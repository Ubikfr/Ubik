<?php
class Dao_Page
{
    private $container;             // Conteneur Pimple
    private $tpl;                   // Template
    
    function __construct($url, $meta, $content, Utils_Container $c)
    {
        $this->container = $c;
        $this->meta = $meta;
        $this->content = $content;
        $this->initTemplate();
    }

    public function initTemplate(){
        $this->template = $this->container['smarty'];
        // Décommenter si utilisation AJAX
        //$this->template->force_compile = true;

        // Commun ajax et html
        $this->template->assign('titre', $this->meta['titre']);
        $this->template->assign('content', $this->content);

        if ($this->container['mode'] == 'ajax') {
            $this->tpl = $this->meta['template'].'_ajax.tpl';
        }
        else { // 'html'
            $this->tpl = $this->meta['template'].'.tpl';
            // Chargement du javascript
            if (isset($this->meta['js'])) {
                $this->template->assign('extra_js', $this->meta['js']);
            }
        }

        // Data access objects
        if (isset($this->meta['dao'])) {
            $class = $this->meta['dao']['class'];
            $fct = $this->meta['dao']['fct'];
            $dao = new $class($this->container['db']);
            $data = $dao->$fct();
            $this->template->assign($this->meta['dao']['spot'], $data);
        }

        // Widgets
        if (isset($this->meta['widget'])) {
            foreach ($this->meta['widget'] as $widget) {
                $class = 'Widget_'.$widget;
                $my_widget = new $class();
                $data = $my_widget->Render();
                $this->template->assign($widget, $data);
            }
        }

        // Extra CSS
        if (isset($this->meta['css'])) {
            $this->template->assign('extra_css', $this->meta['css']);
        }

        // Utilisateur connecté
        if ($this->container['loggedIn']) {
            $this->template->assign('loggedinUser', $this->container['user']);
        }   
    }

    public function Render()
    {
        return $this->template->fetch($this->tpl);
    }
}
