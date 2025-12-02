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
