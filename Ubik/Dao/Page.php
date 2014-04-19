<?php
class Dao_Page
{
    private $container;             // Pimple container
    private $config = array();      // Config array from Page Config File
    private $tpl;                   // Template FILE
    
    function __construct($url, $meta, $content, Utils_Container $c)
    {
        $this->container = $c;
        $this->meta = $meta;
        $this->content = $content;
        $this->initTemplate();
    }

    public function initTemplate(){
        $this->template = $this->container['smarty'];
        $this->template->force_compile = true;
        
        if ($this->container['mode'] == 'ajax') {
            $this->tpl = $this->meta['template'].'_ajax.tpl';
        }
        else { // 'html'
            $this->tpl = $this->meta['template'].'.tpl';
            if (isset($this->meta['js'])) {
                $this->template->assign('extra_js', $this->meta['js']);
            }
        }
           
        // Commun ajax et html
        $this->template->assign('titre', $this->meta['titre']);
        $this->template->assign('content', $this->content);

        if (isset($this->meta['fields'])) {
            foreach($this->meta['fields'] as $tag => $text) {
                $this->template->assign($tag, $text);
            }
        }
        if (isset($this->meta['dao'])) {
            $class = $this->meta['dao']['class'];
            $fct = $this->meta['dao']['fct'];
            $dao = new $class($this->container['db']);
            $data = $dao->$fct();
            $this->template->assign($this->meta['dao']['spot'], $data);
        }
        if (isset($this->meta['css'])) {
            $this->template->assign('extra_css', $this->meta['css']);
        }
        
        if (isset($this->meta['script'])) {
            $script = require $this->dir.$this->meta['script'];
            $this->template->assign('script', $script);
        }
        if ($this->container['loggedIn']) {
            $this->template->assign('loggedinUser', $this->container['user']);
        }   
    }

    public function Render()
    {
        return $this->template->fetch($this->tpl);
    }
}
