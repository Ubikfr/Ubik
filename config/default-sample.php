<?php

return array (
    'pdo' => array(
        'dsn' => 'mysql:host={DB_HOST};dbname={DB_NAME}',
        'username' => '{DB_USER}',
        'password' => '{DB_PASSWORD}',
        'options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        )
    ),
    'smarty' => array(
        'template_dir' => TEMPL_DIR,
        'plug_dir' => TEMPL_DIR.DS.'plugins',
        'temp_dir' => CACHE_DIR,
    ),
    'resources' => array(
        'load' => UBIK_DIR.DS.'Resources/*.php',
        'cache' => new Tonic\MetadataCacheFile(CACHE_DIR.DS.'tonic.cache')
    ),
    'loggedIn' => false,
    'post_par_page' => 4,
    'email' => '{email@dusite}'
);
