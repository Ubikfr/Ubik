<?php
use Tonic\Resource;
use Tonic\Response;
use Tonic\ConditionException;

/**
 * Display Page
 * @uri /:page
 * @uri /:page/:subpage
 * @uri /:page/:subpage/:subsubpage
 * @provides text/html
 */
class PageResource extends Resource
{
    protected $url;
    protected $meta;
    protected $content;
    public $container;

    function setup()
    {
        if (!($this->request->uri == '/home')) {
            // 1. Vérifier si l'url est bien formée (pas de /, de .html (...): rien à la fin)
            // 1.bis Vérifier si on demande pas la page d'acceuil via :'url 'index'
            if (str_replace($this->request->uri, '', $_SERVER['REQUEST_URI']) !== '') {
                throw new Tonic\NotFoundException;
            }
            // 2. Récuper page, subpage ...
            $this->url = $this->page;
            if ($this->subpage) { $this->url = $this->url.DS.$this->subpage; }
            if ($this->subsubpage) { $this->url = $this->url.DS.$this->subsubpage; }        
            // 3. Vérifier si la page existe, si oui: charger la config
            $file = CONTENT.DS.$this->url.'.md';
            if (!file_exists($file)){
                throw new Tonic\NotFoundException;
            }
            else {
                $parser = new Utils_PageParser($file);
                $this->meta = $parser->getMeta();
                $this->content =  $parser->getHtml();
            }        
            // 4. Vérifier les droits/Utilisateur
            $session = new Session('page', $this->url, $this->meta['access'], $this->container);
            $this->container = $session->Check();        
            // 5. On plante tout si l'accès n'est pas accordé
            if (!$this->container['access_granted']) {
                throw new Tonic\UnauthorizedException;
            }
        }
    }

    /**
     * @method GET
     */
    function displayPage() 
    {
        $this->container['mode'] = 'html';
        $page = new Dao_Page($this->url, $this->meta, $this->content, $this->container);
        $response = new Response(Response::OK);
        //$response->headers = array('X-HMAC' => '4f4g5g4s7z8r4f1');
        $response->body = $page->Render();
        return $response;
    }

    /**
     * @method POST
     */
    function ajaxPage()
    {
        $response = new Response(Response::OK);
        if ($this->request->uri == '/home') {
            $response->body = 'close';
        } 
        else {
            $this->container['mode'] = 'ajax';
            $page = new Dao_Page($this->url, $this->meta, $this->content, $this->container);
            $response->body = $page->Render();
        }
        return $response;
    }
}
