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
            $rowData = json_decode($item->data, true);

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

            return [
                'id'               => $item->id,
                'sheet_row_number' => $item->sheet_row_number,
                'data'             => $rowData,
                'created_by'       => $item->created_by,
                'forwarded_by'     => $forwardedBy,
            ];
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

        $resumePath = $row->resume_path; // keep old if no new upload
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $resumePath = $file->store('resumes', 'public');
        }

        $row->update([
            'data'        => json_encode($rowData, JSON_UNESCAPED_UNICODE),
            'resume_path' => $resumePath,
            'created_by'  => Auth::id() . "|" . Auth::user()->role,
        ]);

        return response()->json([
            'success' => true,
            'row' => [
                'id'          => $row->id,
                'data'        => $rowData,
                'resume_url'  => $resumePath ? Storage::url($resumePath) : null,
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


    public function juniorpdfstore(Request $request)
    {
        // decode row data if provided
        $rowData = $request->has('rows') 
            ? json_decode($request->input('rows')[0], true) 
            : [];

        // handle resume upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // create unique filename: originalname_YYYYMMDD_His.pdf
            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}." . $extension;

            $resumePath = $file->storeAs('resumes', $newName, 'public');
        }

        // create record
        $record = GoogleSheetData::create([
            'data'   => !empty($rowData) ? json_encode($rowData) : null,
            'resume' => $resumePath ? basename($resumePath) : null,
        ]);

        return response()->json([
            'success'    => true,
            'id'         => $record->id,
            'resume_url' => $resumePath ? asset('storage/' . $resumePath) : null,
        ]);
    }

    public function juniorpdfupdate(Request $request, $id)
    {
        $record = GoogleSheetData::findOrFail($id);

        // update row data if provided
        if ($request->has('data')) {
            $data = json_decode($request->input('data'), true);
            $record->data = json_encode($data);
        }

        // update resume if uploaded
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // create unique filename
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
