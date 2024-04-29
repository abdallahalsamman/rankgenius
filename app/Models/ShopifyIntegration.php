<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopifyIntegration extends Model
{
    use HasFactory, SoftDeletes;

protected $dates = ['deleted_at'];

    protected $fillable = ['integration_id', 'shop_name', 'access_token', 'blog', 'author'];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
