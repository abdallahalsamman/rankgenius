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

    public function build()
    {
        $this->prompt .= "Make sure the response is in JSON format";
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

    public function addWebsiteContent($websiteText)
    {
        $this->prompt .= "Here is the content of my website: ";
        $this->prompt .= $websiteText;
        $this->prompt .= "\n\n";
        return $this;
    }

    public $flavorMap = ["tables" => 0, "ordered_list" => 0, "unordered_list" => 0];
    public function flavorParagraph()
    {
        $flavor = "paragraph";

        if (rand(0, 100) > 90 && $this->flavorMap["tables"] < 2) {
            $flavor = "table";
            $this->flavorMap["tables"]++;
        }

        if (rand(0, 100) > 90 && $this->flavorMap["ordered_list"] < 2) {
            $flavor = "ordered_list";
            $this->flavorMap["ordered_list"]++;
        }

        if (rand(0, 100) > 90 && $this->flavorMap["unordered_list"] < 2) {
            $flavor = "unordered_list";
            $this->flavorMap["unordered_list"]++;
        }

        return $flavor;
    }

    // #region addOutline
    public function addOutline()
    {
        $flavoredParagraphs = [];
        for ($i = 0; $i < 30; $i++) {
            $flavoredParagraphs[] = $this->flavorParagraph();
        }

        $this->prompt .= <<<OUTLINE
You are an expert content writer that specialises in SEO.

Generate articles that use this outline, fill all empty fields with strings using markdown format.

Bold important keywords.

{
    "title":"",
    "articleBody": {
        "section1": {
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
                        "$flavoredParagraphs[0]1": "",
                        "$flavoredParagraphs[1]2": "",
                        "$flavoredParagraphs[2]3": "",
                    }
                },
                "subsection3": {
                    "subHeading": "",
                    "paragraphs": {
                        "$flavoredParagraphs[3]1": "",
                        "$flavoredParagraphs[4]2": "",
                        "$flavoredParagraphs[5]3": ""
                    }
                }
            }
        },
        "section2": {
            "heading": "",
            "content": {
                "subsection1": {
                    "subHeading": "",
                    "paragraphs": {
                            "$flavoredParagraphs[6]1": "",
                            "$flavoredParagraphs[7]2": "",
                            "$flavoredParagraphs[8]3": ""
                    }
                },
                "subsection2": {
                    "subHeading": "",
                    "paragraphs": {
                        "$flavoredParagraphs[9]1": "",
                        "$flavoredParagraphs[10]2": "",
                        "$flavoredParagraphs[11]3": ""
                    }
                },
                "subsection3": {
                    "subHeading": "",
                    "paragraphs": {
                        "$flavoredParagraphs[12]1": "",
                        "$flavoredParagraphs[13]2": "",
                        "$flavoredParagraphs[14]3": ""
                    }
                }
            }
        },
        "section3": {
            "heading": "",
            "content": {
                "subsection1": {
                    "subHeading": "",
                    "paragraphs": {
                            "$flavoredParagraphs[15]1": "",
                            "$flavoredParagraphs[16]2": "",
                            "$flavoredParagraphs[17]3": ""
                    }
                },
                "subsection2": {
                    "subHeading": "",
                    "paragraphs": {
                        "$flavoredParagraphs[18]1": "",
                        "$flavoredParagraphs[19]2": "",
                        "$flavoredParagraphs[20]3": ""
                    }
                },
                "subsection3": {
                    "subHeading": "",
                    "paragraphs": {
                        "$flavoredParagraphs[21]1": "",
                        "$flavoredParagraphs[22]2": "",
                        "$flavoredParagraphs[23]3": ""
                    }
                }
            }
        },
        "section4": {
            "heading": "Conclusion",
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
        "section5": {
            "heading": "FAQ",
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
