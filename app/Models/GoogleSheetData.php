<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheetData extends Model
{
    protected $table = 'google_sheet_data';

    protected $fillable = [
        'sheet_row_number',
        'data',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array', // auto decode JSON to array
    ];
}
