<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheetData extends Model
{
    protected $table = 'google_sheet_data';

    protected $fillable = [
        'sheet_row_number',
        'data',
        'resume',
        'created_by',
        // Flattened columns
        'Date',
        'Name',
        'Email_Address',
        'Phone_Number',
        'Location',
        'Relocation',
        'Graduation_Date',
        'Immigration',
        'Course',
        'Amount',
        'Qualification',
        'Exe_Remarks',
        'First_Follow_Up_Remarks',
        'Time_Zone',
        'View',
    ];

    protected $casts = [
        'data' => 'array', // auto decode JSON
        'Date' => 'date',
        'Graduation_Date' => 'date',
        'Amount' => 'decimal:2',
    ];

    public function getResumeUrlAttribute()
    {
        return $this->resume ? asset('storage/resumes/' . $this->resume) : null;
    }
}
