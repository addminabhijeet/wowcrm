<?php

namespace App\Http\Controllers;

use App\Models\GoogleSheetData;
use Illuminate\Http\Request;

class CandidateDetailsController extends Controller
{
    public function associate(Request $request, $userId)
    {
        $candidate = GoogleSheetData::where('id', $userId)->first();
        if (!$candidate) {
            return redirect()->back()->with('error', 'Candidate not found.');
        }
        return view('candidate.details', compact('candidate'));
    }

    public function seniorassociate(Request $request, $userId)
    {
        $candidate = GoogleSheetData::where('id', $userId)->first();
        if (!$candidate) {
            return redirect()->back()->with('error', 'Candidate not found.');
        }
        return view('candidate.details', compact('candidate'));
    }
}
