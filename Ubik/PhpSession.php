<?php

/**
* Handle PHP Sessions
* http://www.wikihow.com/Create-a-Secure-Session-Managment-System-in-PHP-and-MySQL
*/
class PhpSession 
{
    function __construct($session_name) {
       // set custom session functions (ie. write to db.
       //session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
     
       register_shutdown_function('session_write_close');
       $this->start($session_name);
    }

    function start($session_name)
    {
        header_remove("X-Powered-By");
        $httponly = true;
        $secure = false;
        $session_hash = 'sha512';

        if (in_array($session_hash, hash_algos())) {
            ini_set('session.hash_function', $session_hash);
        }

        ini_set('session.hash_bits_per_character', 5);
        ini_set('session.use_only_cookies', 1);
        $cookieParams = session_get_cookie_params(); 
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name);
        session_start();
        session_regenerate_id(true); 
    }
}


//print_r($_SESSION);
//exit;
//header('X-HMAC: 4f4g5g4s7z8r4f1');
//header('X-PKEY: 4f4g5g4s7z8r4f1');
//print(session_id());

// PHP session, Cookies and Headers:
//ini_set('session.use_trans_sid', 0);
//ini_set('session.use_only_cookies', 1);
//header_remove("X-Powered-By");
//session_name('JDB_SESSION');
//session_set_cookie_params ( 3600 , '/', '.localhost' , 0, 0 );
//session_start();