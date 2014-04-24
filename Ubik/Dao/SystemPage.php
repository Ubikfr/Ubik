<?php
class Dao_SystemPage
{
    private $container;             // Pimple container
    private $tpl;                   // template

    function __construct($url, Utils_Container $c)
    {  
        $this->container = $c;
        $parser = new Utils_PageParser(CONTENT.DS.'_system'.DS.$url.'.md');
        $this->meta = $parser->getMeta();
        $this->content =  $parser->getHtml();
        
        $this->initTemplate();
    }

    public function initTemplate(){
        $this->template = $this->container['smarty'];
        $this->template->assign('titre', $this->meta['titre']);
        $this->template->assign('content', $this->content);
        $this->tpl = $this->meta['template'].'.tpl';

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

        if (isset($this->meta['js'])) {
            $this->template->assign('extra_js', $this->meta['js']);
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
