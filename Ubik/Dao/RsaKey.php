<?php
class Dao_RsaKey
{
    private $key;
    
    function __construct(PDO $db)
    {
        //sert à rien mais bon...
        $this->db = $db;
    }

    public function publicKey()
    {
        $this->key = new Utils_RsaCrypt();
        $this->key->loadKey('001');
        
        return $this->key->getDetails();
    }
}
