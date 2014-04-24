<?php

class Utils_RsaCrypt
{
    private $privatekey;
    public $publickey = array();

    public function __construct()
    {
        
    }

    public function to_hex($data)
    {
        return strtoupper(bin2hex($data));
    }

    public function decrypt($ciphertext)
    {
        if (openssl_private_decrypt(pack('H*', $ciphertext), $plaintext, $this->privatekey)) {
            return $plaintext;
        }
        else {
            return null;
        }
    }

    public function loadKey($num)
    {
        $file = file_get_contents(KEYS_DIR.'key_'.$num);
        $this->privatekey = openssl_pkey_get_private($file);
    }

    public function getDetails()
    {
        $details = openssl_pkey_get_details($this->privatekey);
        //module n
        $mod = $this->to_hex($details['rsa']['n']);
        //exposant public e
        $pexp = $this->to_hex($details['rsa']['e']);
        $this->publickey = array('mod'=>$mod,'exp'=>$pexp);
    }
}
