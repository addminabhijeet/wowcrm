<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\GoogleSheetData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class GoogleSheetController extends Controller
{
    public function index()
    {
        $data = GoogleSheetData::paginate(10);

        return view('database.admin', compact('data'));
    }

    public function adminfetch(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url'
        ]);

        // Extract spreadsheet ID
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $request->sheet_link, $matches);
        $spreadsheetId = $matches[1] ?? null;

        if (!$spreadsheetId) {
            return back()->with('error', 'Invalid Google Sheet link');
        }

        // Fetch CSV
        $csvUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv";
        $csvData = @file_get_contents($csvUrl);

        if ($csvData === false) {
            return back()->with('error', 'Unable to fetch Google Sheet (maybe private?)');
        }

        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows); // first row as column headers

        $rowIndex = 2;
        $user = Auth::user();

        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue;
            if (count($row) !== count($header)) continue;

            $rowData = array_combine($header, $row);

            // Map CSV headers to database columns
            $mappedData = [
                'sheet_row_number' => $rowIndex,
                'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
                'Name' => $rowData['Name'] ?? null,
                'Email_Address' => $rowData['Email Address'] ?? null,
                'Phone_Number' => $rowData['Phone Number'] ?? null,
                'Location' => $rowData['Location'] ?? null,
                'Relocation' => $rowData['Relocation'] ?? null,
                'Graduation_Date' => isset($rowData['Graduation Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Graduation Date'])->format('Y-m-d') : null,
                'Immigration' => $rowData['Immigration'] ?? null,
                'Course' => $rowData['Course'] ?? null,
                'Amount' => isset($rowData['Amount']) ? (float) str_replace(['$', ','], '', $rowData['Amount']) : null,
                'Qualification' => $rowData['Qualification'] ?? null,
                'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
                'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
                'Time_Zone' => $rowData['Time Zone'] ?? null,
                'View' => $rowData['View'] ?? null,
                'created_by' => "{$user->id}|{$user->role}",
            ];

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                $mappedData
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.admin')->with('success', 'Data fetched successfully!');
    }


    public function adminupdate(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID is required']);
        }

        $row = GoogleSheetData::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $rowData = json_decode($request->input('data'), true);
        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');

                // Delete old resume file if exists
                if ($row->resume && Storage::disk('public')->exists($row->resume)) {
                    Storage::disk('public')->delete($row->resume);
                }

                $row->resume = $filePath; // Store file path instead of just filename

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()]);
            }
        }

        // Prepare update data
        $updateData = [
            'Date' => isset($rowData['Date']) && !empty($rowData['Date']) ?
                $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            'Phone_Number' => $rowData['Phone Number'] ?? null,
            'Location' => $rowData['Location'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => isset($rowData['Graduation Date']) && !empty($rowData['Graduation Date']) ?
                $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) ?
                $this->parseAmount($rowData['Amount']) : null,
            'Qualification' => $rowData['Qualification'] ?? null,
            'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
            'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
            'Time_Zone' => $rowData['Time Zone'] ?? null,
            'updated_at' => now(),
        ];

        // Only update resume if it was uploaded
        if ($request->hasFile('resume')) {
            $updateData['resume'] = $row->resume;
        }

        try {
            $row->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($record->resume) ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function adminstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $user = Auth::user();
        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 0;
        $nextRow = $maxRow + 1;

        $record = new GoogleSheetData();
        $record->sheet_row_number = $nextRow;
        $record->created_by = $user->id . '|admin';

        // Map frontend keys to DB columns
        $columnMap = [
            'Date' => 'Date',
            'Name' => 'Name',
            'Email Address' => 'Email_Address',
            'Phone Number' => 'Phone_Number',
            'Location' => 'Location',
            'Relocation' => 'Relocation',
            'Graduation Date' => 'Graduation_Date',
            'Immigration' => 'Immigration',
            'Course' => 'Course',
            'Amount' => 'Amount',
            'Qualification' => 'Qualification',
            'Exe Remarks' => 'Exe_Remarks',
            '1st Follow Up Remarks' => 'First_Follow_Up_Remarks',
            'Time Zone' => 'Time_Zone',
        ];

        // Assign values safely
        foreach ($rowData as $key => $val) {
            if (!isset($columnMap[$key])) continue;
            $column = $columnMap[$key];

            if (in_array($column, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($column === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
            }

            $record->$column = $val;
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');
                $record->resume = $filePath; // Store file path
            } catch (\Exception $e) {
                // Continue without resume if upload fails
                $record->resume = null;
            }
        }

        try {
            $record->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'resume_path' => !empty($record->resume) ? true : false
        ]);
    }

    // Add a method to serve the PDF files
    public function viewadminResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    // Add a method to download the PDF files
    public function downloadadminResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath));
    }


    public function senior()
    {
        $authUser = Auth::user();

        // Build patterns for LIKE match
        $userPattern = "%:" . $authUser->id . "|senior";
        $zeroPattern = "%:0|senior";

        $data = GoogleSheetData::where(function ($q) use ($authUser, $userPattern, $zeroPattern) {
            // Direct match with "id|senior"
            $q->where('created_by', $authUser->id . '|senior')
                // Direct match with "0|senior"
                ->orWhere('created_by', '0|senior')
                // Matches if last part is ":id|senior"
                ->orWhere('created_by', 'LIKE', $userPattern)
                // Matches if last part is ":0|senior"
                ->orWhere('created_by', 'LIKE', $zeroPattern);
        })
            // Ensure it's truly the LAST part of created_by
            ->where(function ($q) use ($authUser) {
                $q->whereRaw("RIGHT(created_by, LENGTH(?)) = ?", [$authUser->id . '|senior', $authUser->id . '|senior'])
                    ->orWhereRaw("RIGHT(created_by, LENGTH(?)) = ?", ['0|senior', '0|senior']);
            })
            ->paginate(10);

        // Map forwarded_by dynamically
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $parts = explode('|', $item->created_by ?? '');
            $userId = $parts[0] ?? null;
            $role   = $parts[1] ?? 'unknown';

            if ($userId == $authUser->id) {
                $forwardedBy = "SELF ({$userId}) ({$role})";
            } elseif ($userId == 0) {
                $forwardedBy = "SYSTEM (0) ({$role})";
            } else {
                $user = \App\Models\User::find($userId);
                $name = $user ? $user->name : 'Unknown';
                $forwardedBy = "{$name} ({$userId}) ({$role})";
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });

        return view('database.senior', compact('data'));
    }




    public function seniorfetch(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url'
        ]);

        // Extract spreadsheet ID
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $request->sheet_link, $matches);
        $spreadsheetId = $matches[1] ?? null;

        if (!$spreadsheetId) {
            return back()->with('error', 'Invalid Google Sheet link');
        }

        // Fetch CSV
        $csvUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv";
        $csvData = @file_get_contents($csvUrl);

        if ($csvData === false) {
            return back()->with('error', 'Unable to fetch Google Sheet (maybe private?)');
        }

        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows); // first row as column headers

        $rowIndex = 2;
        $user = Auth::user();

        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue;
            if (count($row) !== count($header)) continue;

            $rowData = array_combine($header, $row);

            // Map CSV headers to database columns
            $mappedData = [
                'sheet_row_number' => $rowIndex,
                'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
                'Name' => $rowData['Name'] ?? null,
                'Email_Address' => $rowData['Email Address'] ?? null,
                'Phone_Number' => $rowData['Phone Number'] ?? null,
                'Location' => $rowData['Location'] ?? null,
                'Relocation' => $rowData['Relocation'] ?? null,
                'Graduation_Date' => isset($rowData['Graduation Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Graduation Date'])->format('Y-m-d') : null,
                'Immigration' => $rowData['Immigration'] ?? null,
                'Course' => $rowData['Course'] ?? null,
                'Amount' => isset($rowData['Amount']) ? (float) str_replace(['$', ','], '', $rowData['Amount']) : null,
                'Qualification' => $rowData['Qualification'] ?? null,
                'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
                'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
                'Time_Zone' => $rowData['Time Zone'] ?? null,
                'View' => $rowData['View'] ?? null,
                'created_by' => "{$user->id}|{$user->role}",
            ];

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                $mappedData
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.senior')->with('success', 'Data fetched successfully!');
    }

    public function seniorupdate(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID is required']);
        }

        $row = GoogleSheetData::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $rowData = json_decode($request->input('data'), true);
        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');

                // Delete old resume file if exists
                if ($row->resume && Storage::disk('public')->exists($row->resume)) {
                    Storage::disk('public')->delete($row->resume);
                }

                $row->resume = $filePath; // Store file path instead of just filename

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()]);
            }
        }

        // Prepare update data
        $updateData = [
            'Date' => isset($rowData['Date']) && !empty($rowData['Date']) ?
                $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            'Phone_Number' => $rowData['Phone Number'] ?? null,
            'Location' => $rowData['Location'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => isset($rowData['Graduation Date']) && !empty($rowData['Graduation Date']) ?
                $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) ?
                $this->parseAmount($rowData['Amount'])
                : $row->Amount,
            'Qualification' => $rowData['Qualification'] ?? null,
            'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
            'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
            'Time_Zone' => $rowData['Time Zone'] ?? null,
            'updated_at' => now(),
        ];

        // Only update resume if it was uploaded
        if ($request->hasFile('resume')) {
            $updateData['resume'] = $row->resume;
        }

        // Start with existing created_by value
        $updateData['created_by'] = $row->created_by;

        if (isset($rowData['Exe Remarks'])) {
            $exeRemark = $rowData['Exe Remarks'];

            if ($exeRemark === 'Ready To Paid') {
                // Append ":0|accountant" only if not already present
                if (strpos($updateData['created_by'], ':0|accountant') === false) {
                    $updateData['created_by'] .= ':0|accountant';
                }

                //  Replace "0|senior" with actual senior ID (only if it ends with 0|senior)
                if (preg_match('/0\|senior$/', $updateData['created_by'])) {
                    $updateData['created_by'] = preg_replace(
                        '/0\|senior$/',
                        $id . '|senior',
                        $updateData['created_by']
                    );
                }
            } elseif ($exeRemark === 'Called & Mailed') {
                $tag = $id . '|senior';
                $zerotag = '0|senior';

                // Get the last segment after the last colon
                $parts = explode(':', $updateData['created_by']);
                $lastPart = end($parts);

                // Append only if the last part exactly matches the tag
                if ($lastPart === $tag) {
                    $updateData['created_by'] .= ':' . $zerotag;
                }
            } else {
                // For all other remarks, apply "Revert To Junior" logic
                // Match any integer followed by "|junior"
                if (preg_match('/(\d+)\|junior/', $updateData['created_by'], $matches)) {
                    $juniorId = $matches[1]; // Extract the integer
                    $tag = $juniorId . '|junior';
                    // Append only if tag already exists in created_by
                    if (strpos($updateData['created_by'], $tag) !== false) {
                        $updateData['created_by'] .= ':' . $tag;
                    }
                }

                //  Replace "0|senior" with actual senior ID (only if it ends with 0|senior)
                if (preg_match('/0\|senior$/', $updateData['created_by'])) {
                    $updateData['created_by'] = preg_replace(
                        '/0\|senior$/',
                        $id . '|senior',
                        $updateData['created_by']
                    );
                }
            }
        }



        try {
            $row->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($record->resume) ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function seniorstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $user = Auth::user();
        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 0;
        $nextRow = $maxRow + 1;

        $record = new GoogleSheetData();
        $record->sheet_row_number = $nextRow;
        $record->created_by = $user->id . '|senior';

        // Map frontend keys to DB columns
        $columnMap = [
            'Date' => 'Date',
            'Name' => 'Name',
            'Email Address' => 'Email_Address',
            'Phone Number' => 'Phone_Number',
            'Location' => 'Location',
            'Relocation' => 'Relocation',
            'Graduation Date' => 'Graduation_Date',
            'Immigration' => 'Immigration',
            'Course' => 'Course',
            'Amount' => 'Amount',
            'Qualification' => 'Qualification',
            'Exe Remarks' => 'Exe_Remarks',
            '1st Follow Up Remarks' => 'First_Follow_Up_Remarks',
            'Time Zone' => 'Time_Zone',
        ];

        // Assign values safely
        foreach ($rowData as $key => $val) {
            if (!isset($columnMap[$key])) continue;
            $column = $columnMap[$key];

            if (in_array($column, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($column === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
            }

            if ($column === 'Exe_Remarks') {
                $exeRemarksValue = $val; // capture Exe_Remarks for condition check
            }

            $record->$column = $val;
        }

        // Set created_by conditionally based on Exe_Remarks
        if ($exeRemarksValue === 'Called & Mailed') {
            $record->created_by = $user->id . '|senior:0|senior';
        } elseif ($exeRemarksValue === 'Ready To Paid') {
            $record->created_by = $user->id . '|senior:0|accountant';
        } else {
            $record->created_by = $user->id . '|senior';
        }


        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');
                $record->resume = $filePath; // Store file path
            } catch (\Exception $e) {
                // Continue without resume if upload fails
                $record->resume = null;
            }
        }

        try {
            $record->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'resume_path' => !empty($record->resume) ? true : false
        ]);
    }

    // Add a method to serve the PDF files
    public function viewseniorResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    // Add a method to download the PDF files
    public function downloadseniorResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath));
    }

    private function parseDate($dateString)
    {
        try {
            return \Carbon\Carbon::createFromFormat('m/d/Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseAmount($amountString)
    {
        if (is_null($amountString) || $amountString === '') {
            return null;
        }

        // If already numeric, just return float
        if (is_numeric($amountString)) {
            return (float) $amountString;
        }

        // Otherwise clean it
        return (float) str_replace(['$', ','], '', $amountString);
    }


    public function junior()
    {
        $authUser = Auth::user();
        $pattern = "%:" . $authUser->id . "|junior"; // will check last part

        $data = GoogleSheetData::where(function ($q) use ($authUser, $pattern) {
            $q->where('created_by', $authUser->id . '|junior') // exact match
                ->orWhere('created_by', 'LIKE', $pattern);       // ends with :id|junior
        })
            ->whereRaw("RIGHT(created_by, LENGTH(?)) = ?", [$authUser->id . '|junior', $authUser->id . '|junior']) // ensures it's last part
            ->paginate(10);

        return view('database.junior', compact('data'));
    }


    public function juniorfetch(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url'
        ]);

        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $request->sheet_link, $matches);
        $spreadsheetId = $matches[1] ?? null;

        if (!$spreadsheetId) {
            return back()->with('error', 'Invalid Google Sheet link');
        }

        $csvUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv";
        $csvData = @file_get_contents($csvUrl);

        if ($csvData === false) {
            return back()->with('error', 'Unable to fetch Google Sheet (maybe private?)');
        }

        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows);

        $rowIndex = 2;
        $user = Auth::user();

        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue;
            if (count($row) !== count($header)) continue;

            $rowData = array_combine($header, $row);

            // Map CSV headers to database columns
            $mappedData = [
                'sheet_row_number' => $rowIndex,
                'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
                'Name' => $rowData['Name'] ?? null,
                'Email_Address' => $rowData['Email Address'] ?? null,
                'Phone_Number' => $rowData['Phone Number'] ?? null,
                'Location' => $rowData['Location'] ?? null,
                'Relocation' => $rowData['Relocation'] ?? null,
                'Graduation_Date' => isset($rowData['Graduation Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Graduation Date'])->format('Y-m-d') : null,
                'Immigration' => $rowData['Immigration'] ?? null,
                'Course' => $rowData['Course'] ?? null,
                'Amount' => isset($rowData['Amount']) ? (float) str_replace(['$', ','], '', $rowData['Amount']) : null,
                'Qualification' => $rowData['Qualification'] ?? null,
                'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
                'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
                'Time_Zone' => $rowData['Time Zone'] ?? null,
                'View' => $rowData['View'] ?? null,
                'created_by' => "{$user->id}|{$user->role}",
            ];

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                $mappedData
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.junior')->with('success', 'Data fetched successfully!');
    }

    public function juniorupdate(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID is required']);
        }

        $row = GoogleSheetData::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $rowData = json_decode($request->input('data'), true);
        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');

                // Delete old resume file if exists
                if ($row->resume && Storage::disk('public')->exists($row->resume)) {
                    Storage::disk('public')->delete($row->resume);
                }

                $row->resume = $filePath; // Store file path instead of just filename

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()]);
            }
        }

        // Prepare update data
        $updateData = [
            'Date' => isset($rowData['Date']) && !empty($rowData['Date']) ?
                $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            'Phone_Number' => $rowData['Phone Number'] ?? null,
            'Location' => $rowData['Location'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => isset($rowData['Graduation Date']) && !empty($rowData['Graduation Date']) ?
                $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) ?
                $this->parseAmount($rowData['Amount'])
                : $row->Amount,
            'Qualification' => $rowData['Qualification'] ?? null,
            'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
            'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
            'Time_Zone' => $rowData['Time Zone'] ?? null,
            'updated_at' => now(),
        ];

        // Only update resume if it was uploaded
        if ($request->hasFile('resume')) {
            $updateData['resume'] = $row->resume;
        }

        // === New created_by logic ===
        if (isset($rowData['Exe Remarks']) && $rowData['Exe Remarks'] === 'Called & Mailed') {
            // Append only once if not already present
            if (strpos($row->created_by, ':0|senior') === false) {
                $updateData['created_by'] = $row->created_by . ':0|senior';
            } else {
                $updateData['created_by'] = $row->created_by;
            }
        } else {
            $updateData['created_by'] = $row->created_by;
        }

        try {
            $row->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($row->resume) ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }



    public function juniorstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $user = Auth::user();
        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 0;
        $nextRow = $maxRow + 1;

        $record = new GoogleSheetData();
        $record->sheet_row_number = $nextRow;

        // Map frontend keys to DB columns
        $columnMap = [
            'Date' => 'Date',
            'Name' => 'Name',
            'Email Address' => 'Email_Address',
            'Phone Number' => 'Phone_Number',
            'Location' => 'Location',
            'Relocation' => 'Relocation',
            'Graduation Date' => 'Graduation_Date',
            'Immigration' => 'Immigration',
            'Course' => 'Course',
            'Amount' => 'Amount',
            'Qualification' => 'Qualification',
            'Exe Remarks' => 'Exe_Remarks',
            '1st Follow Up Remarks' => 'First_Follow_Up_Remarks',
            'Time Zone' => 'Time_Zone',
        ];

        $exeRemarksValue = null;

        // Assign values safely
        foreach ($rowData as $key => $val) {
            if (!isset($columnMap[$key])) continue;
            $column = $columnMap[$key];

            if (in_array($column, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($column === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
            }

            if ($column === 'Exe_Remarks') {
                $exeRemarksValue = $val; // capture Exe_Remarks for condition check
            }

            $record->$column = $val;
        }

        // Set created_by conditionally
        if ($exeRemarksValue === 'Called & Mailed') {
            $record->created_by = $user->id . '|junior:0|senior';
        } else {
            $record->created_by = $user->id . '|junior';
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');
                $record->resume = $filePath; // Store file path
            } catch (\Exception $e) {
                // Continue without resume if upload fails
                $record->resume = null;
            }
        }

        try {
            $record->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'resume_path' => !empty($record->resume) ? true : false
        ]);
    }


    // Add a method to serve the PDF files
    public function viewjuniorResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    // Add a method to download the PDF files
    public function downloadjuniorResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath));
    }


    public function accountant()
    {
        $authUser = Auth::user();

        // Build patterns for LIKE match
        $userPattern = "%:" . $authUser->id . "|accountant";
        $zeroPattern = "%:0|accountant";

        $data = GoogleSheetData::where(function ($q) use ($authUser, $userPattern, $zeroPattern) {
            // Direct match with "id|accountant"
            $q->where('created_by', $authUser->id . '|accountant')
                // Direct match with "0|accountant"
                ->orWhere('created_by', '0|accountant')
                // Matches if last part is ":id|accountant"
                ->orWhere('created_by', 'LIKE', $userPattern)
                // Matches if last part is ":0|accountant"
                ->orWhere('created_by', 'LIKE', $zeroPattern);
        })
            // Ensure it's truly the LAST part of created_by
            ->where(function ($q) use ($authUser) {
                $q->whereRaw("RIGHT(created_by, LENGTH(?)) = ?", [$authUser->id . '|accountant', $authUser->id . '|accountant'])
                    ->orWhereRaw("RIGHT(created_by, LENGTH(?)) = ?", ['0|accountant', '0|accountant']);
            })
            ->paginate(10);

        // Map forwarded_by dynamically
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $parts = explode('|', $item->created_by ?? '');
            $userId = $parts[0] ?? null;
            $role   = $parts[1] ?? 'unknown';

            if ($userId == $authUser->id) {
                $forwardedBy = "SELF ({$userId}) ({$role})";
            } elseif ($userId == 0) {
                $forwardedBy = "SYSTEM (0) ({$role})";
            } else {
                $user = \App\Models\User::find($userId);
                $name = $user ? $user->name : 'Unknown';
                $forwardedBy = "{$name} ({$userId}) ({$role})";
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });

        return view('database.accountant', compact('data'));
    }




    public function accountantfetch(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url'
        ]);

        // Extract spreadsheet ID
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $request->sheet_link, $matches);
        $spreadsheetId = $matches[1] ?? null;

        if (!$spreadsheetId) {
            return back()->with('error', 'Invalid Google Sheet link');
        }

        // Fetch CSV
        $csvUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv";
        $csvData = @file_get_contents($csvUrl);

        if ($csvData === false) {
            return back()->with('error', 'Unable to fetch Google Sheet (maybe private?)');
        }

        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows); // first row as column headers

        $rowIndex = 2;
        $user = Auth::user();

        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue;
            if (count($row) !== count($header)) continue;

            $rowData = array_combine($header, $row);

            // Map CSV headers to database columns
            $mappedData = [
                'sheet_row_number' => $rowIndex,
                'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
                'Name' => $rowData['Name'] ?? null,
                'Email_Address' => $rowData['Email Address'] ?? null,
                'Phone_Number' => $rowData['Phone Number'] ?? null,
                'Location' => $rowData['Location'] ?? null,
                'Relocation' => $rowData['Relocation'] ?? null,
                'Graduation_Date' => isset($rowData['Graduation Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Graduation Date'])->format('Y-m-d') : null,
                'Immigration' => $rowData['Immigration'] ?? null,
                'Course' => $rowData['Course'] ?? null,
                'Amount' => isset($rowData['Amount']) ? (float) str_replace(['$', ','], '', $rowData['Amount']) : null,
                'Qualification' => $rowData['Qualification'] ?? null,
                'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
                'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
                'Time_Zone' => $rowData['Time Zone'] ?? null,
                'View' => $rowData['View'] ?? null,
                'created_by' => "{$user->id}|{$user->role}",
            ];

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                $mappedData
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.accountant')->with('success', 'Data fetched successfully!');
    }

    public function accountantupdate(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID is required']);
        }

        $row = GoogleSheetData::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $rowData = json_decode($request->input('data'), true);
        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');

                // Delete old resume file if exists
                if ($row->resume && Storage::disk('public')->exists($row->resume)) {
                    Storage::disk('public')->delete($row->resume);
                }

                $row->resume = $filePath; // Store file path instead of just filename

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()]);
            }
        }

        // Prepare update data
        $updateData = [
            'Date' => isset($rowData['Date']) && !empty($rowData['Date']) ?
                $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            'Phone_Number' => $rowData['Phone Number'] ?? null,
            'Location' => $rowData['Location'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => isset($rowData['Graduation Date']) && !empty($rowData['Graduation Date']) ?
                $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) ?
                $this->parseAmount($rowData['Amount'])
                : $row->Amount,
            'Qualification' => $rowData['Qualification'] ?? null,
            'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
            'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
            'Time_Zone' => $rowData['Time Zone'] ?? null,
            'updated_at' => now(),
        ];

        // Only update resume if it was uploaded
        if ($request->hasFile('resume')) {
            $updateData['resume'] = $row->resume;
        }

        // Start with existing created_by value
        $updateData['created_by'] = $row->created_by;

        if (isset($rowData['Exe Remarks'])) {
            $exeRemark = $rowData['Exe Remarks'];

            if ($exeRemark === 'Ready To Paid') {
                // Append ":0|accountant" only if not already present
                if (strpos($updateData['created_by'], ':0|accountant') === false) {
                    $updateData['created_by'] .= ':0|accountant';
                }
            } elseif ($exeRemark === 'Called & Mailed') {
                $tag = $id . '|accountant';
                // Append only if created_by exactly matches the tag
                if ($updateData['created_by'] === $tag) {
                    $updateData['created_by'] .= ':' . $tag;
                }
            } else {
                // For all other remarks, apply "Revert To Junior" logic
                $tag = $id . '|junior';
                // Append only if tag already exists in created_by
                if (strpos($updateData['created_by'], $tag) !== false) {
                    $updateData['created_by'] .= ':' . $tag;
                }
            }
        }


        try {
            $row->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($record->resume) ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function accountantstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $user = Auth::user();
        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 0;
        $nextRow = $maxRow + 1;

        $record = new GoogleSheetData();
        $record->sheet_row_number = $nextRow;
        $record->created_by = $user->id . '|accountant';

        // Map frontend keys to DB columns
        $columnMap = [
            'Date' => 'Date',
            'Name' => 'Name',
            'Email Address' => 'Email_Address',
            'Phone Number' => 'Phone_Number',
            'Location' => 'Location',
            'Relocation' => 'Relocation',
            'Graduation Date' => 'Graduation_Date',
            'Immigration' => 'Immigration',
            'Course' => 'Course',
            'Amount' => 'Amount',
            'Qualification' => 'Qualification',
            'Exe Remarks' => 'Exe_Remarks',
            '1st Follow Up Remarks' => 'First_Follow_Up_Remarks',
            'Time Zone' => 'Time_Zone',
        ];

        // Assign values safely
        foreach ($rowData as $key => $val) {
            if (!isset($columnMap[$key])) continue;
            $column = $columnMap[$key];

            if (in_array($column, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($column === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
            }

            if ($column === 'Exe_Remarks') {
                $exeRemarksValue = $val; // capture Exe_Remarks for condition check
            }

            $record->$column = $val;
        }

        // Set created_by conditionally based on Exe_Remarks
        if ($exeRemarksValue === 'Called & Mailed') {
            $record->created_by = $user->id . '|accountant:' . $user->id . '|accountant';
        } elseif ($exeRemarksValue === 'Ready To Paid') {
            $record->created_by = $user->id . '|accountant:' . $user->id . '|accountant:0|accountant';
        } else {
            $record->created_by = $user->id . '|accountant';
        }


        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');
                $record->resume = $filePath; // Store file path
            } catch (\Exception $e) {
                // Continue without resume if upload fails
                $record->resume = null;
            }
        }

        try {
            $record->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'resume_path' => !empty($record->resume) ? true : false
        ]);
    }

    // Add a method to serve the PDF files
    public function viewaccountantResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    // Add a method to download the PDF files
    public function downloadaccountantResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath));
    }


    public function trainer()
    {
        $authUser = Auth::user();

        // Build patterns for LIKE match
        $userPattern = "%:" . $authUser->id . "|trainer";
        $zeroPattern = "%:0|trainer";

        $data = GoogleSheetData::where(function ($q) use ($authUser, $userPattern, $zeroPattern) {
            // Direct match with "id|trainer"
            $q->where('created_by', $authUser->id . '|trainer')
                // Direct match with "0|trainer"
                ->orWhere('created_by', '0|trainer')
                // Matches if last part is ":id|trainer"
                ->orWhere('created_by', 'LIKE', $userPattern)
                // Matches if last part is ":0|trainer"
                ->orWhere('created_by', 'LIKE', $zeroPattern);
        })
            // Ensure it's truly the LAST part of created_by
            ->where(function ($q) use ($authUser) {
                $q->whereRaw("RIGHT(created_by, LENGTH(?)) = ?", [$authUser->id . '|trainer', $authUser->id . '|trainer'])
                    ->orWhereRaw("RIGHT(created_by, LENGTH(?)) = ?", ['0|trainer', '0|trainer']);
            })
            ->paginate(10);

        // Map forwarded_by dynamically
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $parts = explode('|', $item->created_by ?? '');
            $userId = $parts[0] ?? null;
            $role   = $parts[1] ?? 'unknown';

            if ($userId == $authUser->id) {
                $forwardedBy = "SELF ({$userId}) ({$role})";
            } elseif ($userId == 0) {
                $forwardedBy = "SYSTEM (0) ({$role})";
            } else {
                $user = \App\Models\User::find($userId);
                $name = $user ? $user->name : 'Unknown';
                $forwardedBy = "{$name} ({$userId}) ({$role})";
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });

        return view('database.trainer', compact('data'));
    }




    public function trainerfetch(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url'
        ]);

        // Extract spreadsheet ID
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $request->sheet_link, $matches);
        $spreadsheetId = $matches[1] ?? null;

        if (!$spreadsheetId) {
            return back()->with('error', 'Invalid Google Sheet link');
        }

        // Fetch CSV
        $csvUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv";
        $csvData = @file_get_contents($csvUrl);

        if ($csvData === false) {
            return back()->with('error', 'Unable to fetch Google Sheet (maybe private?)');
        }

        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows); // first row as column headers

        $rowIndex = 2;
        $user = Auth::user();

        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue;
            if (count($row) !== count($header)) continue;

            $rowData = array_combine($header, $row);

            // Map CSV headers to database columns
            $mappedData = [
                'sheet_row_number' => $rowIndex,
                'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
                'Name' => $rowData['Name'] ?? null,
                'Email_Address' => $rowData['Email Address'] ?? null,
                'Phone_Number' => $rowData['Phone Number'] ?? null,
                'Location' => $rowData['Location'] ?? null,
                'Relocation' => $rowData['Relocation'] ?? null,
                'Graduation_Date' => isset($rowData['Graduation Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Graduation Date'])->format('Y-m-d') : null,
                'Immigration' => $rowData['Immigration'] ?? null,
                'Course' => $rowData['Course'] ?? null,
                'Amount' => isset($rowData['Amount']) ? (float) str_replace(['$', ','], '', $rowData['Amount']) : null,
                'Qualification' => $rowData['Qualification'] ?? null,
                'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
                'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
                'Time_Zone' => $rowData['Time Zone'] ?? null,
                'View' => $rowData['View'] ?? null,
                'created_by' => "{$user->id}|{$user->role}",
            ];

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                $mappedData
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.trainer')->with('success', 'Data fetched successfully!');
    }

    public function trainerupdate(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID is required']);
        }

        $row = GoogleSheetData::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $rowData = json_decode($request->input('data'), true);
        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');

                // Delete old resume file if exists
                if ($row->resume && Storage::disk('public')->exists($row->resume)) {
                    Storage::disk('public')->delete($row->resume);
                }

                $row->resume = $filePath; // Store file path instead of just filename

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()]);
            }
        }

        // Prepare update data
        $updateData = [
            'Date' => isset($rowData['Date']) && !empty($rowData['Date']) ?
                $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            'Phone_Number' => $rowData['Phone Number'] ?? null,
            'Location' => $rowData['Location'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => isset($rowData['Graduation Date']) && !empty($rowData['Graduation Date']) ?
                $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) ?
                $this->parseAmount($rowData['Amount'])
                : $row->Amount,
            'Qualification' => $rowData['Qualification'] ?? null,
            'Exe_Remarks' => $rowData['Exe Remarks'] ?? null,
            'First_Follow_Up_Remarks' => $rowData['1st Follow Up Remarks'] ?? null,
            'Time_Zone' => $rowData['Time Zone'] ?? null,
            'updated_at' => now(),
        ];

        // Only update resume if it was uploaded
        if ($request->hasFile('resume')) {
            $updateData['resume'] = $row->resume;
        }

        // Start with existing created_by value
        $updateData['created_by'] = $row->created_by;

        if (isset($rowData['Exe Remarks'])) {
            $exeRemark = $rowData['Exe Remarks'];

            if ($exeRemark === 'Ready To Paid') {
                // Append ":0|accountant" only if not already present
                if (strpos($updateData['created_by'], ':0|accountant') === false) {
                    $updateData['created_by'] .= ':0|accountant';
                }
            } elseif ($exeRemark === 'Called & Mailed') {
                $tag = $id . '|trainer';
                // Append only if created_by exactly matches the tag
                if ($updateData['created_by'] === $tag) {
                    $updateData['created_by'] .= ':' . $tag;
                }
            } else {
                // For all other remarks, apply "Revert To Junior" logic
                $tag = $id . '|junior';
                // Append only if tag already exists in created_by
                if (strpos($updateData['created_by'], $tag) !== false) {
                    $updateData['created_by'] .= ':' . $tag;
                }
            }
        }


        try {
            $row->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($record->resume) ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function trainerstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $user = Auth::user();
        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 0;
        $nextRow = $maxRow + 1;

        $record = new GoogleSheetData();
        $record->sheet_row_number = $nextRow;
        $record->created_by = $user->id . '|trainer';

        // Map frontend keys to DB columns
        $columnMap = [
            'Date' => 'Date',
            'Name' => 'Name',
            'Email Address' => 'Email_Address',
            'Phone Number' => 'Phone_Number',
            'Location' => 'Location',
            'Relocation' => 'Relocation',
            'Graduation Date' => 'Graduation_Date',
            'Immigration' => 'Immigration',
            'Course' => 'Course',
            'Amount' => 'Amount',
            'Qualification' => 'Qualification',
            'Exe Remarks' => 'Exe_Remarks',
            '1st Follow Up Remarks' => 'First_Follow_Up_Remarks',
            'Time Zone' => 'Time_Zone',
        ];

        // Assign values safely
        foreach ($rowData as $key => $val) {
            if (!isset($columnMap[$key])) continue;
            $column = $columnMap[$key];

            if (in_array($column, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($column === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
            }

            if ($column === 'Exe_Remarks') {
                $exeRemarksValue = $val; // capture Exe_Remarks for condition check
            }

            $record->$column = $val;
        }

        // Set created_by conditionally based on Exe_Remarks
        if ($exeRemarksValue === 'Called & Mailed') {
            $record->created_by = $user->id . '|trainer:' . $user->id . '|trainer';
        } elseif ($exeRemarksValue === 'Ready To Paid') {
            $record->created_by = $user->id . '|trainer:' . $user->id . '|trainer:0|accountant';
        } else {
            $record->created_by = $user->id . '|trainer';
        }


        // Handle resume file upload - Save actual file content
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Validate it's a PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Store the actual file content
                $filePath = $file->storeAs('resumes', $newName, 'public');
                $record->resume = $filePath; // Store file path
            } catch (\Exception $e) {
                // Continue without resume if upload fails
                $record->resume = null;
            }
        }

        try {
            $record->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'resume_path' => !empty($record->resume) ? true : false
        ]);
    }

    // Add a method to serve the PDF files
    public function viewtrainerResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    // Add a method to download the PDF files
    public function downloadtrainerResume($id)
    {
        $row = GoogleSheetData::find($id);

        if (!$row || !$row->resume) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $row->resume);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath));
    }
}
