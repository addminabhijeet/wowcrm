<?php

namespace App\Http\Controllers;

use App\Models\GoogleSheetData;
use App\Models\User;
use Illuminate\Http\Request;

class CandidateDetailsController extends Controller
{
    public function associate(Request $request, $userId)
    {
        // Get candidate
        $candidate = GoogleSheetData::find($userId);
        if (!$candidate) {
            return redirect()->back()->with('error', 'Candidate not found.');
        }

        // Parse forwarded list
        $createdByRaw = $candidate->created_by ?? '';
        $parts = explode('|', $createdByRaw);
        $forwardedList = [];

        foreach ($parts as $part) {
            if (strpos($part, ':') !== false) {
                [$role, $id] = explode(':', $part);
                $forwardedList[] = [
                    'role' => $role,
                    'id'   => is_numeric($id) ? (int)$id : null
                ];
            } else {
                $forwardedList[] = [
                    'role' => null,
                    'id'   => is_numeric($part) ? (int)$part : null
                ];
            }
        }

        // Fetch users in one query
        $userIds = collect($forwardedList)->pluck('id')->filter(fn($id) => $id > 0)->unique()->toArray();
        $users = \App\Models\User::whereIn('id', $userIds)->get(['id', 'name', 'role'])->keyBy('id');

        return view('candidate.details', compact('candidate', 'forwardedList', 'users'));
    }

    public function associateservices(Request $request, $userId)
    {
        // Get candidate
        $candidate = GoogleSheetData::find($userId);
        if (!$candidate) {
            return redirect()->back()->with('error', 'Candidate not found.');
        }

        // Parse forwarded list
        $createdByRaw = $candidate->created_by ?? '';
        $parts = explode('|', $createdByRaw);
        $forwardedList = [];

        foreach ($parts as $part) {
            if (strpos($part, ':') !== false) {
                [$role, $id] = explode(':', $part);
                $forwardedList[] = [
                    'role' => $role,
                    'id'   => is_numeric($id) ? (int)$id : null
                ];
            } else {
                $forwardedList[] = [
                    'role' => null,
                    'id'   => is_numeric($part) ? (int)$part : null
                ];
            }
        }

        // Fetch users in one query
        $userIds = collect($forwardedList)->pluck('id')->filter(fn($id) => $id > 0)->unique()->toArray();
        $users = \App\Models\User::whereIn('id', $userIds)->get(['id', 'name', 'role'])->keyBy('id');

        return view('candidate.services', compact('candidate', 'forwardedList', 'users'));
    }


    public function saveFollowups(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) return response()->json(['error' => 'Candidate not found'], 404);

        $candidate->followup = $request->input('followups', '');
        $candidate->save();

