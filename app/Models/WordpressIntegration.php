<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WordpressIntegration extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'url',
        'username',
        'app_password',
        'categories',
        'tags',
        'author',
        'status',
        'time_gap',
        'integration_id',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
