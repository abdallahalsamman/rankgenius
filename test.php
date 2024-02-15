<?php

$HTML = <<<HTML
<body>
    <article>
        <h1>Tips for Apartment Owners: Managing a German Shepherd's Exercise Needs</h1>

        <section class="introduction-section">
            <h2>Overview</h2>
            <div class="subsection">
                <h3>Introduction</h3>
                <p>Living in an apartment presents a unique set of challenges for dog owners, especially when the dog in question is a <strong>German Shepherd</strong>. Known for their high energy and need for regular exercise, German Shepherds can thrive in apartment settings with the right management strategies.</p>
                <p>This article aims to provide <strong>practical tips</strong> and advice for apartment owners to ensure their German Shepherd gets sufficient exercise. We'll explore creative solutions to meet your dog's physical needs, even within the confines of smaller living spaces.</p>
                <p>By following these guidelines, you can promote a healthy and happy lifestyle for your German Shepherd, while enjoying the companionship of your loyal and loving pet in your apartment home.</p>
            </div>
            <div class="subsection">
                <h3>Why Exercise is Crucial for German Shepherds</h3>
                <p>Exercise is not just about keeping your dog physically fit; it's also essential for their mental health. German Shepherds are intelligent, working dogs that require stimulation to prevent boredom and destructive behavior.</p>
                <p>Without adequate exercise, a German Shepherd can develop issues such as excessive barking, chewing, and even depression. Therefore, it's crucial for apartment owners to find ways to incorporate exercise into their daily routine.</p>
                <p>Regular physical activity helps maintain your German Shepherd's muscle tone, keeps their joints healthy, and provides an outlet for their boundless energy.</p>
            </div>
            <div class="subsection">
                <h3>Understanding Your German Shepherd's Needs</h3>
                <p>Each German Shepherd is unique, and so are their exercise needs. While some may require more intense physical activity, others might be satisfied with moderate exercise.</p>
                <p>It's important to pay attention to your dog's behavior and energy levels to gauge the right amount of exercise. A good indicator of their exercise needs is how they behave after a workout—if they're still full of energy, they might need more activity.</p>
                <p>Keep in mind that German Shepherds also need mental stimulation, so incorporating training and problem-solving games into their exercise routine is beneficial.</p>
            </div>
        </section>

        <section class="body-section1">
            <h2>Indoor Exercise Strategies</h2>
            <div class="subsection">
                <h3>Interactive Playtime</h3>
                <p>Invest in <strong>interactive toys</strong> that can keep your German Shepherd engaged indoors. Toys like puzzle feeders or treat-dispensing balls can provide both mental and physical stimulation.</p>
                <p>Playing tug-of-war with a sturdy rope or engaging in a game of hide-and-seek with treats can also be a great way to get your dog moving.</p>
                <p>Remember to create a safe play space free of breakable items and with enough room for your dog to move around without risk of injury.</p>
            </div>
            <div class="subsection">
                <h3>Obedience Training Sessions</h3>
                <p>Regular <strong>obedience training</strong> is not only good for discipline but also for mental exercise. Teach your German Shepherd new commands or practice existing ones to keep their mind sharp.</p>
                <p>Training sessions can be broken up throughout the day to provide consistent mental engagement and to break up periods of inactivity.</p>
                <p>Consider hiring a professional trainer if you need assistance with advanced training techniques suitable for indoor environments.</p>
            </div>
            <div class="subsection">
                <h3>Indoor Obstacle Courses</h3>
                <p>Creating a simple obstacle course inside your apartment can provide a fun and challenging way for your German Shepherd to exercise. Use cushions, chairs, and blankets to make tunnels and hurdles.</p>
                <p>Guide your dog through the course with treats and encouragement, adjusting the difficulty as they become more adept.</p>
                <p>This not only exercises their body but also helps with agility and obedience.</p>
            </div>
        </section>

        <section class="body-section2">
            <h2>Outdoor Exercise Options</h2>
            <div class="subsection">
                <h3>Daily Walks and Runs</h3>
                <p>Schedule at least one long <strong>walk or run</strong> each day. This is essential for your German Shepherd's well-being and provides the opportunity for them to explore the environment.</p>
                <p>Consider varying the route to keep the walks interesting for your dog. If possible, find a nearby park where they can roam more freely.</p>
                <p>For those with higher energy levels, running or jogging can be a great way to tire them out and keep them fit.</p>
            </div>
            <div class="subsection">
                <h3>Dog Parks and Playdates</h3>
                <p>Visiting a local <strong>dog park</strong> can provide a safe and enclosed space for your German Shepherd to run off-leash and socialize.</p>
                <p>Organizing playdates with other dogs can also be beneficial, as it allows for social interaction and physical play that can be more vigorous than what you might provide alone.</p>
                <p>Ensure your dog is well-trained and can recall on command before visiting off-leash parks for the safety of your pet and others.</p>
            </div>
            <div class="subsection">
                <h3>Outdoor Adventures</h3>
                <p>Whenever possible, take your German Shepherd on outdoor adventures such as hiking or swimming. These activities are excellent for providing natural and stimulating exercise opportunities.</p>
                <p>Always keep your dog's safety in mind, ensuring they are well-hydrated and not overexerted, especially in extreme weather conditions.</p>
                <p>Respect leash laws and wildlife by keeping your dog under control at all times during these excursions.</p>
            </div>
        </section>

        <section class="conclusion-section">
            <h2>Conclusion</h2>
            <p>Managing a <strong>German Shepherd's exercise needs</strong> as an apartment owner is entirely possible with a bit of creativity and commitment. From indoor play to outdoor adventures, there are numerous ways to keep your dog healthy and happy.</p>
            <p>Remember, a well-exercised German Shepherd is a content and well-behaved companion. By meeting their physical and mental exercise needs, you'll ensure a harmonious living situation for both you and your cherished pet.</p>
            <p>With these tips, you're well on your way to providing a loving and stimulating environment for your German Shepherd, regardless of your living situation.</p>
        </section>
    </article>
</body>
HTML;

function isEffectivelyEmpty($node) {
    if ($node->nodeType == XML_TEXT_NODE) {
        return trim($node->nodeValue) === '';
    }

    if ($node->nodeType == XML_ELEMENT_NODE) {
        foreach ($node->childNodes as $child) {
            if (!isEffectivelyEmpty($child)) {
                return false;
            }
        }
    }

    return true; // No non-empty text nodes or elements found
}

function handleElement($element) {
    if (isEffectivelyEmpty($element)) {
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
        case 'h7':
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
                    'text' => $element->textContent
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
                            $childBlock = handleElement($childNode);
                            if (!empty($childBlock)) {
                                // For simplicity, concatenating child elements' text content
                                $liContent .= $childBlock['data']['text'] . ' ';
                            }
                        } elseif ($childNode->nodeType == XML_TEXT_NODE) {
                            $liContent .= $childNode->nodeValue . ' ';
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
                    $childBlock = handleElement($childNode);
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

function convertHTMLToEditorJsBlocks($htmlContent)
{
    $doc = new DOMDocument();
    @$doc->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $blocks = [];

    foreach ($doc->childNodes as $node) {
        if ($node->nodeType == XML_ELEMENT_NODE) {
            $block = handleElement($node);
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

print(convertHTMLToEditorJsBlocks($HTML));