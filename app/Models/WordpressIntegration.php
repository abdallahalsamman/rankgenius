<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WordPressIntegration extends Model
{
    use HasFactory, SoftDeletes;

protected $dates = ['deleted_at'];

    protected $fillable = ['url', 'categories', 'tags', 'author', 'status', 'time_gap'];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
