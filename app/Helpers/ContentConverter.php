<?php

namespace App\Helpers;

class ContentConverter
{
    public static function convertParagraphsToMarkdown($paragraphs)
    {
        $markdown = "";
        foreach ($paragraphs as $paragraph) {
            if (!empty($paragraph)) {
                $markdown .= $paragraph . "\n\n";
            }
        }

        return $markdown;
    }

    public static function convertContentToMarkdown($content)
    {
        $markdown = "";
        foreach ($content as $section) {
            $subHeading = $section["subHeading"] ?? $section["question"] ?? "";
            $paragraphs = $section["paragraphs"];

            if (!empty($subHeading)) {
                $markdown .= "### " . $subHeading . "\n\n";
            }

            $markdown .= self::convertParagraphsToMarkdown($paragraphs);
        }

        return $markdown;
    }

    public static function convertToMarkdown($data)
    {
        $markdown = "# " . $data["title"] . "\n\n";
        foreach ($data["articleBody"] as $section) {
            $heading = $section["heading"];
            $content = $section["content"];

            if (!empty($heading)) {
                $markdown .= "## " . $heading . "\n\n";
            }

            $markdown .= self::convertContentToMarkdown($content);
        }

        return $markdown;
    }

    public static function isEffectivelyEmpty($node)
    {
        if ($node->nodeType == XML_TEXT_NODE) {
            return trim($node->nodeValue) === '';
        }

        if ($node->nodeType == XML_ELEMENT_NODE) {
            foreach ($node->childNodes as $child) {
                if (!self::isEffectivelyEmpty($child)) {
                    return false;
                }
            }
        }

        return true; // No non-empty text nodes or elements found
    }

    public static function handleElement($element)
    {
        // Skip effectively empty elements
        if (self::isEffectivelyEmpty($element)) {
            return null;
        }

        $block = [];
        switch ($element->tagName) {
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
            case 'h7': // Though not standard, included for completeness
                $level = (int)substr($element->tagName, 1);
                $block = [
                    'type' => 'header',
                    'data' => [
                        'text' => $element->textContent,
                        'level' => $level
                    ]
                ];
                break;
            case 'p':
                $block = [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $element->ownerDocument->saveHTML($element)
                    ]
                ];
                break;
            case 'ul':
            case 'ol':
                $items = [];
                foreach ($element->childNodes as $li) {
                    if ($li->nodeType == XML_ELEMENT_NODE && $li->nodeName === 'li') {
                        // Handle nested lists or additional content within <li>
                        $liContent = '';
                        foreach ($li->childNodes as $childNode) {
                            if ($childNode->nodeType == XML_ELEMENT_NODE) {
                                // Recursively handle child elements (e.g., for nested lists)
                                $childBlock = self::handleElement($childNode);
                                if (!empty($childBlock)) {
                                    // For simplicity, concatenating child elements' text content
                                    $liContent .= $childBlock['data']['text'] . ' ';
                                }
                            } elseif ($childNode->nodeType == XML_TEXT_NODE) {
                                $liContent .= $childNode->ownerDocument->saveHTML($childNode) . ' ';
                            }
                        }
                        $items[] = trim($liContent);
                    }
                }
                $block = [
                    'type' => 'list',
                    'data' => [
                        'style' => $element->tagName === 'ol' ? 'ordered' : 'unordered',
                        'items' => $items
                    ]
                ];
                break;
                // case 'article':
                // case 'section':
                // case 'body':
                // case 'div':
            default:
                $nestedBlocks = [];
                foreach ($element->childNodes as $childNode) {
                    if ($childNode->nodeType == XML_ELEMENT_NODE) {
                        $childBlock = self::handleElement($childNode);
                        if (!empty($childBlock)) {
                            if (is_array(current($childBlock))) {
                                $nestedBlocks = array_merge($nestedBlocks, $childBlock);
                            } else {
                                $nestedBlocks[] = $childBlock;
                            }
                        }
                    }
                }
                // Instead of returning a single block, return an array of blocks
                return $nestedBlocks;
                break;
        }
        return $block;
    }

    public static function convertHTMLToEditorJsBlocks($htmlContent)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML('<html>' . $htmlContent . '</html>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $blocks = [];

        foreach ($doc->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $block = self::handleElement($node);
                if (!empty($block)) {
                    if (is_array(current($block))) {
                        $blocks = array_merge($blocks, $block);
                    } else {
                        $blocks[] = $block;
                    }
                }
            }
        }

        $editorJsData = [
            'time' => time(),
            'blocks' => array_values(array_filter($blocks))
        ];

        return json_encode($editorJsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function convertJSONToEditorJsBlocks($data)
    {
        $blocks = [];

        $blocks[] = [
            'type' => 'header',
            'data' => [
                'text' => $data['title'],
                'level' => 1
            ]
        ];

        foreach ($data['articleBody'] as $sectionKey => $section) {
            if (!empty($section['heading'])) {
                $blocks[] = [
                    'type' => 'header',
                    'data' => [
                        'text' => $section['heading'],
                        'level' => 2
                    ]
                ];
            }

            foreach ($section['content'] as $subsectionKey => $subsection) {
                if (!empty($subsection['subHeading']) || !empty($subsection['question'])) {
                    $subHeading = $subsection['subHeading'] ?? $subsection['question'];
                    $blocks[] = [
                        'type' => 'header',
                        'data' => [
                            'text' => $subHeading,
                            'level' => 3
                        ]
                    ];
                }

                foreach ($subsection['paragraphs'] as $paragraphKey => $paragraph) {
                    if (!empty($paragraph)) {
                        $blocks[] = [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => $paragraph
                            ]
                        ];
                    }
                }
            }
        }

        $editorJsData = [
            'time' => time(),
            'blocks' => $blocks,
            'version' => '2.22.2'
        ];

        return json_encode($editorJsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
