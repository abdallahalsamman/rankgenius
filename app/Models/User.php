<?php

namespace App\Models;

use App\Models\Preset;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
    ];

    /**
     * Define the relationship with Presets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Define the relationship with Presets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presets()
    {
        return $this->hasMany(Preset::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
