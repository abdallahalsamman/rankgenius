<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }
}
