<?php
class Widget_Publickey
{
    private $key;
    
    function __construct()
    {
        $this->key = new Utils_RsaCrypt();
        if (!isset($_SESSION['PKEY'])) {
            $this->key->newKey();
            $this->key->storeKey();
        }
    }

    public function Render()
    {
        return $this->key->getDetails();
    }
}
