<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoBlog extends Model
{
    use HasFactory, SoftDeletes;

protected $dates = ['deleted_at'];


    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'quantity',
        'interval',
        'status',
        'preset_id',
        'integration_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preset()
    {
        return $this->belongsTo(Preset::class);
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
