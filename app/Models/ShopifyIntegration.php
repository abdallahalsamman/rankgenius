<?php

namespace App\Models;

use Signifly\Shopify\Shopify;
use Durlecode\EJSParser\Parser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopifyIntegration extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['integration_id', 'shop_name', 'access_token', 'blog', 'author'];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function publishBatch($batch)
    {
        $shopify = new Shopify($this->access_token, $this->shop_name . '.myshopify.com', '2024-04');

        foreach ($batch->articles as $article) {
            $data = [
                'title' => $article->title,
                'author' => $this->author,
                'body_html' => Parser::parse($article->content)->toHtml(),
            ];

            try {
                $post = $shopify->createArticle($data);
            } catch (\Exception $e) {
                dd($e);
            }
        }
    }
}
