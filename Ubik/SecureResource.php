<?php
use Tonic\Resource;

class SecureResource extends Resource
{
    function decrypt()
    {
        // Avant de traiter la requête: décodage JSON et décryptage
        $this->before(function ($request) {
            $request->data = json_decode($request->data);
            $blob = $request->data->blob;
            $key = new Utils_RsaCrypt();
            $key->loadKey('001');
            $request->data = json_decode($key->decrypt($blob));
        });
    }

    function auth()
    {
        $this->before(function ($request) {
            // On récupère toutes les données
            $headers = getallheaders();
            $hmac = $headers['X-HMAC'];
            $microtime = floatval($headers['X-MICROTIME']);
            $info = $headers['X-INFO'];
            $referer = $headers['Referer'];
            $my_microtime = microtime(true);
            
            // Délai de 10 secondes max entre requête et la réception
            if (abs($my_microtime - $microtime) > 10){
                throw new Tonic\UnauthorizedException;
            }

            // Décryptage de X-INFO
            $key = new Utils_RsaCrypt();
            $key->loadKey('001');
            $info = explode('$', $key->decrypt($info));
            $email = $info['0'];
            $my_referer = $info['1'];

            // On vérifie si les deux Referer sont dentiques
            if ($my_referer !== $referer){
                throw new Tonic\UnauthorizedException;
            }

            // On charge l'utilisateur et on récupère son token
            $dao = new Dao_User($this->container['db']);
            $user = $dao->findByEmail($email);
            if (($user == null) || ($_SESSION['UserMail'] !== $email)) {
                throw new Tonic\UnauthorizedException;
            }
            else {
                $token = $user->getToken();
            }

            // On reconstitue le HMAC et on vérifie
            $data = $this->request->uri.$this->request->data.$headers['X-MICROTIME'];
            $my_hmac = hash_hmac('sha256', $data, $token);
            if ($my_hmac !== $hmac) {
                throw new Tonic\UnauthorizedException;
            }

            // Si on arrive là c'est que tout va bien alors on décode le JSON
            $request->data = json_decode($request->data);
        });
    }

    function respjson()
    {
        // Avant d'envoyer la réponse: encodage des parmétres en JSON
        $this->after(function ($response) {
            $response->contentType = "application/json";
            $response->body = json_encode($response->body);
        });
    }
}