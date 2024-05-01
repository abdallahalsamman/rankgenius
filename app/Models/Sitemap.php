<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sitemap extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'last_fetched'
    ];

    public function embeddings()
    {
        return $this->hasMany(SitemapEmbedding::class);
    }
}
