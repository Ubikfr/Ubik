<?php

use \Michelf\MarkdownExtra;

class Utils_PageParser
{
    var $meta;
    var $content;

    public function __construct($file)
    {
        //$source = explode('---', file_get_contents($file));
        $source = preg_split('/[\n]*[-]{3}[\n]/', file_get_contents($file), 3);
        $this->meta = Spyc::YAMLLoad($source[1]);;
        $this->content = $source[2];
    }

    private function filter_p($content){
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }

    public function getMeta(){
        return $this->meta;
    }

    public function getMarkdown(){
        return $this->content;
    }

    public function getHtml(){
        if ($this->meta['content'] == 'markdown'){
            $html = $this->filter_p(MarkdownExtra::defaultTransform($this->content));
        }
        elseif ($this->meta['content'] == 'html') {
            $html = $this->content;
        }
        else {
            $hmtl = null;
        }

        return $html;
    }
}
