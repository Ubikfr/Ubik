<?php

class Utils_Container extends Pimple
{
    public function __construct()
    {
        parent::__construct();
        $this['config'] = include CONF_DIR.'default.php';
        $this->configure();
    }

    protected function configure()
    {
        $c = $this;

        $this['db'] = function () use ($c)
        {
            try {
                $db = new PDO(
                    $c['config']['pdo']['dsn'],
                    $c['config']['pdo']['username'],
                    $c['config']['pdo']['password'],
                    $c['config']['pdo']['options']
                );
                return $db;
            } catch (PDOException $e) {
                error_log('Database connection error in ' . $e->getFile() . ' on line ' . $e->getLine() . ': ' . $e->getMessage());
                die('Database connection error. Please check php error log.');
            }
        };

        $this['smarty'] = function () use ($c)
        {
            $smarty = new Smarty();
            $smarty->setTemplateDir($c['config']['smarty']['template_dir']);
            $smarty->setCompileDir($c['config']['smarty']['temp_dir']);
            $smarty-> setPluginsDir($c['config']['smarty']['plug_dir']);
            return $smarty;
        };

        $this['resources'] = function () use ($c)
        {
            return $c['config']['resources'];
        };

        $this['loggedIn'] = function () use ($c)
        {
            return $c['config']['loggedIn'];
        };

        $this['post_par_page'] = function () use ($c)
        {
            return $c['config']['post_par_page'];;
        };

        $this['email'] = function () use ($c)
        {
            return $c['config']['email'];;
        };

        $this['blog_repo'] = function () use ($c)
        {
            $config = new \JamesMoss\Flywheel\Config('content', array(
                'formatter' => new \JamesMoss\Flywheel\Formatter\Markdown,
            ));
            $repo = new \JamesMoss\Flywheel\Repository('blog', $config);
            return $repo;
        };
    }

    public function load($name, $what) 
    {
        $c = $this;
        $this[$name] = function () use ($c)
        {
            return $what;
        };
    }
}


        /*$this['userDao'] = function () use ($c) {
            return new UserDao($c['db']);
        };

        $this['authAdapter'] = function () use ($c) {
            return new DbAdapter($c['userDao']);
        };*/