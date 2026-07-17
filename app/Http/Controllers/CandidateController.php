<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\GoogleSheetData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\SmtpSetting;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;


class CandidateController extends Controller
{

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

        // Fetch all users upfront to avoid N+1 queries (cache for 5 min)
        $allUsers = Cache::remember('active_users_list', 300, function () {
            return \App\Models\User::where('is_deleted', 0)->get()->keyBy('id');
        });

        // Map forwarded_by dynamically (multi-level like senior)
        $data->getCollection()->transform(function ($item) use ($authUser, $allUsers) {
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
                        $user = $allUsers->get($userId);
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
        $juniorUsers = \App\Models\User::where('is_deleted', 0)->where('role', 'junior')
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'phone', 'gender']);


        // Handle AJAX request for both search and pagination
        if ($request->ajax()) {
            return view('database.partials.senior_table', compact('data'))->render();
        }

        return view('candidate.accountant', compact('data', 'juniorUsers'));
    }
}
