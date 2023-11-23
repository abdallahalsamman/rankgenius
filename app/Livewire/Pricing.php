<?php

namespace App\Livewire;

use Livewire\Component;

class Pricing extends Component
{
    public $yearly = true;
    public $customCredits = 0;

    public $pricingTable = [
        "writer" => [
            "price" => ["monthly" => 39, "yearly" => 19, "credits" => 90],
            "features" => [
                ["html" => "<b>150+</b> supported languages",],
                ["html" => "Featured Images", "hint" => "Article thumbnails will be included"],
                ["html" => "<b>Keyword-based</b> generation",],
                ["html" => "<b>Title-based</b> generation",],
                ["html" => "Customizable <b>outline</b>",],
                ["html" => "<b>In-Article</b> images",],
                ["html" => "Export <b>HTML or Markdown</b>",],
                ["html" => "<b>Youtube</b> videos", "hint" => "Incorporate youtube videos in your articles.", "icon" => "phosphor.youtube-logo-fill"],
            ]
        ],
        "autoblog" => [
            "price" => ["monthly" => 69, "yearly" => 39, "credits" => 150],
            "features" => [
                ["html" => "<b>Up to 2 AutoBlogs</b>", "hint" => "Option to auto-generate & auto-publish to 2 websites."],
                ["html" => "<b>Internal</b> linking",],
                ["html" => "<b>External </b> linking",],
                ["html" => "<b>Wordpress</b> integration", "icon" => "bi.wordpress"],
            ]
        ],
        "ultimate" => [
            "price" => [
                ["monthly" => 99, "yearly" => 59, "credits" => 300],
                ["monthly" => 150, "yearly" => 100, "credits" => 500],
                ["monthly" => 300, "yearly" => 200, "credits" => 1000],
                ["monthly" => 750, "yearly" => 500, "credits" => 2500],
                ["monthly" => 1500, "yearly" => 1000, "credits" => 5000],
                ["monthly" => 3000, "yearly" => 2000, "credits" => 10000],
                ["monthly" => 4500, "yearly" => 3000, "credits" => 15000],
                ["monthly" => 6000, "yearly" => 4000, "credits" => 20000],
                ["monthly" => 7500, "yearly" => 5000, "credits" => 25000],
            ],
            "features" => [
                ["html" => "<b>Unlimited AutoBlogs</b>", "hint" => "Add automatic blogging to unlimited websites."],
                ["html" => "<b>Priority Support</b>",],
            ]
        ],
    ];
}
