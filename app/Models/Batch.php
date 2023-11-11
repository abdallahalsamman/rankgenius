<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public static $STATUS = [
        'DONE' => 'Done',
        'CANCELLED' => 'Cancelled',
        'IN_PROGRESS' => 'In Progress',
    ];

    protected $fillable = ['id', 'summary', 'status'];
}
