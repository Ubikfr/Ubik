<?php

/**
* Session manager
*/
class Session 
{
    protected $container;
    
    function __construct($mode, $url, $access, Utils_Container $c)
    {
        $this->container = $c;
        $this->container['url'] = $url;
        $this->container['mode'] = $mode;
        $this->container['access'] = $access;
    }

    public function Check()
    {
        $c = $this->container;

        if(isset($_SESSION['UserId']) && isset($_SESSION['Role'])) {
            $this->container['user'] = 
                array(
                    'id' => $_SESSION['UserId'],
                    'role' => $_SESSION['Role'],
                    'email' => $_SESSION['UserMail'],
                    'nom' => $_SESSION['UserNom']
                );
            $this->container['loggedIn'] = true;
        } else {
            $this->container['user'] = 
                array(
                    'id' => 'none',
                    'role' => 'public'
                );
            $this->container['loggedIn'] = false;
        }

        switch ($this->container['access']) {
            case 'public':
                $this->container['access_granted'] = true;
            break;
            case 'private':
                if (($this->container['user']['role'] == 'admin') ||
                    ($this->container['user']['role'] == 'visiteur')) {
                    $this->container['access_granted'] = true;
                } else {
                    $this->container['access_granted'] = false;
                }
            break;
            case 'admin':
                if ($this->container['user']['role'] == 'admin') {
                    $this->container['access_granted'] = true;
                } else {
                    $this->container['access_granted'] = false;
                }
            break;
        }

        return $this->container;
    }
}
