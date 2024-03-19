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

    public function setArticleTopic($articleTopic)
    {
        $this->prompt .= "I want to write an article about this topic: ";
        $this->prompt .= $articleTopic;
        $this->prompt .= "\n\n";
        $this->prompt .= "Please write an article that is relevant to the topic, and include my business only at the end of the article.";
        $this->prompt .= "\n\n";
        $this->prompt .= "Please write an engaging title relevant to the topic.";
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

make sure to stay relevant to the topic, make sure the articles are informative and engaging.

don't make the article about the company, make it about the topic.

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

    public function setArticleKeywords($articleTopic)
    {
        $this->prompt = $articleTopic;
    }

    public function introduceWriter() {
        $this->prompt .= <<<PROMPT
I am a content writer that specialises in SEO.

I need help with writing articles that are relevant to my business.

I will send you and article in HTML, the selected text and a prompt, and I want you to send me back 5 to 10 suggestions that I can replace with the selected text.

return the suggestions in a json response with a field "suggestions".
PROMPT;
        $this->prompt .= "\n\n";
        return $this;
    }

    public function promptArticleAssistance($articleHTML, $selectedText, $prompt)
    {
        $this->prompt .= <<<PROMPT
This is my article in HTML

$articleHTML

I want to replace this text:
$selectedText

With something that is relevant to my business.

Here is the prompt I want you to use:
$prompt
PROMPT;
        $this->prompt .= "\n\n";
        return $this;
    }

}
