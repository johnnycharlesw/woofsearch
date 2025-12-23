<?php
use WoofSearch\SearchEngine;
function renderResults(){
    global $page;
    global $page_;
    $page_ = $page;
            function stripTagsWithContent($html, $tagsToRemove = ['script', 'style', 'iframe', 'object', 'embed', 'noscript', 'svg', 'math', 'template', 'header', 'footer', 'nav']) {
                // Suppress warnings from malformed HTML
                libxml_use_internal_errors(true);

                $doc = new DOMDocument();
                $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                foreach ($tagsToRemove as $tag) {
                    $elements = $doc->getElementsByTagName($tag);

                    // Because getElementsByTagName is live, we need to loop backwards
                    for ($i = $elements->length - 1; $i >= 0; $i--) {
                        $element = $elements->item($i);
                        $element->parentNode->removeChild($element);
                    }
                }

                return $doc->saveHTML();
            }

            # Start up the actual search backend and get results
            
            $engine=new SearchEngine();
            global $search;
            $tokens = $engine->parseQueryWithOperators($search);
            $results_=$engine->multiTokenSearch($tokens, $page-1);
            $results = [];
            foreach ($results_ as $result) {
                $processed_result = [
                    'location' => $result['url'],
                    'text' => stripTagsWithContent($result['content'])
                ];
                $processed_result['text'] = strip_tags($processed_result['text'], ["ul", "li", "b", "i", "em", 'p', 'br']);
                $results[] = $processed_result;
            }
            // Render the results
            foreach ($results as $result) {
                $location=$result['location'];
                $search=$result['text'];
                $linkRender = <<<html
                <li>
                    <a href="{$location}" target="_blank" class="search-result">
                        <span class="result-link">{$location}</span>
                        <br>
                        <span class="result-text">{$search}</span>
                    </a>
                </li>
                html;
                echo $linkRender;
            }
}