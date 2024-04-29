<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MadeITBelgium\WordPress\WordPress;
use Durlecode\EJSParser\Parser;

class WordpressIntegration extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'url',
        'username',
        'app_password',
        'categories',
        'tags',
        'author',
        'status',
        'time_gap',
        'integration_id',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function publishBatch($batch)
    {
        $postManager = (new WordPress($this->url))
            ->setUsername($this->username)
            ->setApplicationPassword($this->app_password)
            ->post();

        foreach ($batch->articles as $article) {
            $data = [
                'title' => $article->title,
                'status' => $this->status,
                'author' => json_decode($this->author),
                'tags' => json_decode($this->tags),
                'categories' => json_decode($this->categories),
                'content' => Parser::parse($article->content)->toHtml(),
            ];

            try {
                $post = $postManager->create($data);
            } catch (\Exception $e) {
                dd($e);
            }
        }
    }
}
