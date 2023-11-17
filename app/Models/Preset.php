<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preset extends Model
{
    use HasFactory, SoftDeletes;

protected $dates = ['deleted_at'];

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'mode',
        'details',
        'language',
        'creativity',
        'tone_of_voice',
        'custom_instructions',
        'point_of_view',
        'call_to_action',
        'sitemap_url',
        'sitemap_filter',
        'automatic_external_link',
        'extra_links',
        'featured_image_enabled',
        'in_article_images',
        'automatic_youtube_videos',
        'youtube_videos',
        'user_id',
    ];

    /**
     * Define the relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function autoBlogs()
    {
        return $this->hasMany(AutoBlog::class);
    }
}
