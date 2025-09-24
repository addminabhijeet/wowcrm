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

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                [
                    'data'       => json_encode($rowData, JSON_UNESCAPED_UNICODE),
                    'created_by' => "{$user->id}|{$user->role}",
                ]
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.junior')->with('success', 'Data fetched successfully!');
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

        $data = GoogleSheetData::all()->filter(function ($item) use ($authUser) {
            $rowData = json_decode($item->data, true);

            if (($rowData['Exe Remarks'] ?? '') === 'Called & Mailed') {
                return true;
            }

            return $item->created_by === "{$authUser->id}|senior";
        })->map(function ($item) use ($authUser) {
            // Safely split created_by
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

            // attach forwarded_by dynamically
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

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                [
                    'data'       => json_encode($rowData, JSON_UNESCAPED_UNICODE),
                    'created_by' => "{$user->id}|{$user->role}",
                ]
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.junior')->with('success', 'Data fetched successfully!');
    }


    public function seniorupdate(Request $request, $id)
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

    public function seniorstore(Request $request)
    {
        $rowData = $request->input('rows.0'); 

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 1;
        $nextRow = $maxRow + 1;

        $user = Auth::user();

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

public function junior()
{
    $data = GoogleSheetData::all();
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
    $resumePath = $row->resume ? "resumes/" . $row->resume : null;
    if ($request->hasFile('resume')) {
        $file = $request->file('resume');
        $timestamp = now()->format('Ymd_His');
        $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;
        $resumePath = $file->storeAs('resumes', $newName, 'public');
        $row->resume = basename($resumePath);
    }

    // Map the data to individual columns
    $updateData = [
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
        'resume' => $row->resume,
        'created_by' => Auth::id() . "|" . Auth::user()->role,
    ];

    $row->update($updateData);

        return response()->json([
            'success' => true,
            'row' => [
                'id'          => $row->id,
                'data'        => $rowData,
                'resume_url'  => $row->resume ? asset('storage/resumes/' . $row->resume) : null,
            ]
        ]);
    }

    public function juniorstore(Request $request)
    {
        $rowData = $request->input('rows.0'); 

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 1;
        $nextRow = $maxRow + 1;
        $user = Auth::user();

        // Map the data to individual columns
        $mappedData = [
            'sheet_row_number' => $nextRow,
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

        $newRow = GoogleSheetData::create($mappedData);

        return response()->json([
            'success' => true,
            'rows' => [[
                'id'               => $newRow->id,
                'sheet_row_number' => $newRow->sheet_row_number,
                'data'             => $rowData,
                'created_by'       => $newRow->created_by,
                'resume_url'       => $newRow->resume ? asset('storage/resumes/' . $newRow->resume) : null,
            ]]
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