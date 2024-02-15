<?php

namespace App\Helpers;

class PromptBuilder
{

    private $prompt = "";

    public function __construct()
    {
    }

    public function clear()
    {
        $this->prompt = "";
        return $this;
    }

    public function build($format = "json")
    {
        $enforcer = "Make sure the response is in $format.\n\n";

        if (strtolower($format) == "html") {
            $enforcer .= "Don't include head and doctype tag.\n\n";
        }

        if (substr($this->prompt, -strlen($enforcer)) !== $enforcer) {
            $this->prompt .= $enforcer;
        }

        return trim($this->prompt);
    }

    public function getArticleSuggestions($quantity)
    {
        $this->prompt .= <<<PROMPT
Make a list of $quantity article titles that are relevant to my business and put it in a field "titles" in the json response.
PROMPT;
        $this->prompt .= "\n\n";
        return $this;
    }

    public function getBusinessSummary()
    {
        $this->prompt .= <<<PROMPT
Write summary of my business and put it in a field "businessSummary" in the json response.
PROMPT;
        $this->prompt .= "\n\n";
        return $this;
    }

    public function setBusinessDescription($businessDescription)
    {
        $this->prompt .= "Here is a description of my business: ";
        $this->prompt .= $businessDescription;
        $this->prompt .= "\n\n";
        return $this;
    }

    public function setArticleTitle($title)
    {
        $this->prompt .= "Write an article with this title: ";
        $this->prompt .= $title;
        $this->prompt .= "\n\n";
        return $this;
    }

    public function setLanguage($language)
    {
        $this->prompt .= "Write the output in this language: ";
        $this->prompt .= $language;
        $this->prompt .= "\n\n";
        return $this;
    }

    public function addWebsiteContent($websiteText)
    {
        $this->prompt .= "Here is the content of my website: ";
        $this->prompt .= $websiteText;
        $this->prompt .= "\n\n";
        return $this;
    }

    // public $flavorMap = ["tables" => 0, "ordered_list" => 0, "unordered_list" => 0];
    // public function flavorParagraph()
    // {
    //     $index = ($i % 3) + 1;
    //     $flavor = "paragraph" . $index;

    //     if (rand(0, 100) > 90 && $this->flavorMap["tables"] < 1) {
    //         $flavor = "markdownTable";
    //         $this->flavorMap["tables"]++;
    //     }

    //     if (rand(0, 100) > 90 && $this->flavorMap["ordered_list"] < 2) {
    //         $flavor = "ordered_list" . $index;
    //         $this->flavorMap["ordered_list"]++;
    //     }

    //     if (rand(0, 100) > 90 && $this->flavorMap["unordered_list"] < 2) {
    //         $flavor = "unordered_list" . $index;
    //         $this->flavorMap["unordered_list"]++;
    //     }

    //     return $flavor;
    // }

    // #region addOutline
    public function addOutline()
    {
    //     $flavoredParagraphs = [];
    //     for ($i = 0; $i < 30; $i++) {
    //         $flavoredParagraphs[] = $this->flavorParagraph($i);
        // }

        $this->prompt .= <<<OUTLINE
You are an expert content writer that specialises in SEO.

Generate articles that use this outline.

Bold important seo keywords.

{
    "title": "",
    "articleBody": {
        "introductionSection": {
            "heading": "Overview",
            "content": {
                "subsection1": {
                    "subHeading": "Introduction",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                },
                "subsection2": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                },
                "subsection3": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                }
            }
        },
        "bodySection1": {
            "heading": "",
            "content": {
                "subsection1": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                },
                "subsection2": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                },
                "subsection3": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                }
            }
        },
        "bodySection2": {
            "heading": "",
            "content": {
                "subsection1": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                },
                "subsection2": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                },
                "subsection3": {
                    "subHeading": "",
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                }
            }
        },
        "conclusionSection": {
            "heading": "Conclusion",
            "content": {
                "subsection1": {
                    "paragraphs": {
                        "paragraph1": "",
                        "paragraph2": "",
                        "paragraph3": ""
                    }
                }
            }
        }
    }
}
OUTLINE;
        $this->prompt .= "\n\n";
        return $this;
    }
    // #endregion

    public function setArticleKeywords($businessDescription)
    {
        $this->prompt = $businessDescription;
    }

    // public function setBusinessDescription($businessDescription)
    // {
    //     $this->businessDescription = $businessDescription;
    // }

    // public function setBusinessDescription($businessDescription)
    // {
    //     $this->businessDescription = $businessDescription;
    // }

    // public function setBusinessDescription($businessDescription)
    // {
    //     $this->businessDescription = $businessDescription;
    // }

    // public function setBusinessDescription($businessDescription)
    // {
    //     $this->businessDescription = $businessDescription;
    // }

    // public function setBusinessDescription($businessDescription)
    // {
    //     $this->businessDescription = $businessDescription;
    // }

    // public function setBusinessDescription($businessDescription)
    // {
    //     $this->businessDescription = $businessDescription;
    // }
}
