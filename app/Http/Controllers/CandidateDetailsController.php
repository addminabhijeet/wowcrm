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

        $candidate->Name          = $request->input('name', '');
        $candidate->Phone_Number  = $request->input('phone', '');
        $candidate->Time_Zone     = $request->input('time_zone', '');

        // NEW FIELDS
        $candidate->Email_Address = $request->input('email', '');
        $candidate->Location      = $request->input('Location', '');

        $candidate->save();

        return response()->json(['success' => true]);
    }


    public function saveEdu(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'Candidate not found'], 404);
        }

        // Collect input
        $data = [
            'Relocation'      => $request->input('relocation', null),
            'Graduation_Date' => $request->input('graduation', null),
            'Immigration'     => $request->input('immigration', null),
            'Course'          => $request->input('course', null),
            'Qualification'   => $request->input('qualification', null),
            'updated_at'      => now(),
        ];

        // Fix graduation date format
        if (!empty($data['Graduation_Date'])) {
            $timestamp = strtotime($data['Graduation_Date']);
            if ($timestamp !== false) {
                $data['Graduation_Date'] = date('Y-m-d', $timestamp);
            } else {
                $data['Graduation_Date'] = null;
            }
        }

        // Convert empty strings to null
        foreach ($data as $key => $value) {
            if ($value === '' || $value === ' ') {
                $data[$key] = null;
            }
        }

        // Update candidate
        $candidate->update($data);

        return response()->json(['success' => true]);
    }




    public function savePayment(Request $request, $id)
    {
        $candidate = GoogleSheetData::find($id);
        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'Candidate not found'], 404);
        }

        // Get JSON
        $json = $request->input('payment_data', '');
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON received'], 422);
        }

        // Normalize array keys to match DB structure
        $data = array_change_key_case($data, CASE_LOWER);

        // Map JSON → DB with fallback NULL
        $updateData = [
            'Amount'        => isset($data['amount']) ? $data['amount'] : null,
            'PaymentDate'   => $data['paymentdate'] ?? null,
            'TranId'        => $data['tranid'] ?? null,
            'TranRef'       => $data['tranref'] ?? null,
            'PaymentMethod' => $data['paymentmethod'] ?? null,
            'PayeeName'     => $data['payeename'] ?? null,
            'payment_data'  => $json,  // store original json
            'updated_at'    => now(),
        ];

        // Replace empty string → NULL
        foreach ($updateData as $key => $value) {
            if ($value === '' || $value === ' ') {
                $updateData[$key] = null;
            }
        }

        // Save
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
