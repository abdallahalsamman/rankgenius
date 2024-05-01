<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitemapEmbedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'embedding',
        'sitemap_id'
    ];

    public function sitemap()
    {
        return $this->belongsTo(Sitemap::class);
    }

    public function getEmbeddingAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setEmbeddingAttribute($value)
    {
        $this->attributes['embedding'] = json_encode($value);
    }
}
