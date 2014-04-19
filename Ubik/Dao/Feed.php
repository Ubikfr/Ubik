<?php
class Dao_Feed
{
    private $container;             // Pimple container
    private $repo;                  // Blog Flywhell repository
    private $post = array ();
    
    function __construct(Utils_Container $c)
    {
        $this->container = $c;
        $this->repo = $this->container['blog_repo'];
        $this->getData();
    }

    private function getData()
    {

        $posts = $this->repo->query()
                            ->where('status', '==', 'publish')
                            ->orderBy('date ASC')
                            ->execute();
        //if (count($posts)) {
            $i=0;
            foreach ($posts as $post) {
                $this->post[$i] = array (
                            'titre' => $post->titre,
                            'date' => $post->date,
                            'tag' => $post->tag,
                            'slug' => $post->getId(),
                            );
                $i++;
            }
//        } else {
//            print 'shit';
//            //throw new Tonic\NotFoundException;
//        }
    }

    public function Render()
    {
        $this->template = $this->container['smarty'];
        $this->template->assign('items', $this->post);

        return $this->template->fetch("feed.tpl");
    }
}
