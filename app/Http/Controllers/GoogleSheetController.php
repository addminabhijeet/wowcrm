<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\GoogleSheetData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\SmtpSetting;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;


class GoogleSheetController extends Controller
{
    public function admin(Request $request)
    {
        $authUser = Auth::user();
        $search = $request->input('search');
        $rowId = $request->input('row_id');
        $juniorUserId = $request->input('junior_user'); // dropdown value

        $userPattern = "%:" . $authUser->id . "|senior";
        $zeroPattern = "%:0|senior";

        $query = GoogleSheetData::where(function ($q) use ($authUser, $userPattern, $zeroPattern) {
            $q->where('created_by', $authUser->id . '|senior')
                ->orWhere('created_by', '0|senior')
                ->orWhere('created_by', 'LIKE', $userPattern)
                ->orWhere('created_by', 'LIKE', $zeroPattern);
        });

        // Filter by selected junior
        if ($juniorUserId) {
            $query->where(function ($q) use ($juniorUserId) {
                $q->where('created_by', 'LIKE', '%' . $juniorUserId . '|junior%')
                    ->orWhere('created_by', 'LIKE', '%' . $juniorUserId . '|senior%');
            });
        }

        // Search or specific row filter
        if ($rowId) {
            $query->where('id', $rowId);
        } elseif ($search && strlen($search) >= 3) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', "%{$search}%")
                    ->orWhere('Email_Address', 'LIKE', "%{$search}%")
                    ->orWhere('Phone_Number', 'LIKE', "%{$search}%");
            });
        }

        // Pagination with appended filters for AJAX navigation
        $data = $query->orderBy('Date', 'desc')->paginate(10);
        $data->appends([
            'search' => $search,
            'row_id' => $rowId,
            'junior_user' => $juniorUserId,
        ]);

        // Map forwarded_by dynamically
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $forwardedBy = '';

            if (!empty($item->created_by)) {
                $entries = explode(':', $item->created_by);
                $names = [];

                foreach ($entries as $entry) {
                    $parts = explode('|', $entry);
                    $userId = $parts[0] ?? null;
                    $role   = $parts[1] ?? 'unknown';

                    if ($userId == $authUser->id) {
                        $names[] = "SELF ({$userId}) ({$role})";
                    } elseif ($userId == 0) {
                        $names[] = "SYSTEM (0) ({$role})";
                    } else {
                        $user = \App\Models\User::find($userId);
                        $name = $user ? $user->name : 'Unknown';
                        $names[] = "{$name} ({$userId}) ({$role})";
                    }
                }

                $forwardedBy = implode(' → ', $names);
            } else {
                $forwardedBy = 'N/A';
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });

        $juniorUsers = \App\Models\User::whereIn('role', ['junior', 'senior'])
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'phone', 'designation']);

        // Handle AJAX request for both search and pagination
        if ($request->ajax()) {
            return view('database.partials.senior_table', compact('data'))->render();
        }

        return view('database.admin', compact('data', 'juniorUsers'));
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

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        $exists = GoogleSheetData::where('Email_Address', $email)->exists();

        return response()->json([
            'exists' => $exists
        ]);
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

        // --- Extract Email & Phone for uniqueness check ---
        $email = $rowData['Email Address'] ?? null;
        $phone = $rowData['Phone Number'] ?? null;

        // Check for duplicate Email (ignore current record)
        if (!empty($email)) {
            $emailExists = GoogleSheetData::where('Email_Address', $email)
                ->where('id', '!=', $id)
                ->exists();

            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exists in records.'
                ]);
            }
        }

        // Check for duplicate Phone (ignore current record)
        if (!empty($phone)) {
            $phoneExists = GoogleSheetData::where('Phone_Number', $phone)
                ->where('id', '!=', $id)
                ->exists();

            if ($phoneExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already exists in records.'
                ]);
            }
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
            'Email_Address' => $email,
            'Phone_Number' => $phone,
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
                'resume_path' => !empty($row->resume) ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fill Full Detail to Save.'
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

        // Temporary storage for checking unique fields
        $email = null;
        $phone = null;

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

            if ($column === 'Email_Address') {
                $email = $val;
            }

            if ($column === 'Phone_Number') {
                $phone = $val;
            }

            $record->$column = $val;
        }

        // --- Check for duplicate Email or Phone ---
        if (!empty($email)) {
            $emailExists = GoogleSheetData::where('Email_Address', $email)->exists();
            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exists in records.'
                ]);
            }
        }

        if (!empty($phone)) {
            $phoneExists = GoogleSheetData::where('Phone_Number', $phone)->exists();
            if ($phoneExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already exists in records.'
                ]);
            }
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
                'message' => 'Fill Full Detail to Save.'
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

    public function senior(Request $request)
    {
        $authUser = Auth::user();
        $search = $request->input('search');
        $rowId = $request->input('row_id');
        $juniorUserId = $request->input('junior_user'); // dropdown value

        $userPattern = "%:" . $authUser->id . "|senior";
        $zeroPattern = "%:0|senior";

        $query = GoogleSheetData::where(function ($q) use ($authUser, $userPattern, $zeroPattern) {
            $q->where('created_by', $authUser->id . '|senior')
                ->orWhere('created_by', '0|senior')
                ->orWhere('created_by', 'LIKE', $userPattern)
                ->orWhere('created_by', 'LIKE', $zeroPattern);
        });

        // Filter by selected junior
        if ($juniorUserId) {
            $query->where('created_by', 'LIKE', '%' . $juniorUserId . '|junior%');
        }

        // Search or specific row filter
        if ($rowId) {
            $query->where('id', $rowId);
        } elseif ($search && strlen($search) >= 3) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', "%{$search}%")
                    ->orWhere('Email_Address', 'LIKE', "%{$search}%")
                    ->orWhere('Phone_Number', 'LIKE', "%{$search}%");
            });
        }

        // Pagination with appended filters for AJAX navigation
        $data = $query->orderBy('Date', 'desc')->paginate(10);
        $data->appends([
            'search' => $search,
            'row_id' => $rowId,
            'junior_user' => $juniorUserId,
        ]);

        // Map forwarded_by dynamically
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $forwardedBy = '';

            if (!empty($item->created_by)) {
                $entries = explode(':', $item->created_by);
                $names = [];

                foreach ($entries as $entry) {
                    $parts = explode('|', $entry);
                    $userId = $parts[0] ?? null;
                    $role   = $parts[1] ?? 'unknown';

                    if ($userId == $authUser->id) {
                        $names[] = "SELF ({$userId}) ({$role})";
                    } elseif ($userId == 0) {
                        $names[] = "SYSTEM (0) ({$role})";
                    } else {
                        $user = \App\Models\User::find($userId);
                        $name = $user ? $user->name : 'Unknown';
                        $names[] = "{$name} ({$userId}) ({$role})";
                    }
                }

                $forwardedBy = implode(' → ', $names);
            } else {
                $forwardedBy = 'N/A';
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });

        $juniorUsers = \App\Models\User::where('role', 'junior')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'phone', 'designation']);

        // Handle AJAX request for both search and pagination
        if ($request->ajax()) {
            return view('database.partials.senior_table', compact('data'))->render();
        }

        return view('database.senior', compact('data', 'juniorUsers'));
    }


    public function seniorcandm(Request $request)
    {
        $authUser = Auth::user();
        $search = $request->input('search');
        $rowId = $request->input('row_id');

        // SUBSTRING_INDEX-based filter with second part check
        $query = GoogleSheetData::where(function ($q) use ($authUser) {
            $seniorPart = $authUser->id . '|senior';

            $q->whereRaw("SUBSTRING_INDEX(created_by, ':', 1) = ?", [$seniorPart])
                ->whereRaw("
              -- Ensure there is a second part and it ends with |senior
              LENGTH(created_by) - LENGTH(REPLACE(created_by, ':', '')) >= 1
              AND
              SUBSTRING_INDEX(SUBSTRING_INDEX(created_by, ':', 2), ':', -1) LIKE '%|senior'
          ");
        });

        if ($rowId) {
            $query->where('id', $rowId);
        } elseif ($search && strlen($search) >= 3) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', "%{$search}%")
                    ->orWhere('Email_Address', 'LIKE', "%{$search}%")
                    ->orWhere('Phone_Number', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy('Date', 'desc')->paginate(10);

        // Map forwarded_by dynamically for multiple creators
        $data->getCollection()->transform(function ($item) use ($authUser) {

            $forwardedBy = '';

            if (!empty($item->created_by)) {
                // Split by ':' to handle multiple forwarded entries
                $entries = explode(':', $item->created_by);

                $names = [];
                foreach ($entries as $entry) {
                    $parts = explode('|', $entry);
                    $userId = $parts[0] ?? null;
                    $role   = $parts[1] ?? 'unknown';

                    if ($userId == $authUser->id) {
                        $names[] = "SELF ({$userId}) ({$role})";
                    } elseif ($userId == 0) {
                        $names[] = "SYSTEM (0) ({$role})";
                    } else {
                        $user = \App\Models\User::find($userId);
                        $name = $user ? $user->name : 'Unknown';
                        $names[] = "{$name} ({$userId}) ({$role})";
                    }
                }

                // Join all names for forwarded chain
                $forwardedBy = implode(' → ', $names);
            } else {
                $forwardedBy = 'N/A';
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });


        if ($request->ajax()) {
            return view('database.partials.senior_table', compact('data'))->render();
        }

        return view('database.seniorcandm', compact('data'));
    }


    public function seniorpaid(Request $request)
    {
        $authUser = Auth::user();
        $search = $request->input('search');
        $rowId = $request->input('row_id');

        $query = GoogleSheetData::where(function ($q) {
            // Removed user_id|senior check
            // Only keep the accountant part filter
            $q->where(function ($q2) {
                $q2->whereRaw("created_by = '0|accountant'")
                    ->orWhereRaw("created_by LIKE '0|accountant:%'")
                    ->orWhereRaw("created_by LIKE '%:0|accountant'")
                    ->orWhereRaw("created_by LIKE '%:0|accountant:%'");
            });
        });

        if ($rowId) {
            $query->where('id', $rowId);
        } elseif ($search && strlen($search) >= 3) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', "%{$search}%")
                    ->orWhere('Email_Address', 'LIKE', "%{$search}%")
                    ->orWhere('Phone_Number', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy('Date', 'desc')->paginate(10);

        $data->getCollection()->transform(function ($item) use ($authUser) {

            $forwardedBy = '';

            if (!empty($item->created_by)) {
                // Split by ':' to handle multiple forwarded entries
                $entries = explode(':', $item->created_by);

                $names = [];
                foreach ($entries as $entry) {
                    $parts = explode('|', $entry);
                    $userId = $parts[0] ?? null;
                    $role   = $parts[1] ?? 'unknown';

                    if ($userId == $authUser->id) {
                        $names[] = "SELF ({$userId}) ({$role})";
                    } elseif ($userId == 0) {
                        $names[] = "SYSTEM (0) ({$role})";
                    } else {
                        $user = \App\Models\User::find($userId);
                        $name = $user ? $user->name : 'Unknown';
                        $names[] = "{$name} ({$userId}) ({$role})";
                    }
                }

                // Join all names for forwarded chain
                $forwardedBy = implode(' → ', $names);
            } else {
                $forwardedBy = 'N/A';
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });


        if (request()->ajax()) {
            return view('database.partials.senior_table', compact('data'))->render();
        }

        return view('database.seniorpaid', compact('data'));
    }


    // -----------------------------
    // AJAX Search Suggestions
    // -----------------------------
    public function seniorSuggestions(Request $request)
    {
        $authUser = Auth::user();
        $query = $request->input('query');

        $results = [];

        if ($query && strlen($query) >= 3) {
            $results = GoogleSheetData::where(function ($q) use ($authUser) {
                $userPattern = "%:" . $authUser->id . "|senior";
                $zeroPattern = "%:0|senior";

                $q->where('created_by', $authUser->id . '|senior')
                    ->orWhere('created_by', '0|senior')
                    ->orWhere('created_by', 'LIKE', $userPattern)
                    ->orWhere('created_by', 'LIKE', $zeroPattern);
            })
                ->where(function ($q) use ($query) {
                    $q->where('Name', 'LIKE', "%{$query}%")
                        ->orWhere('Email_Address', 'LIKE', "%{$query}%")
                        ->orWhere('Phone_Number', 'LIKE', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'Name', 'Email_Address', 'Phone_Number']);
        }

        return response()->json($results);
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

        // --- Extract Email & Phone for uniqueness check ---
        $email = $rowData['Email Address'] ?? $row->Email_Address;
        $phone = $rowData['Phone Number'] ?? $row->Phone_Number;
        $name  = $rowData['Name'] ?? $row->Name;
        $date  = $rowData['Date'] ?? $row->Date;

        if (empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Name is required.'
            ]);
        }

        if (empty($date)) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.'
            ]);
        }
        // Check for duplicate Email (ignore current record)
        if (!empty($email)) {
            $emailExists = GoogleSheetData::where('Email_Address', $email)
                ->where('id', '!=', $id)
                ->exists();

            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exists in records.'
                ]);
            }
        }

        // Check for duplicate Phone (ignore current record)
        if (!empty($phone)) {
            $phoneExists = GoogleSheetData::where('Phone_Number', $phone)
                ->where('id', '!=', $id)
                ->exists();

            if ($phoneExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already exists in records.'
                ]);
            }
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

        // --- Prepare update data with null defaults for empty fields ---
        $updateData = [
            'Date' => !empty($rowData['Date']) ? $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $email, // keep original email
            'Phone_Number' => $phone,  // keep original phone
            'Location' => $rowData['Location'] ?? null,
            'Remark' => $rowData['Remark'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => !empty($rowData['Graduation Date']) ? $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) && $rowData['Amount'] !== '' ? $this->parseAmount($rowData['Amount']) : 469, // ✅ default 469
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
                $authUser = Auth::user();

                // Replace "0|senior" with "auth_id|senior:0|accountant"
                if (preg_match('/0\|senior$/', $updateData['created_by'])) {
                    $updateData['created_by'] = preg_replace(
                        '/0\|senior$/',
                        $authUser->id . '|senior:0|accountant',
                        $updateData['created_by']
                    );
                }

                // Ensure ":0|accountant" exists at the end if missing
                if (strpos($updateData['created_by'], ':0|accountant') === false) {
                    $updateData['created_by'] .= ':0|accountant';
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

                // Replace "0|senior" with actual senior ID (only if it ends with 0|senior)
                if (preg_match('/0\|senior$/', $updateData['created_by'])) {
                    $updateData['created_by'] = preg_replace(
                        '/0\|senior$/',
                        $id . '|senior',
                        $updateData['created_by']
                    );
                }
            }
        }

        foreach ($updateData as $key => $value) {
            if ($value === '' && !in_array($key, ['Email_Address', 'Name', 'Date', 'Amount'])) {
                $updateData[$key] = null;
            }
        }

        try {
            $row->update($updateData);
            $user = Auth::user();
            $mailMessage = 'No email sent.';
            $name = $rowData['Name'] ?? null;
            $amount = isset($rowData['Amount']) ? $this->parseAmount($rowData['Amount']) : $row->Amount;

            // --- Send email if Exe_Remarks is "Called & Mailed" ---
            if (isset($rowData['Exe Remarks']) && $rowData['Exe Remarks'] === 'Called & Mailed' && !empty($email)) {
                try {
                    $smtp = SmtpSetting::where('user_id', $user->id)->first();
                    if (!$smtp) {
                        return response()->json([
                            'message' => 'No SMTP settings found.'
                        ]);
                    } else {
                        // Configure mailer dynamically (same as test() method)
                        config([
                            'mail.mailers.smtp.transport' => $smtp->mailer,
                            'mail.mailers.smtp.host' => $smtp->host,
                            'mail.mailers.smtp.port' => $smtp->port,
                            'mail.mailers.smtp.username' => $smtp->username,
                            'mail.mailers.smtp.password' => decrypt($smtp->password),
                            'mail.mailers.smtp.encryption' => $smtp->encryption,
                            'mail.from.address' => $smtp->from_address,
                            'mail.from.name' => $smtp->from_name,
                        ]);

                        // --- Fetch Email Template from Database ---
                        $template = EmailTemplate::where('name', 'Called_Mailed')->first();

                        if ($template) {
                            $subject = $template->subject;
                            $messageBody = $template->body;
                        } else {
                            // Fallback if template not found
                            $subject = "Unlock Career Stability with Fortune 500 Projects !";
                            $messageBody =
                                "Hi {$name},\n\n" .
                                "I hope this message finds you well.\n\n" .
                                "My name is {$smtp->from_name}, and I’m part of the Talent Acquisition Team at Synergie Systems INC., a respected workforce development and project management firm based in Delaware. We partner with some of the most renowned Fortune 500 companies across the U.S., delivering not just staffing solutions but long-term career success.\n\n" .
                                "After reviewing your profile, I believe you could be a strong fit for several exciting opportunities we currently have available. And more importantly, I believe we can offer you not just a job, but a career pathway built on stability, support, and growth.\n\n" .
                                "What Makes Synergie Different?\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "At Synergie, we understand that a fulfilling career is built on trust, purpose, and progress. That's why we go beyond recruitment—we invest in you. Our commitment is simple: to help you grow, thrive, and achieve your highest potential.\n\n" .
                                "Here’s what you can expect when you join our community:\n\n" .
                                "                  - Direct Project Placements with Fortune 500 and Tier 1 clients\n" .
                                "                  - Full-time employment with Synergie—never just a short-term contract\n" .
                                "                  - Real-world project experience with today’s most in-demand tools and technologies\n" .
                                "                  - Dedicated support from day one: resume branding, interview prep, and onboarding guidance\n" .
                                "                  - Zero Bond Policy—because your freedom and career choices matter\n" .
                                "                  - Support for OPT, CPT, STEM OPT, H1B & Green Card sponsorships\n\n" .
                                "More Than a Paycheck — A Path to Prosperity\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "We believe that when you bring value, you deserve to be valued. That’s why we offer a transparent, competitive compensation structure designed to reward your dedication and drive.\n\n" .
                                "                  - Full-Time Roles: \$40–\$50/hr\n" .
                                "                  - Part-Time Roles: \$15–\$25/hr\n" .
                                "                  - Paid Internships available\n" .
                                "                  - 15% Salary Raise every 6 months based on performance\n" .
                                "                  - 12 Days Paid Vacation annually\n" .
                                "                  - Relocation Assistance for client deployments\n\n" .
                                "Comprehensive Benefits That Put You First\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "At Synergie, we care for your career—and your well-being. We provide:\n\n" .
                                "                  - Health, Dental & Vision Insurance\n" .
                                "                  - Short- & Long-Term Disability Insurance\n" .
                                "                  - Life Insurance & 401(k) Retirement Plan\n" .
                                "                  - Legal & Immigration Support\n" .
                                "                  - Tax Assistance & Transparent Payroll\n" .
                                "                  - Workers’ Compensation—your safety is our priority\n\n" .
                                "Support Tailored for International Talent\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "We take pride in guiding hundreds of F1/OPT/CPT/STEM OPT professionals every year toward long-term success in the U.S.:\n\n" .
                                "                  - Offer Letters, Client Confirmations & Employer Letters\n" .
                                "                  - Full STEM Extension & OPT/CPT Support\n" .
                                "                  - H1B Sponsorship after project onboarding\n" .
                                "                  - Relocation & Immigration Documentation\n" .
                                "                  - Ongoing Green Card Processing Assistance\n\n" .
                                "Not Quite Job-Ready? We’ll Bridge That Gap\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "Sometimes, all it takes is one last push to unlock your dream opportunity. That’s why we offer a 4-week industry-focused workshop, designed by experts with over a decade of experience to prepare you for real-world success.\n\n" .
                                "What You’ll Gain:\n\n" .
                                "                  - Live Zoom sessions & recorded expert sessions\n" .
                                "                  - Real-time project simulations & hands-on assignments\n" .
                                "                  - One-on-one resume branding & mock interviews\n" .
                                "                  - Global Certificate of Completion & recruiter access\n" .
                                "                  - 100% Fee Refund with your first project paycheck (Only \${$amount}—one-time, fully refundable)\n\n" .
                                "Let’s Take the First Step Together\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "If you’re seeking more than just another role—if you’re looking for a career that recognizes your potential, offers true support, and opens doors to the future you deserve—then Synergie is here for you.\n\n" .
                                "This is your opportunity to move forward with confidence, backed by a team that believes in you and works tirelessly to help you succeed.\n\n" .
                                "Please feel free to reply to this email or reach me directly over the phone if you’d like to learn more or take the next step.\n\n" .
                                "Wishing you success in every path you choose—but hoping we’ll have the honor of being part of your journey.\n\n" .
                                "Visit Our Website: https://www.synergiesystems.com/";
                        }

                        // --- Send Email (No Template Logic Changed) ---
                        Mail::raw($messageBody, function ($message) use ($email, $subject, $smtp) {
                            $message->from($smtp->from_address, $smtp->from_name)
                                ->to($email)
                                ->subject($subject);
                        });

                        $mailMessage = "Email sent successfully to {$email}!";
                    }
                } catch (\Exception $e) {
                    $mailMessage = 'Failed to send email: ' . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($row->resume) ? true : false,
                'mail_message' => $mailMessage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fill Full Detail to Save.'
            ]);
        }
    }


    public function seniorstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // --- Extract Email & Phone for uniqueness check ---
        $email = $rowData['Email Address'] ?? null;
        $phone = $rowData['Phone Number'] ?? null;
        $name  = $rowData['Name'] ?? null;
        $date  = $rowData['Date'] ?? null;

        // --- Check required fields ---
        if (empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Name is required.'
            ]);
        }

        if (empty($date)) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.'
            ]);
        }

        // Check for duplicate Email
        if (!empty($email)) {
            $emailExists = GoogleSheetData::where('Email_Address', $email)->exists();

            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exists in records.'
                ]);
            }
        }

        // Check for duplicate Phone
        if (!empty($phone)) {
            $phoneExists = GoogleSheetData::where('Phone_Number', $phone)->exists();

            if ($phoneExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already exists in records.'
                ]);
            }
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
            'Remark' => 'Remark',
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
        $name = null;
        $amount = null;

        // Assign values safely, save null for empty non-number/email fields
        foreach ($columnMap as $frontendKey => $dbColumn) {
            $val = $rowData[$frontendKey] ?? null;

            if (in_array($dbColumn, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($dbColumn === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
                $amount = $val;
            }

            if ($dbColumn === 'Name') {
                $name = $val;
            }

            if ($dbColumn === 'Exe_Remarks') {
                $exeRemarksValue = $val;
            }

            // Save null for empty fields, including Amount
            if (empty($val) && !in_array($dbColumn, ['Email_Address', 'Phone_Number'])) {
                $val = null;
            }

            $record->$dbColumn = $val;
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
            $saveMessage = 'Record saved successfully.';
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fill Full Detail to Save.'
            ]);
        }

        // --- Email logic ---
        $mailMessage = 'No email sent.';
        // --- Send Email if Exe_Remarks is "Called & Mailed" ---
        if ($exeRemarksValue === 'Called & Mailed' && !empty($email)) {
            try {
                $smtp = SmtpSetting::where('user_id', $user->id)->first();
                if (!$smtp) {
                    return response()->json([
                        'message' => 'No SMTP settings found.'
                    ]);
                } else {
                    // Configure mailer dynamically (same as test() method)
                    config([
                        'mail.mailers.smtp.transport' => $smtp->mailer,
                        'mail.mailers.smtp.host' => $smtp->host,
                        'mail.mailers.smtp.port' => $smtp->port,
                        'mail.mailers.smtp.username' => $smtp->username,
                        'mail.mailers.smtp.password' => decrypt($smtp->password),
                        'mail.mailers.smtp.encryption' => $smtp->encryption,
                        'mail.from.address' => $smtp->from_address,
                        'mail.from.name' => $smtp->from_name,
                    ]);

                    // --- Fetch Email Template from Database ---
                    $template = EmailTemplate::where('name', 'Called_Mailed')->first();

                    if ($template) {
                        $subject = $template->subject;
                        $messageBody = $template->body;
                    } else {
                        // Fallback if template not found
                        $subject = "Unlock Career Stability with Fortune 500 Projects !";
                        $messageBody =
                            "Hi {$name},\n\n" .
                            "I hope this message finds you well.\n\n" .
                            "My name is {$smtp->from_name}, and I’m part of the Talent Acquisition Team at Synergie Systems INC., a respected workforce development and project management firm based in Delaware. We partner with some of the most renowned Fortune 500 companies across the U.S., delivering not just staffing solutions but long-term career success.\n\n" .
                            "After reviewing your profile, I believe you could be a strong fit for several exciting opportunities we currently have available. And more importantly, I believe we can offer you not just a job, but a career pathway built on stability, support, and growth.\n\n" .
                            "What Makes Synergie Different?\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "At Synergie, we understand that a fulfilling career is built on trust, purpose, and progress. That's why we go beyond recruitment—we invest in you. Our commitment is simple: to help you grow, thrive, and achieve your highest potential.\n\n" .
                            "Here’s what you can expect when you join our community:\n\n" .
                            "                  - Direct Project Placements with Fortune 500 and Tier 1 clients\n" .
                            "                  - Full-time employment with Synergie—never just a short-term contract\n" .
                            "                  - Real-world project experience with today’s most in-demand tools and technologies\n" .
                            "                  - Dedicated support from day one: resume branding, interview prep, and onboarding guidance\n" .
                            "                  - Zero Bond Policy—because your freedom and career choices matter\n" .
                            "                  - Support for OPT, CPT, STEM OPT, H1B & Green Card sponsorships\n\n" .
                            "More Than a Paycheck — A Path to Prosperity\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "We believe that when you bring value, you deserve to be valued. That’s why we offer a transparent, competitive compensation structure designed to reward your dedication and drive.\n\n" .
                            "                  - Full-Time Roles: \$40–\$50/hr\n" .
                            "                  - Part-Time Roles: \$15–\$25/hr\n" .
                            "                  - Paid Internships available\n" .
                            "                  - 15% Salary Raise every 6 months based on performance\n" .
                            "                  - 12 Days Paid Vacation annually\n" .
                            "                  - Relocation Assistance for client deployments\n\n" .
                            "Comprehensive Benefits That Put You First\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "At Synergie, we care for your career—and your well-being. We provide:\n\n" .
                            "                  - Health, Dental & Vision Insurance\n" .
                            "                  - Short- & Long-Term Disability Insurance\n" .
                            "                  - Life Insurance & 401(k) Retirement Plan\n" .
                            "                  - Legal & Immigration Support\n" .
                            "                  - Tax Assistance & Transparent Payroll\n" .
                            "                  - Workers’ Compensation—your safety is our priority\n\n" .
                            "Support Tailored for International Talent\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "We take pride in guiding hundreds of F1/OPT/CPT/STEM OPT professionals every year toward long-term success in the U.S.:\n\n" .
                            "                  - Offer Letters, Client Confirmations & Employer Letters\n" .
                            "                  - Full STEM Extension & OPT/CPT Support\n" .
                            "                  - H1B Sponsorship after project onboarding\n" .
                            "                  - Relocation & Immigration Documentation\n" .
                            "                  - Ongoing Green Card Processing Assistance\n\n" .
                            "Not Quite Job-Ready? We’ll Bridge That Gap\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "Sometimes, all it takes is one last push to unlock your dream opportunity. That’s why we offer a 4-week industry-focused workshop, designed by experts with over a decade of experience to prepare you for real-world success.\n\n" .
                            "What You’ll Gain:\n\n" .
                            "                  - Live Zoom sessions & recorded expert sessions\n" .
                            "                  - Real-time project simulations & hands-on assignments\n" .
                            "                  - One-on-one resume branding & mock interviews\n" .
                            "                  - Global Certificate of Completion & recruiter access\n" .
                            "                  - 100% Fee Refund with your first project paycheck (Only \${$amount}—one-time, fully refundable)\n\n" .
                            "Let’s Take the First Step Together\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "If you’re seeking more than just another role—if you’re looking for a career that recognizes your potential, offers true support, and opens doors to the future you deserve—then Synergie is here for you.\n\n" .
                            "This is your opportunity to move forward with confidence, backed by a team that believes in you and works tirelessly to help you succeed.\n\n" .
                            "Please feel free to reply to this email or reach me directly over the phone if you’d like to learn more or take the next step.\n\n" .
                            "Wishing you success in every path you choose—but hoping we’ll have the honor of being part of your journey.\n\n" .
                            "Visit Our Website: https://www.synergiesystems.com/";
                    }

                    // --- Send Email (No Template Logic Changed) ---
                    Mail::raw($messageBody, function ($message) use ($email, $subject, $smtp) {
                        $message->from($smtp->from_address, $smtp->from_name)
                            ->to($email)
                            ->subject($subject);
                    });

                    $mailMessage = "Email sent successfully to {$email}!";
                }
            } catch (\Exception $e) {
                $mailMessage = 'Failed to send email: ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'resume_path' => !empty($record->resume) ? true : false,
            'mail_message' => $mailMessage,
            'save_message' => $saveMessage,
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
            ->orderBy('Date', 'desc') // ✅ sort by Date descending (latest first)
            ->paginate(10);

        return view('database.junior', compact('data'));
    }


    public function juniorcandm()
    {
        $authUser = Auth::user();
        $juniorPart = $authUser->id . '|junior';

        $data = GoogleSheetData::where(function ($q) use ($juniorPart) {
            // Check first segment is junior
            $q->whereRaw("SUBSTRING_INDEX(created_by, ':', 1) = ?", [$juniorPart]);
        })
            ->where(function ($q) {
                // Check second segment is senior (any ID or 0)
                $q->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(created_by, ':', 2), ':', -1) LIKE '%|senior'");
            })
            ->orderBy('Date', 'desc') // ✅ sort by Date descending
            ->paginate(10);

        return view('database.juniorcandm', compact('data'));
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

        // --- Extract Email & Phone for uniqueness check ---
        $email = $rowData['Email Address'] ?? $row->Email_Address;
        $phone = $rowData['Phone Number'] ?? $row->Phone_Number;
        $name  = $rowData['Name'] ?? $row->Name;
        $date  = $rowData['Date'] ?? $row->Date;

        if (empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Name is required.'
            ]);
        }

        if (empty($date)) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.'
            ]);
        }
        if (!empty($email)) {
            $emailExists = GoogleSheetData::where('Email_Address', $email)
                ->where('id', '!=', $id)
                ->exists();

            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exists in records.'
                ]);
            }
        }

        // Check for duplicate Phone (ignore current record)
        if (!empty($phone)) {
            $phoneExists = GoogleSheetData::where('Phone_Number', $phone)
                ->where('id', '!=', $id)
                ->exists();

            if ($phoneExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already exists in records.'
                ]);
            }
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

        // --- Prepare update data with null for empty fields ---
        $updateData = [
            'Date' => !empty($rowData['Date']) ? $this->parseDate($rowData['Date']) : null,
            'Name' => $rowData['Name'] ?? null,
            'Email_Address' => $email, // keep provided email
            'Phone_Number' => $phone,  // keep provided phone
            'Location' => $rowData['Location'] ?? null,
            'Remark' => $rowData['Remark'] ?? null,
            'Relocation' => $rowData['Relocation'] ?? null,
            'Graduation_Date' => !empty($rowData['Graduation Date']) ? $this->parseDate($rowData['Graduation Date']) : null,
            'Immigration' => $rowData['Immigration'] ?? null,
            'Course' => $rowData['Course'] ?? null,
            'Amount' => isset($rowData['Amount']) && $rowData['Amount'] !== '' ? $this->parseAmount($rowData['Amount']) : 469, // ✅ default 469
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

        foreach ($updateData as $key => $value) {
            if ($value === '' && !in_array($key, ['Email_Address', 'Remark', 'Name', 'Amount'])) {
                $updateData[$key] = null;
            }
        }

        try {
            $row->update($updateData);
            $user = Auth::user();
            $mailMessage = 'No email sent.';
            $name = $rowData['Name'] ?? null;
            $amount = isset($rowData['Amount']) ? $this->parseAmount($rowData['Amount']) : $row->Amount;

            // --- Send email if Exe_Remarks is "Called & Mailed" ---
            if (isset($rowData['Exe Remarks']) && $rowData['Exe Remarks'] === 'Called & Mailed' && !empty($email)) {
                try {
                    $smtp = SmtpSetting::where('user_id', $user->id)->first();
                    if (!$smtp) {
                        return response()->json([
                            'message' => 'No SMTP settings found.'
                        ]);
                    } else {
                        // Configure mailer dynamically (same as test() method)
                        config([
                            'mail.mailers.smtp.transport' => $smtp->mailer,
                            'mail.mailers.smtp.host' => $smtp->host,
                            'mail.mailers.smtp.port' => $smtp->port,
                            'mail.mailers.smtp.username' => $smtp->username,
                            'mail.mailers.smtp.password' => decrypt($smtp->password),
                            'mail.mailers.smtp.encryption' => $smtp->encryption,
                            'mail.from.address' => $smtp->from_address,
                            'mail.from.name' => $smtp->from_name,
                        ]);

                        // --- Fetch Email Template from Database ---
                        $template = EmailTemplate::where('name', 'Called_Mailed')->first();

                        if ($template) {
                            $subject = $template->subject;
                            $messageBody = $template->body;
                        } else {
                            // Fallback if template not found
                            $subject = "Unlock Career Stability with Fortune 500 Projects !";
                            $messageBody =
                                "Hi {$name},\n\n" .
                                "I hope this message finds you well.\n\n" .
                                "My name is {$smtp->from_name}, and I’m part of the Talent Acquisition Team at Synergie Systems INC., a respected workforce development and project management firm based in Delaware. We partner with some of the most renowned Fortune 500 companies across the U.S., delivering not just staffing solutions but long-term career success.\n\n" .
                                "After reviewing your profile, I believe you could be a strong fit for several exciting opportunities we currently have available. And more importantly, I believe we can offer you not just a job, but a career pathway built on stability, support, and growth.\n\n" .
                                "What Makes Synergie Different?\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "At Synergie, we understand that a fulfilling career is built on trust, purpose, and progress. That's why we go beyond recruitment—we invest in you. Our commitment is simple: to help you grow, thrive, and achieve your highest potential.\n\n" .
                                "Here’s what you can expect when you join our community:\n\n" .
                                "                  - Direct Project Placements with Fortune 500 and Tier 1 clients\n" .
                                "                  - Full-time employment with Synergie—never just a short-term contract\n" .
                                "                  - Real-world project experience with today’s most in-demand tools and technologies\n" .
                                "                  - Dedicated support from day one: resume branding, interview prep, and onboarding guidance\n" .
                                "                  - Zero Bond Policy—because your freedom and career choices matter\n" .
                                "                  - Support for OPT, CPT, STEM OPT, H1B & Green Card sponsorships\n\n" .
                                "More Than a Paycheck — A Path to Prosperity\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "We believe that when you bring value, you deserve to be valued. That’s why we offer a transparent, competitive compensation structure designed to reward your dedication and drive.\n\n" .
                                "                  - Full-Time Roles: \$40–\$50/hr\n" .
                                "                  - Part-Time Roles: \$15–\$25/hr\n" .
                                "                  - Paid Internships available\n" .
                                "                  - 15% Salary Raise every 6 months based on performance\n" .
                                "                  - 12 Days Paid Vacation annually\n" .
                                "                  - Relocation Assistance for client deployments\n\n" .
                                "Comprehensive Benefits That Put You First\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "At Synergie, we care for your career—and your well-being. We provide:\n\n" .
                                "                  - Health, Dental & Vision Insurance\n" .
                                "                  - Short- & Long-Term Disability Insurance\n" .
                                "                  - Life Insurance & 401(k) Retirement Plan\n" .
                                "                  - Legal & Immigration Support\n" .
                                "                  - Tax Assistance & Transparent Payroll\n" .
                                "                  - Workers’ Compensation—your safety is our priority\n\n" .
                                "Support Tailored for International Talent\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "We take pride in guiding hundreds of F1/OPT/CPT/STEM OPT professionals every year toward long-term success in the U.S.:\n\n" .
                                "                  - Offer Letters, Client Confirmations & Employer Letters\n" .
                                "                  - Full STEM Extension & OPT/CPT Support\n" .
                                "                  - H1B Sponsorship after project onboarding\n" .
                                "                  - Relocation & Immigration Documentation\n" .
                                "                  - Ongoing Green Card Processing Assistance\n\n" .
                                "Not Quite Job-Ready? We’ll Bridge That Gap\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "Sometimes, all it takes is one last push to unlock your dream opportunity. That’s why we offer a 4-week industry-focused workshop, designed by experts with over a decade of experience to prepare you for real-world success.\n\n" .
                                "What You’ll Gain:\n\n" .
                                "                  - Live Zoom sessions & recorded expert sessions\n" .
                                "                  - Real-time project simulations & hands-on assignments\n" .
                                "                  - One-on-one resume branding & mock interviews\n" .
                                "                  - Global Certificate of Completion & recruiter access\n" .
                                "                  - 100% Fee Refund with your first project paycheck (Only \${$amount}—one-time, fully refundable)\n\n" .
                                "Let’s Take the First Step Together\n" .
                                "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                                "If you’re seeking more than just another role—if you’re looking for a career that recognizes your potential, offers true support, and opens doors to the future you deserve—then Synergie is here for you.\n\n" .
                                "This is your opportunity to move forward with confidence, backed by a team that believes in you and works tirelessly to help you succeed.\n\n" .
                                "Please feel free to reply to this email or reach me directly over the phone if you’d like to learn more or take the next step.\n\n" .
                                "Wishing you success in every path you choose—but hoping we’ll have the honor of being part of your journey.\n\n" .
                                "Visit Our Website: https://www.synergiesystems.com/";
                        }

                        // --- Send Email (No Template Logic Changed) ---
                        Mail::raw($messageBody, function ($message) use ($email, $subject, $smtp) {
                            $message->from($smtp->from_address, $smtp->from_name)
                                ->to($email)
                                ->subject($subject);
                        });

                        $mailMessage = "Email sent successfully to {$email}!";
                    }
                } catch (\Exception $e) {
                    $mailMessage = 'Failed to send email: ' . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Row updated successfully',
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'resume_path' => !empty($row->resume) ? true : false,
                'mail_message' => $mailMessage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fill Full Detail to Save.'
            ]);
        }
    }

    public function juniorstore(Request $request)
    {
        $rowData = json_decode($request->input('data'), true);

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        // --- Extract Email & Phone for uniqueness check ---
        $email = $rowData['Email Address'] ?? null;
        $phone = $rowData['Phone Number'] ?? null;
        $name = $rowData['Name'] ?? null;
        $date = $rowData['Date'] ?? null;

        // --- Check required fields ---
        if (empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Name is required.'
            ]);
        }

        if (empty($date)) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.'
            ]);
        }

        // Check for duplicate Email
        if (!empty($email)) {
            $emailExists = GoogleSheetData::where('Email_Address', $email)->exists();
            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exists in records.'
                ]);
            }
        }

        // Check for duplicate Phone
        if (!empty($phone)) {
            $phoneExists = GoogleSheetData::where('Phone_Number', $phone)->exists();
            if ($phoneExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already exists in records.'
                ]);
            }
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
            'Remark' => 'Remark',
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
        $name = null;
        $amount = null;

        // Assign values safely, save null for empty non-number/email fields
        foreach ($columnMap as $frontendKey => $dbColumn) {
            $val = $rowData[$frontendKey] ?? null;

            if (in_array($dbColumn, ['Date', 'Graduation_Date']) && !empty($val)) {
                $val = $this->parseDate($val);
            }

            if ($dbColumn === 'Amount' && !empty($val)) {
                $val = $this->parseAmount($val);
                $amount = $val;
            }

            if ($dbColumn === 'Name') {
                $name = $val;
            }

            if ($dbColumn === 'Exe_Remarks') {
                $exeRemarksValue = $val;
            }

            // Save null for empty fields, including Amount
            if (empty($val) && !in_array($dbColumn, ['Email_Address', 'Phone_Number'])) {
                $val = null;
            }

            $record->$dbColumn = $val;
        }

        // Set created_by conditionally
        if ($exeRemarksValue === 'Called & Mailed') {
            $record->created_by = $user->id . '|junior:0|senior';
        } else {
            $record->created_by = $user->id . '|junior';
        }

        if (is_null($record->Amount)) {
            $record->Amount = 469; // ✅ default amount
            $amount = 469;         // ensures email shows correct amount
        }


        // Handle resume file upload
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            if ($file->getMimeType() !== 'application/pdf') {
                return response()->json(['success' => false, 'message' => 'Only PDF files are allowed']);
            }

            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                $filePath = $file->storeAs('resumes', $newName, 'public');
                $record->resume = $filePath;
            } catch (\Exception $e) {
                $record->resume = null;
            }
        }

        try {
            $record->save();
            $saveMessage = 'Record saved successfully.';
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fill Full Detail to Save.'
            ]);
        }

        $mailMessage = 'No email sent.';

        // --- Send Email if Exe_Remarks is "Called & Mailed" ---
        if ($exeRemarksValue === 'Called & Mailed' && !empty($email)) {
            try {
                $smtp = SmtpSetting::where('user_id', $user->id)->first();
                if (!$smtp) {
                    return response()->json([
                        'message' => 'No SMTP settings found.'
                    ]);
                } else {
                    // Configure mailer dynamically (same as test() method)
                    config([
                        'mail.mailers.smtp.transport' => $smtp->mailer,
                        'mail.mailers.smtp.host' => $smtp->host,
                        'mail.mailers.smtp.port' => $smtp->port,
                        'mail.mailers.smtp.username' => $smtp->username,
                        'mail.mailers.smtp.password' => decrypt($smtp->password),
                        'mail.mailers.smtp.encryption' => $smtp->encryption,
                        'mail.from.address' => $smtp->from_address,
                        'mail.from.name' => $smtp->from_name,
                    ]);

                    // --- Fetch Email Template from Database ---
                    $template = EmailTemplate::where('name', 'Called_Mailed')->first();

                    if ($template) {
                        $subject = $template->subject;
                        $messageBody = $template->body;
                    } else {
                        // Fallback if template not found
                        $subject = "Unlock Career Stability with Fortune 500 Projects !";
                        $messageBody =
                            "Hi {$name},\n\n" .
                            "I hope this message finds you well.\n\n" .
                            "My name is {$smtp->from_name}, and I’m part of the Talent Acquisition Team at Synergie Systems INC., a respected workforce development and project management firm based in Delaware. We partner with some of the most renowned Fortune 500 companies across the U.S., delivering not just staffing solutions but long-term career success.\n\n" .
                            "After reviewing your profile, I believe you could be a strong fit for several exciting opportunities we currently have available. And more importantly, I believe we can offer you not just a job, but a career pathway built on stability, support, and growth.\n\n" .
                            "What Makes Synergie Different?\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "At Synergie, we understand that a fulfilling career is built on trust, purpose, and progress. That's why we go beyond recruitment—we invest in you. Our commitment is simple: to help you grow, thrive, and achieve your highest potential.\n\n" .
                            "Here’s what you can expect when you join our community:\n\n" .
                            "                  - Direct Project Placements with Fortune 500 and Tier 1 clients\n" .
                            "                  - Full-time employment with Synergie—never just a short-term contract\n" .
                            "                  - Real-world project experience with today’s most in-demand tools and technologies\n" .
                            "                  - Dedicated support from day one: resume branding, interview prep, and onboarding guidance\n" .
                            "                  - Zero Bond Policy—because your freedom and career choices matter\n" .
                            "                  - Support for OPT, CPT, STEM OPT, H1B & Green Card sponsorships\n\n" .
                            "More Than a Paycheck — A Path to Prosperity\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "We believe that when you bring value, you deserve to be valued. That’s why we offer a transparent, competitive compensation structure designed to reward your dedication and drive.\n\n" .
                            "                  - Full-Time Roles: \$40–\$50/hr\n" .
                            "                  - Part-Time Roles: \$15–\$25/hr\n" .
                            "                  - Paid Internships available\n" .
                            "                  - 15% Salary Raise every 6 months based on performance\n" .
                            "                  - 12 Days Paid Vacation annually\n" .
                            "                  - Relocation Assistance for client deployments\n\n" .
                            "Comprehensive Benefits That Put You First\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "At Synergie, we care for your career—and your well-being. We provide:\n\n" .
                            "                  - Health, Dental & Vision Insurance\n" .
                            "                  - Short- & Long-Term Disability Insurance\n" .
                            "                  - Life Insurance & 401(k) Retirement Plan\n" .
                            "                  - Legal & Immigration Support\n" .
                            "                  - Tax Assistance & Transparent Payroll\n" .
                            "                  - Workers’ Compensation—your safety is our priority\n\n" .
                            "Support Tailored for International Talent\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "We take pride in guiding hundreds of F1/OPT/CPT/STEM OPT professionals every year toward long-term success in the U.S.:\n\n" .
                            "                  - Offer Letters, Client Confirmations & Employer Letters\n" .
                            "                  - Full STEM Extension & OPT/CPT Support\n" .
                            "                  - H1B Sponsorship after project onboarding\n" .
                            "                  - Relocation & Immigration Documentation\n" .
                            "                  - Ongoing Green Card Processing Assistance\n\n" .
                            "Not Quite Job-Ready? We’ll Bridge That Gap\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "Sometimes, all it takes is one last push to unlock your dream opportunity. That’s why we offer a 4-week industry-focused workshop, designed by experts with over a decade of experience to prepare you for real-world success.\n\n" .
                            "What You’ll Gain:\n\n" .
                            "                  - Live Zoom sessions & recorded expert sessions\n" .
                            "                  - Real-time project simulations & hands-on assignments\n" .
                            "                  - One-on-one resume branding & mock interviews\n" .
                            "                  - Global Certificate of Completion & recruiter access\n" .
                            "                  - 100% Fee Refund with your first project paycheck (Only \${$amount}—one-time, fully refundable)\n\n" .
                            "Let’s Take the First Step Together\n" .
                            "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n" .
                            "If you’re seeking more than just another role—if you’re looking for a career that recognizes your potential, offers true support, and opens doors to the future you deserve—then Synergie is here for you.\n\n" .
                            "This is your opportunity to move forward with confidence, backed by a team that believes in you and works tirelessly to help you succeed.\n\n" .
                            "Please feel free to reply to this email or reach me directly over the phone if you’d like to learn more or take the next step.\n\n" .
                            "Wishing you success in every path you choose—but hoping we’ll have the honor of being part of your journey.\n\n" .
                            "Visit Our Website: https://www.synergiesystems.com/";
                    }

                    // --- Send Email (No Template Logic Changed) ---
                    Mail::raw($messageBody, function ($message) use ($email, $subject, $smtp) {
                        $message->from($smtp->from_address, $smtp->from_name)
                            ->to($email)
                            ->subject($subject);
                    });

                    $mailMessage = "Email sent successfully to {$email}!";
                }
            } catch (\Exception $e) {
                $mailMessage = 'Failed to send email: ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'id' => $record->id,
            'sheet_row_number' => $record->sheet_row_number,
            'save_message' => $saveMessage,
            'mail_message' => $mailMessage,
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


    public function accountant(Request $request)
    {
        $authUser = Auth::user();
        $search = $request->input('search');
        $rowId = $request->input('row_id');
        $juniorUserId = $request->input('junior_user'); // dropdown value

        // Build patterns for LIKE match
        $userPattern = "%:" . $authUser->id . "|accountant";
        $zeroPattern = "%:0|accountant";

        $query = GoogleSheetData::where(function ($q) use ($authUser, $userPattern, $zeroPattern) {
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
            });

        // Filter by selected junior
        if ($juniorUserId) {
            $query->where('created_by', 'LIKE', '%' . $juniorUserId . '|junior%');
        }

        // Search or specific row filter
        if ($rowId) {
            $query->where('id', $rowId);
        } elseif ($search && strlen($search) >= 3) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', "%{$search}%")
                    ->orWhere('Email_Address', 'LIKE', "%{$search}%")
                    ->orWhere('Phone_Number', 'LIKE', "%{$search}%");
            });
        }

        // Pagination with appended filters for AJAX navigation
        $data = $query->orderBy('Date', 'desc')->paginate(10);
        $data->appends([
            'search' => $search,
            'row_id' => $rowId,
            'junior_user' => $juniorUserId,
        ]);

        // Map forwarded_by dynamically (multi-level like senior)
        $data->getCollection()->transform(function ($item) use ($authUser) {
            $forwardedBy = '';

            if (!empty($item->created_by)) {
                $entries = explode(':', $item->created_by);
                $names = [];

                foreach ($entries as $entry) {
                    $parts = explode('|', $entry);
                    $userId = $parts[0] ?? null;
                    $role   = $parts[1] ?? 'unknown';

                    if ($userId == $authUser->id) {
                        $names[] = "SELF ({$userId}) ({$role})";
                    } elseif ($userId == 0) {
                        $names[] = "SYSTEM (0) ({$role})";
                    } else {
                        $user = \App\Models\User::find($userId);
                        $name = $user ? $user->name : 'Unknown';
                        $names[] = "{$name} ({$userId}) ({$role})";
                    }
                }

                $forwardedBy = implode(' → ', $names);
            } else {
                $forwardedBy = 'N/A';
            }

            $item->forwarded_by = $forwardedBy;
            return $item;
        });

        // Fetch junior users list for dropdown
        $juniorUsers = \App\Models\User::where('role', 'junior')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'phone', 'designation']);

        // Handle AJAX request for both search and pagination
        if ($request->ajax()) {
            return view('database.partials.senior_table', compact('data'))->render();
        }

        return view('database.accountant', compact('data', 'juniorUsers'));
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
                'message' => 'Fill Full Detail to Save.'
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
                'message' => 'Fill Full Detail to Save.'
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
                'message' => 'Fill Full Detail to Save.'
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
                'message' => 'Fill Full Detail to Save.'
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
