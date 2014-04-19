<?php

use \Michelf\MarkdownExtra;

class Utils_PageContent
{
    var $type;
    var $content;

    public function __construct($type, $content)
    {
        $this->type = $type;
        $this->content = $content;
        if ($this->type == 'markdown'){
            return $this->filter_p(MarkdownExtra::defaultTransform($this->content));
        }
        elseif ($this->type == 'html') {
            return $this->content;
        }
        else {
            return null;
        }
    }

    private function filter_p($dirty)
    {
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $dirty);
    }

    public function render()
    {
        if ($this->type == 'markdown'){
            return $this->filter_p(MarkdownExtra::defaultTransform($this->content));
        }
        elseif ($this->type == 'html') {
            return $this->content;
        }
        else {
            return null;
        }
    }
}
