<?php
class Dao_Post
{
    private $container;             // Pimple container
    private $tpl;                   // Template FILE
    private $repo;                  // Blog Flywhell repository
    private $post = array ();
    
    function __construct(Utils_Container $c)
    {
        $this->container = $c;
        $this->repo = $this->container['blog_repo'];
        $this->getData();
        $this->initTemplate();
    }

    private function getData()
    {
        switch ($this->container['blog']['mode']) {
            case 'post':
                $post = $this->repo->findById($this->container['blog']['post']);
                $content = new Utils_PageContent($post->type, $post->body);
                $this->post[] = array (
                                    'titre' => $post->titre,
                                    'date' => $post->date,
                                    'tag' => $post->tag,
                                    'slug' => $this->container['blog']['post'],
                                    'content' => $content->render()
                                    );
                break;
            case 'page':
                $page = intval($this->container['blog']['page']);
                $nb = intval($this->container['post_par_page']);
                $to = $page*$nb;
                $from = $to+$nb;
                $posts = $this->repo->query()
                                    ->where('status', '==', 'publish')
                                    ->orderBy('date ASC')
                                    ->limit($from,$to)
                                    ->execute();
                if (count($posts)) {
                    $i=0;
                    foreach ($posts as $post) {
                        $content = new Utils_PageContent($post->type, $post->body);
                        $this->post[$i] = array (
                                    'titre' => $post->titre,
                                    'date' => $post->date,
                                    'tag' => $post->tag,
                                    'slug' => $post->getId(),
                                    'content' => $content->render()
                                    );
                        $i++;
                    }
                } else {
                    throw new Tonic\NotFoundException;
                }
                break;
            case 'tag':
                $posts = $this->repo->query()
                                    ->where('status', '==', 'publish')
                                    ->where('tag', '=~', $this->container['blog']['tag'])
                                    ->orderBy('date ASC')
                                    ->execute();
                if (count($posts)) {
                    $i=0;
                    foreach ($posts as $post) {
                        $content = new Utils_PageContent($post->type, $post->body);
                        $this->post[$i] = array (
                                    'titre' => $post->titre,
                                    'date' => $post->date,
                                    'tag' => $post->tag,
                                    'slug' => $post->getId(),
                                    'content' => $content->render()
                                    );
                        $i++;
                    }
                } else {
                    throw new Tonic\NotFoundException;
                }
                break;
            default:
                throw new Tonic\NotFoundException;
                break;
        }
    }

    public function initTemplate()
    {
        $this->template = $this->container['smarty'];
        $this->template->force_compile = true;
        
        if ($this->container['mode'] == 'ajax') {
            $this->tpl = 'blog_ajax.tpl';
        }
        else { // 'html'
            $this->tpl = 'blog.tpl';
        }
           
        // Commun ajax et html
        $this->template->assign('titre', 'Mon blog');
        $this->template->assign('posts', $this->post);
        //print_r($this->post);
//        $this->template->assign('content', $this->content);
//
//        if (isset($this->meta['fields'])) {
//            foreach($this->meta['fields'] as $tag => $text) {
//                $this->template->assign($tag, $text);
//            }
//        }
//        if (isset($this->meta['dao'])) {
//            $class = $this->meta['dao']['class'];
//            $fct = $this->meta['dao']['fct'];
//            $dao = new $class($this->container['db']);
//            $data = $dao->$fct();
//            $this->template->assign($this->meta['dao']['spot'], $data);
//        }
//        if (isset($this->meta['css'])) {
//            $this->template->assign('extra_css', $this->meta['css']);
//        }
//        if (isset($this->meta['script'])) {
//            $script = require $this->dir.$this->meta['script'];
//            $this->template->assign('script', $script);
//        }
//        if ($this->container['loggedIn']) {
//            $this->template->assign('loggedinUser', $this->container['user']);
//        }   
    }

    public function Render()
    {
        return $this->template->fetch($this->tpl);
    }
}
