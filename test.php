<?php

// $HTML = <<<HTML
// <h1>The Pros and Cons of Owning a German Shepherd in an Apartment</h1>

// <h2>Overview</h2>
// <h3>Introduction</h3>
// <p>Are you considering adding a German Shepherd to your apartment family? It's important to weigh the pros and cons of owning this intelligent and loyal breed in a limited living space. While German Shepherds can make wonderful companions, their specific needs and characteristics may not always be the best fit for apartment living. In this article, we will explore the advantages and disadvantages of owning a German Shepherd in an apartment setting, helping you make an informed decision.</p>

// <h3>Advantages of Owning a German Shepherd in an Apartment</h3>
// <p>1. Size and Exercise: Despite their larger size, German Shepherds can adapt well to apartment living if given sufficient exercise. Regular walks, playtime, and mental stimulation can help meet their exercise needs even in a smaller space.</p>
// <p>2. Security: German Shepherds are known for their protective nature, making them excellent guard dogs. Living in an apartment can provide additional security benefits, as their presence may deter potential intruders.</p>
// <p>3. Companionship: German Shepherds are highly loyal and affectionate. They can form strong bonds with their owners and provide constant companionship, which can be especially comforting in a smaller living environment.</p>

// <h3>Disadvantages of Owning a German Shepherd in an Apartment</h3>
// <p>1. Space Requirements: German Shepherds are active dogs that require ample space to move around and explore. While they can adapt to apartment living with proper exercise, it may not be ideal for their natural instincts and energy levels.</p>
// <p>2. Noise and Barking: German Shepherds are known to be vocal dogs. In an apartment setting, excessive barking can become a concern, especially if your neighbors are sensitive to noise.</p>
// <p>3. Training Needs: German Shepherds are intelligent but can be strong-willed. They require consistent training and mental stimulation to prevent boredom and potential behavior issues, which may require extra time and effort in an apartment.</p>

// <h2>Conclusion</h2>
// <p>Ultimately, the decision to own a German Shepherd in an apartment depends on your lifestyle, commitment, and ability to meet their specific needs. While German Shepherds can adapt to apartment living with proper care and attention, it's essential to consider the potential challenges that may arise. If you have the time, space, and dedication to provide for their physical and mental well-being, owning a German Shepherd in an apartment can be a rewarding experience.</p>  
// HTML;

// function isEffectivelyEmpty($node) {
//     if ($node->nodeType == XML_TEXT_NODE) {
//         return trim($node->nodeValue) === '';
//     }

//     if ($node->nodeType == XML_ELEMENT_NODE) {
//         foreach ($node->childNodes as $child) {
//             if (!isEffectivelyEmpty($child)) {
//                 return false;
//             }
//         }
//     }

//     return true; // No non-empty text nodes or elements found
// }

// function handleElement($element) {
//     if (isEffectivelyEmpty($element)) {
//         return null;
//     }

//     $block = [];
//     switch ($element->tagName) {
//         case 'h1':
//         case 'h2':
//         case 'h3':
//         case 'h4':
//         case 'h5':
//         case 'h6':
//         case 'h7':
//             $level = (int)substr($element->tagName, 1);
//             $block = [
//                 'type' => 'header',
//                 'data' => [
//                     'text' => $element->textContent,
//                     'level' => $level
//                 ]
//             ];
//             break;
//         case 'p':
//             $block = [
//                 'type' => 'paragraph',
//                 'data' => [
//                     'text' => $element->textContent
//                 ]
//             ];
//             break;
//         case 'ul':
//         case 'ol':
//             $items = [];
//             foreach ($element->childNodes as $li) {
//                 if ($li->nodeType == XML_ELEMENT_NODE && $li->nodeName === 'li') {
//                     // Handle nested lists or additional content within <li>
//                     $liContent = '';
//                     foreach ($li->childNodes as $childNode) {
//                         if ($childNode->nodeType == XML_ELEMENT_NODE) {
//                             // Recursively handle child elements (e.g., for nested lists)
//                             $childBlock = handleElement($childNode);
//                             if (!empty($childBlock)) {
//                                 // For simplicity, concatenating child elements' text content
//                                 $liContent .= $childBlock['data']['text'] . ' ';
//                             }
//                         } elseif ($childNode->nodeType == XML_TEXT_NODE) {
//                             $liContent .= $childNode->nodeValue . ' ';
//                         }
//                     }
//                     $items[] = trim($liContent);
//                 }
//             }
//             $block = [
//                 'type' => 'list',
//                 'data' => [
//                     'style' => $element->tagName === 'ol' ? 'ordered' : 'unordered',
//                     'items' => $items
//                 ]
//             ];
//             break;
//         // case 'article':
//         // case 'section':
//         // case 'body':
//         // case 'div':
//         default:
//             $nestedBlocks = [];
//             foreach ($element->childNodes as $childNode) {
//                 if ($childNode->nodeType == XML_ELEMENT_NODE) {
//                     $childBlock = handleElement($childNode);
//                     if (!empty($childBlock)) {
//                         if (is_array(current($childBlock))) {
//                             $nestedBlocks = array_merge($nestedBlocks, $childBlock);
//                         } else {
//                             $nestedBlocks[] = $childBlock;
//                         }
//                     }
//                 }
//             }
//             // Instead of returning a single block, return an array of blocks
//             return $nestedBlocks;
//             break;
//     }
//     return $block;
// }

// function convertHTMLToEditorJsBlocks($htmlContent)
// {
//     $doc = new DOMDocument();
//     @$doc->loadHTML('<html>'.$htmlContent.'</html>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

//     $blocks = [];

//     foreach ($doc->childNodes as $node) {
//         if ($node->nodeType == XML_ELEMENT_NODE) {
//             $block = handleElement($node);
//             if (!empty($block)) {
//                 if (is_array(current($block))) {
//                     $blocks = array_merge($blocks, $block);
//                 } else {
//                     $blocks[] = $block;
//                 }

//             }
//         }
//     }

//     $editorJsData = [
//         'time' => time(),
//         'blocks' => array_values(array_filter($blocks))
//     ];

//     return json_encode($editorJsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
// }

// print(convertHTMLToEditorJsBlocks($HTML));
