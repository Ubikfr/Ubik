<?php
use Tonic\Resource;
use Tonic\Response;
use Tonic\ConditionException;

/**
 * A protected API call to login a user
 * @uri /api/users/login
 * @priority 2
 */
class UserLoginResource extends Resource
{
    /**
     * @method POST
     * @decrypt
     */
    function login()
    {
        // si $this->request->data == null, le décryptage a foiré
        if (!$this->request->data) {
            $response = new Response(Response::UNAUTHORIZED);
            $response->body = 'Accès refusé';
        }
        // sinon on pêut y aller:
        else {
            $email = $this->request->data->email;
            $password = $this->request->data->password;

            $dao = new Dao_User($this->container['db']);
            $user = $dao->findByEmailPassword($email, $password);

            if($user!=null){
                try{
                    // Créer la session pour l'utilsateur
                    $dao->loginUser($user);
                    // Prénom pour dire bonjour et page de redirection
                    $params = array(
                        'start' => '/',
                        'prenom' => $user->getPrenom(),
                    );
                    $response = new Response(Response::OK);
                    $response->body = $params;
                }
                catch (Exception $e) {
                    $response = new Response(Response::BADREQUEST);
                    $response->body = $e->getMessage();
                }
            }
            else{
                // si $user == null, on refuse
                $response = new Response(Response::UNAUTHORIZED);
                $response->body = 'Accès refusé';
            }
        }

        // Réponse
        return $response;
    }

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

        // Avant d'envoyer la réponse: encodage des parmétres en JSON
        $this->after(function ($response) {
            $response->contentType = "application/json";
            $response->body = json_encode($response->body);
        });
    }
}

/**
 * A protected API call to logout a user
 * @uri /api/users/logout
 * @priority 2
 */
class UserLogoutResource extends Resource
{
    /**
     * @method POST
     */
    function logout()
    {
        $dao = new Dao_User($this->container['db']);
        $user = $dao->findById($_SESSION['UserId']);
        if ($dao->logoutUser($user)) {

            $params = array(
                'start' => '/',
                'prenom' => $user->getPrenom(),
            );

            $response = new Response(Response::OK);
            $response->contentType = 'application/json';
            $response->body = json_encode($params);
        }
        else {
            $response = new Response(Response::BADREQUEST);
            $response->body = 'ça chie dans la colle';
        }

        return $response;
    }
}
