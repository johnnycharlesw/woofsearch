<?php
namespace WoofSearch;
use PHPizza\Database\Database;
class SearchEngine {
    private $db;
    public function __construct() {
        global $dbServer, $dbUser, $dbPassword, $dbType;
        $this->db=new Database($dbServer, $dbUser, $dbPassword, 'woofsearch', $dbType);
    }

    public function getTotalPagesCrawled(){
        # How many pages did WoofSearchBot crawl, again?
        $sql = <<<SQL
SELECT COUNT(*) AS total_docs
FROM pages;
SQL;
        $pages_ = $this->db->fetchRow($sql);
        $pages = $pages_['total_docs'];
        return $pages;
    }


    public function _parseQueryWithOperators(string $query): array {
        $must = [];
        $mustNot = [];
        $optional = [];

        $query = trim($query);

        // Match phrases with optional + or -
        preg_match_all('/([+-]?)"([^"]+)"/', $query, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $op = $match[1];       // +, -, or empty
            $phrase = strtolower($match[2]);

            if ($op === '+') {
                $must[] = $phrase;
            } elseif ($op === '-') {
                $mustNot[] = $phrase;
            } else {
                $optional[] = $phrase;
            }

            // Remove matched phrase from query
            $query = str_replace($match[0], '', $query);
        }

        // Match remaining single words with optional + or -
        preg_match_all('/([+-]?)(\S+)/', $query, $wordMatches, PREG_SET_ORDER);
        foreach ($wordMatches as $match) {
            $op = $match[1];
            $word = strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $match[2]));

            if ($word === '') continue;

            if ($op === '+') {
                $must[] = $word;
            } elseif ($op === '-') {
                $mustNot[] = $word;
            } else {
                $optional[] = $word;
            }
        }

        return [
            'must' => array_values($must),
            'mustNot' => array_values($mustNot),
            'optional' => array_values($optional),
        ];
    }

    public function parseQueryWithOperators(string $query): array {
        $pretokenized = $this->_parseQueryWithOperators($query);
        $tokenized = [];
        foreach ($pretokenized['must'] as $pretoken) {
            $tokenized[] = new SearchQueryToken('must', $pretoken);
        }
        foreach ($pretokenized['mustNot'] as $pretoken) {
            $tokenized[] = new SearchQueryToken('mustNot', $pretoken);
        }
        foreach ($pretokenized['optional'] as $pretoken) {
            $tokenized[] = new SearchQueryToken('optional', $pretoken);
        }
        return $tokenized;
    }

    public function tf(string $t, array $d){
        # Get word count of $t in $d
        $wordcount = $this->count_number_of_word_in_string($t, $d['content']);
        # Get the number of words in $d
        $doc_wordcount=$this->count_words_in_string($d['content']);

        # Compute the term frequency
        if ($doc_wordcount===0) {
            return 0.0;
        }
        $tf = $wordcount / $doc_wordcount;
        return $tf;
    }

    public function count_number_of_word_in_string(string $word, string $string) {
        // Use \b for word boundaries, i for case-insensitive
        preg_match_all('/\b' . preg_quote($word, '/') . '\b/i', $string, $matches);

        $count = count($matches[0]);
        return $count;

    }

    public function count_words_in_string(string $string){
        $string = strip_tags($string);
        return str_word_count($string);
    }

    public function count_documents_containing_term(string $t){
        $pages = $this->getPages();
        $pages_with_term_found=[];
        foreach ($pages as $page) {
            if ($this->count_number_of_word_in_string($t, $page['content'])>0) {
                $pages_with_term_found[]=$page;
            }
        }
        $page_count=count($pages_with_term_found);
        return $page_count;
    }

    public function idf(string $t){
        # Get the total number of pages WoofSearchBot crawled
        $crawled_page_count = $this->getTotalPagesCrawled();

        # Get the number of pages containing the term
        $pages_containing_term_count = $this->count_documents_containing_term($t);

        # Compute IDF
        $idf = log($crawled_page_count / (1+$pages_containing_term_count));
        return $idf;
    }

    public function getPages(){
        return $this->db->fetchAll("SELECT * from pages");
    }

    public function tfIdf(string $t, array $d){
        # Multiply TF and IDF to compute TF-IDF
        $tf = $this->tf($t, $d);
        $idf = $this->idf($t);
        $tfIdf = $tf * $idf;
        return $tfIdf;
    }

    public function singleTokenSearch(SearchQueryToken $token, array $page){
        $score = 0;
        if ($token->type == "optional") {  
            $score += $this->tfIdf($token->content, $page);
        } elseif ($token->type == "mustNot") {
            $score -= $this->tfIdf($token->content, $page);
        } elseif ($token->type == "must") {
            $tfIdf = $this->tfIdf($token->content, $page);
            if ($tfIdf <= 0) {
                $score -= $tfIdf + 1000;
            }
        }
        return $score;
    }

    public function _multiTokenSearch(array $tokens, array $page){
        $score = 0;
        foreach ($tokens as $token) {
            $score += $this->singleTokenSearch($token, $page);
        }
        return $score;
    }

    public function multiTokenSearch(array $tokens, int $page_id){
        $crawled_pages = $this->getPages();
        foreach ($crawled_pages as $page) {
            $page['score'] = $this->_multiTokenSearch($tokens, $page);
        }
        usort($crawled_pages, function ($a, $b){
            return $b['score'] <=> $a['score'];
        });
        # Get the results to display
        global $pageResultCount;
        $pages = array_slice($crawled_pages, $page_id*$pageResultCount, $pageResultCount);
        return $pages;
    }
}