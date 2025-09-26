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
        $data = GoogleSheetData::all();
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


    public function adminupdate(Request $request, $id)
    {
        $rowData = $request->input('data');

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $row = GoogleSheetData::find($id);

        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $user = Auth::user();

        $row->update([
            'data'       => json_encode($rowData, JSON_UNESCAPED_UNICODE),
            'created_by' => "{$user->id}|{$user->role}",
        ]);

        return response()->json([
            'success' => true,
            'row' => [
                'id'               => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'data'             => $rowData,
                'created_by'       => $row->created_by
            ]
        ]);
    }

    public function adminstore(Request $request)
    {
        $rowData = $request->input('rows.0');

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 1;
        $nextRow = $maxRow + 1;

        $user = Auth::user();

        // 🔑 Use updateOrCreate instead of create
        $newRow = GoogleSheetData::updateOrCreate(
            ['sheet_row_number' => $nextRow],
            [
                'data'       => json_encode($rowData, JSON_UNESCAPED_UNICODE),
                'created_by' => "{$user->id}|{$user->role}",
            ]
        );

        return response()->json([
            'success' => true,
            'rows' => [[
                'id'               => $newRow->id,
                'sheet_row_number' => $newRow->sheet_row_number,
                'data'             => $rowData,
                'created_by'       => $newRow->created_by
            ]]
        ]);
    }

    public function senior()
    {
        $authUser = Auth::user();

        // Base query
        $query = GoogleSheetData::query();

        // Filter rows where 'Exe_Remarks' = 'Called & Mailed' OR created_by = "<user_id>|senior"
        $query->where(function ($q) use ($authUser) {
            $q->where('Exe_Remarks', 'Called & Mailed')
                ->orWhere('created_by', "{$authUser->id}|senior");
        });

        // Paginate 10 per page
        $data = $query->paginate(10);

        // Map forwarded_by dynamically
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $parts = explode('|', $item->created_by ?? '');
            $userId = $parts[0] ?? null;
            $role   = $parts[1] ?? 'unknown';

            if ($userId == $authUser->id) {
                $forwardedBy = "SELF ({$userId}) ({$role})";
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
                'resume_path' => $row->resume // Return the file path for frontend
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
            'resume_path' => $record->resume
        ]);
    }

    // Add a method to serve the PDF files
    public function viewResume($id)
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
    public function downloadResume($id)
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
        return (float) str_replace(['$', ','], '', $amountString);
    }
    // The PDF methods remain the same as they handle file uploads separately
    public function seniorpdfstore(Request $request)
    {
        // This method can remain similar since it handles file uploads
        $rowData = $request->has('rows')
            ? json_decode($request->input('rows')[0], true)
            : [];

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;
            $resumePath = $file->storeAs('resumes', $newName, 'public');
        }

        // Map data to individual columns for PDF store as well
        $mappedData = [
            'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            // ... map other fields as needed
        ];

        $record = GoogleSheetData::create(array_merge($mappedData, [
            'resume' => $resumePath ? basename($resumePath) : null,
        ]));

        return response()->json([
            'success'    => true,
            'id'         => $record->id,
            'resume_url' => $record->resume ? asset('storage/resumes/' . $record->resume) : null,
        ]);
    }



    public function seniorpdfupdate(Request $request, $id)
    {
        $record = GoogleSheetData::findOrFail($id);

        if ($request->has('data')) {
            $data = json_decode($request->input('data'), true);

            // Update individual columns
            $record->update([
                'Date' => isset($data['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $data['Date'])->format('Y-m-d') : null,
                'Name' => $data['Name'] ?? null,
                // ... update other fields
            ]);
        }

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;
            $resumePath = $file->storeAs('resumes', $newName, 'public');
            $record->resume = basename($resumePath);
        }

        $record->save();

        return response()->json([
            'success'    => true,
            'resume_url' => $record->resume ? asset('storage/resumes/' . $record->resume) : null,
        ]);
    }

    public function junior()
    {
        // Fetch 10 entries per page
        $data = GoogleSheetData::paginate(10);

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

    public function juniorupdate(Request $request, $id)
    {
        $row = GoogleSheetData::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $rowData = json_decode($request->input('data'), true);
        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;
            $stored    = $file->storeAs('resumes', $newName, 'public');
            $row->resume = basename($stored);
        }

        // Safely parse dates
        $date = !empty($rowData['Date'])
            ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d')
            : null;

        $graduationDate = !empty($rowData['Graduation Date'])
            ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Graduation Date'])->format('Y-m-d')
            : null;

        // Map flattened columns
        $updateData = [
            'Date'                     => $date,
            'Name'                     => $rowData['Name'] ?? null,
            'Email_Address'            => $rowData['Email Address'] ?? null,
            'Phone_Number'             => $rowData['Phone Number'] ?? null,
            'Location'                 => $rowData['Location'] ?? null,
            'Relocation'               => $rowData['Relocation'] ?? null,
            'Graduation_Date'          => $graduationDate,
            'Immigration'              => $rowData['Immigration'] ?? null,
            'Course'                   => $rowData['Course'] ?? null,
            'Amount'                   => isset($rowData['Amount']) ? (float) str_replace(['$', ','], '', $rowData['Amount']) : null,
            'Qualification'            => $rowData['Qualification'] ?? null,
            'Exe_Remarks'              => $rowData['Exe Remarks'] ?? null,
            'First_Follow_Up_Remarks'  => $rowData['1st Follow Up Remarks'] ?? null,
            'Time_Zone'                => $rowData['Time Zone'] ?? null,
            'View'                     => $rowData['View'] ?? null,
            'resume'                   => $row->resume,
            'data'                     => $rowData, // store JSON too
            'created_by'               => $row->created_by, // keep original creator
        ];

        $row->update($updateData);

        return response()->json([
            'success' => true,
            'row' => [
                'id'         => $row->id,
                'data'       => $row->data,
                'resume_url' => $row->resume ? asset('storage/resumes/' . $row->resume) : null,
            ]
        ]);
    }


    public function juniorstore(Request $request)
    {
        // Decode incoming row data
        $rowData = json_decode($request->input('rows.0'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $user = Auth::user();
        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 0;
        $nextRow = $maxRow + 1;

        $record = new GoogleSheetData();
        $record->sheet_row_number = $nextRow;
        $record->created_by = $user->id . '|junior';

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
            'View' => 'View'
        ];

        // Assign values safely
        foreach ($rowData as $key => $val) {
            if (!isset($columnMap[$key])) continue;
            $column = $columnMap[$key];

            // Convert dates to Y-m-d format
            if (in_array($column, ['Date', 'Graduation_Date']) && !empty($val)) {
                try {
                    $val = \Carbon\Carbon::createFromFormat('m/d/Y', $val)->format('Y-m-d');
                } catch (\Exception $e) {
                    $val = null; // fallback if date parsing fails
                }
            }

            // Convert Amount to float
            if ($column === 'Amount' && !empty($val)) {
                $val = (float) str_replace(['$', ','], '', $val);
            }

            $record->$column = $val;
        }

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;

            try {
                $file->storeAs('resumes', $newName, 'public');
                $record->resume = $newName;
            } catch (\Exception $e) {
                $record->resume = null; // fallback if upload fails
            }
        }

        // Save record safely
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
            'sheet_row_number' => $record->sheet_row_number
        ]);
    }



    // The PDF methods remain the same as they handle file uploads separately
    public function juniorpdfstore(Request $request)
    {
        // This method can remain similar since it handles file uploads
        $rowData = $request->has('rows')
            ? json_decode($request->input('rows')[0], true)
            : [];

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;
            $resumePath = $file->storeAs('resumes', $newName, 'public');
        }

        // Map data to individual columns for PDF store as well
        $mappedData = [
            'Date' => isset($rowData['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $rowData['Date'])->format('Y-m-d') : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $rowData['Email Address'] ?? null,
            // ... map other fields as needed
        ];

        $record = GoogleSheetData::create(array_merge($mappedData, [
            'resume' => $resumePath ? basename($resumePath) : null,
        ]));

        return response()->json([
            'success'    => true,
            'id'         => $record->id,
            'resume_url' => $record->resume ? asset('storage/resumes/' . $record->resume) : null,
        ]);
    }

    public function juniorpdfupdate(Request $request, $id)
    {
        $record = GoogleSheetData::findOrFail($id);

        if ($request->has('data')) {
            $data = json_decode($request->input('data'), true);

            // Update individual columns
            $record->update([
                'Date' => isset($data['Date']) ? \Carbon\Carbon::createFromFormat('m/d/Y', $data['Date'])->format('Y-m-d') : null,
                'Name' => $data['Name'] ?? null,
                // ... update other fields
            ]);
        }

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;
            $resumePath = $file->storeAs('resumes', $newName, 'public');
            $record->resume = basename($resumePath);
        }

        $record->save();

        return response()->json([
            'success'    => true,
            'resume_url' => $record->resume ? asset('storage/resumes/' . $record->resume) : null,
        ]);
    }
}
