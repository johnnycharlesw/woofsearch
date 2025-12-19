<?php
namespace WoofSearch;
class SearchQueryToken {
    public $type;
    public $content;
    
    public function __construct(string $type, string $content) {
        $this->type=$type;
        $this->content=$content;
    }
}