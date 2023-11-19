<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use HasFactory, SoftDeletes;

protected $dates = ['deleted_at'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'integration_type_id',
        'user_id',
    ];

    public function integrationType()
    {
        return $this->belongsTo(IntegrationType::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }

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