        return response()->json(['success' => true]);
    }

    public function saveProfile(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) return response()->json(['error' => 'Candidate not found'], 404);

        // Collect original values BEFORE update
        $original = $candidate->only([
            'Name',
            'Phone_Number',
            'Time_Zone',
            'Email_Address',
            'Location'
        ]);

        // Apply updates (existing logic untouched)
        $candidate->Name          = $request->input('name', '');
        $candidate->Phone_Number  = $request->input('phone', '');
        $candidate->Time_Zone     = $request->input('time_zone', '');
        $candidate->Email_Address = $request->input('email', '');
        $candidate->Location      = $request->input('Location', '');

        // Detect Changes
        $changes = [];
        $fields = [
            'Name'          => 'Full Name',
            'Phone_Number'  => 'Phone Number',
            'Time_Zone'     => 'Time Zone',
            'Email_Address' => 'Email Address',
            'Location'      => 'Location'
        ];

        foreach ($fields as $key => $label) {
            $old = $original[$key] ?? '';
            $new = $candidate->$key ?? '';

            if ($old !== $new) {
                $changes[] = "[" . now()->format('Y-m-d H:i:s') . "] $label changed from '$old' to '$new'";
            }
        }

        // Append to profilechanges
        if (!empty($changes)) {
            $existingLog = $candidate->profilechanges ?? '';
            $newLogEntry = implode("\n", $changes);

            $candidate->profilechanges = trim($existingLog . "\n" . $newLogEntry);
        }

        // Save as usual (existing logic)
        $candidate->save();

        return response()->json(['success' => true]);
    }


    public function saveEdu(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) return response()->json(['error' => 'Candidate not found'], 404);

        // 1️⃣ Collect original values BEFORE update
        $original = $candidate->only([
            'Education',
            'University',
            'Year_of_Passing'
        ]);

        // 2️⃣ Existing logic (DO NOT MODIFY)
        $candidate->Education       = $request->input('Education', '');
        $candidate->University      = $request->input('University', '');
        $candidate->Year_of_Passing = $request->input('Year_of_Passing', '');

        // 3️⃣ Detect changes and prepare messages
        $changes = [];
        $fields = [
            'Education'       => 'Education',
            'University'      => 'University / College',
            'Year_of_Passing' => 'Year of Passing'
        ];

        foreach ($fields as $key => $label) {
            $old = $original[$key] ?? '';
            $new = $candidate->$key ?? '';

            if ($old !== $new) {
                $changes[] = "[" . now()->format('Y-m-d H:i:s') . "] $label changed from '$old' to '$new'";
            }
        }

        // 4️⃣ Append to educhanges log (without removing existing log)
        if (!empty($changes)) {
            $existingLog = $candidate->educhanges ?? '';
            $newLogEntry = implode("\n", $changes);
            $candidate->educhanges = trim($existingLog . "\n" . $newLogEntry);
        }

        // 5️⃣ Save normally
        $candidate->save();

        return response()->json(['success' => true]);
    }








    public function savePayment(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'Candidate not found'], 404);
        }

        // ---- 1. Capture original values BEFORE update ----
        $original = $candidate->only([
            'Amount',
            'PaymentDate',
            'TranId',
            'TranRef',
            'PaymentMethod',
            'PayeeName'
        ]);

        // ---- 2. Get JSON ----
        $json = $request->input('payment_data', '');
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON received'], 422);
        }

        // Normalize keys
        $data = array_change_key_case($data, CASE_LOWER);

        // ---- 3. Map JSON → DB fields (existing logic untouched) ----
        $updateData = [
            'Amount'        => $data['amount'] ?? null,
            'PaymentDate'   => $data['paymentdate'] ?? null,
            'TranId'        => $data['tranid'] ?? null,
            'TranRef'       => $data['tranref'] ?? null,
            'PaymentMethod' => $data['paymentmethod'] ?? null,
            'PayeeName'     => $data['payeename'] ?? null,
            'payment_data'  => $json,
        ];

        // Empty string → NULL
        foreach ($updateData as $key => $value) {
            if ($value === '' || $value === ' ') {
                $updateData[$key] = null;
            }
        }

        // ---- 4. Detect Changes ----
        $changes = [];

        $labels = [
            'Amount'        => 'Amount',
            'PaymentDate'   => 'Payment Date',
            'TranId'        => 'Transaction ID',
            'TranRef'       => 'Transaction Ref',
            'PaymentMethod' => 'Payment Method',
            'PayeeName'     => 'Payee Name'
        ];

        foreach ($labels as $key => $label) {
            $old = $original[$key] ?? '';
            $new = $updateData[$key] ?? '';

            if ($old != $new) {
                $changes[] = "[" . now()->format('Y-m-d H:i:s') . "] $label changed from '$old' to '$new'";
            }
        }

        // ---- 5. Append to paychanges field ----
        if (!empty($changes)) {
            $existingLog = $candidate->paychanges ?? '';
            $newLogEntry = implode("\n", $changes);

            $updateData['paychanges'] = trim($existingLog . "\n" . $newLogEntry);
        }

        // ---- 6. Save (original logic preserved) ----
        $candidate->update($updateData);

        return response()->json(['success' => true]);
    }







    public function autoSave(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) return response()->json(['error' => 'Candidate not found'], 404);

        // Update only passed values
        foreach ($request->all() as $key => $value) {
            $candidate->{$key} = $value;
        }

        $candidate->save();

        return response()->json(['success' => true]);
    }

    public function seniorassociate(Request $request, $userId)
    {
        // Get candidate
        $candidate = GoogleSheetData::where('id', $userId)->first();
        if (!$candidate) {
            return redirect()->back()->with('error', 'Candidate not found.');
        }

        $createdByRaw = $candidate->created_by;
        $parts = explode('|', $createdByRaw);

        $userIds = [];

        foreach ($parts as $part) {
            if (strpos($part, ':') !== false) {
                // role:id format
                [$role, $id] = explode(':', $part);
                if (is_numeric($id) && $id > 0) $userIds[] = (int)$id;
            } else {
                // pure numeric id
                if (is_numeric($part)) $userIds[] = (int)$part;
            }
        }

        // Remove duplicates because repeated ids can exist
        $userIds = array_unique($userIds);

        // Fetch users
        $users = \App\Models\User::whereIn('id', $userIds)->get(['id', 'name', 'role']);

        return view('candidate.details', compact('candidate', 'users'));
    }
}
