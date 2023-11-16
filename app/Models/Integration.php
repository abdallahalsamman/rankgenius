<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'integration_type',
        'user_id',
    ];

    public function autoBlogs()
    {
        return $this->hasMany(AutoBlog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wordpressIntegration()
    {
        return $this->hasOne(WordpressIntegration::class);
    }

    public function shopifyIntegration()
    {
        return $this->hasOne(ShopifyIntegration::class);
    }
}
