<?php

namespace App\Models;

use App\Enums\IntegrationTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function publishBatch($batch)
    {
        switch ($this->integrationType->name) {
            case IntegrationTypeEnum::WORDPRESS->value:
                $this->wordpressIntegration->publishBatch($batch);
                break;
            case IntegrationTypeEnum::SHOPIFY->value:
                $this->shopifyIntegration->publishBatch($batch);
                break;
            default:
                break;
        }

        foreach ($batch->articles as $article) {
            Publication::create([
                'user_id' => $this->user_id,
                'article_id' => $article->id,
                'integration_id' => $this->id,
                'url' => ''
            ]);
        }
    }

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
