<?php

class Utils_RsaCrypt
{
    private $privatekey;
    private $config = array(
            //"digest_alg" => "RSA-SHA512",
            "digest_alg" => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
    public $publickey;

    public function __construct()
    {
        // Si une clef est déjà enregistrée on la charge
        if (isset($_SESSION['PKEY'])) {
            $this->loadKey();
        }
    }

    private function to_hex($data)
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

    private function loadKey()
    {
        $this->privatekey = openssl_pkey_get_private($_SESSION['PKEY']);
    }

    public function getDetails()
    {
        $res = openssl_pkey_get_private($this->privatekey);
        $details = openssl_pkey_get_details($res);
        //module n
        $mod = $this->to_hex($details['rsa']['n']);
        //exposant public e
        $pexp = $this->to_hex($details['rsa']['e']);

        return array('mod'=>$mod,'exp'=>$pexp);
    }

    public function newKey()
    {
        // Création de la pair de clefs
        $res = openssl_pkey_new($this->config);

        // Extraction de la clef privée
        openssl_pkey_export($res, $this->privatekey);

        // Extraction de la clef privée
        $pubKey = openssl_pkey_get_details($res);
        $this->publickey = $pubKey["key"];
    }

    public function storeKey()
    {
        if ($this->privatekey) {
            $_SESSION['PKEY'] = $this->privatekey;
        }
    }
}
