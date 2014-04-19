<?php
use Tonic\Resource;
use Tonic\Response;
use Tonic\ConditionException;

/**
 * Display Blog
 * @uri /blog
 * @uri /blog/:arg
 * @uri /blog/tag/:tag
 * priority 2
 * @provides text/html
 */
class BlogResource extends Resource
{
    protected $url;
    protected $meta;
    protected $content;
    protected $mode;
    public $container;

    function setup()
    {
        if ($this->arg) {
            preg_match_all('/[^\d]+/', $this->arg, $string);
            preg_match('/\d+/', $this->arg, $integer);
            if (($string['0'] == null) && ($integer['0'] == $this->arg)) {   //arg est un nb => page
                $this->container['blog'] = array('mode' => 'page', 'page' => $this->arg);
            }
            else {  // arg est une chaîne => post
                $file = CONTENT.DS.'blog'.DS.$this->arg.'.md';
                if (file_exists($file)){
                    $this->container['blog'] = array('mode' => 'post', 'post' => $this->arg);
                    $this->mode = 'blog_post';
                }
                else {
                    throw new Tonic\NotFoundException;
                }
            }
        }
        elseif ($this->tag) {
            $tags = explode(',', $this->tag);
            $this->container['blog'] = array('mode' => 'tag', 'tag' => $tags['0']);
            $this->mode = 'tag';
        }
        else {
            if ($this->request->uri == $_SERVER['REQUEST_URI']) { // url = /blog
                $this->container['blog'] = array('mode' => 'page', 'page' => '0');
            }
            else { // url = /blog/ (SEO not found...)
                throw new Tonic\NotFoundException;
            }
        }
    }

    /**
     * @method GET
     */
    function htmlPage()
    {
        $this->container['mode'] = 'html';
        $response = new Response(Response::OK);
        $page = new Dao_Post($this->container);
        $response->body = $page->Render();
        return $response;
    }

    /**
     * @method POST
     */
    function ajaxPage()
    {
        $this->container['mode'] = 'ajax';
        $response = new Response(Response::OK);
        $page = new Dao_Post($this->container);
        $response->body = $page->Render();
        return $response;
    }
}
