<?php

namespace App\Models;

use Pgvector\Laravel\Vector;
use Pgvector\Laravel\HasNeighbors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SitemapEmbedding extends Model
{
    use HasFactory, HasNeighbors;

    protected $fillable = [
        'url',
        'embedding',
        'sitemap_id'
    ];

    protected $casts = ['embedding' => Vector::class];

    public function sitemap()
    {
        return $this->belongsTo(Sitemap::class);
    }
}
