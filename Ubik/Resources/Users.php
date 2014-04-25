<?php
use Tonic\Resource;
use Tonic\Response;
use Tonic\ConditionException;

/**
 * Connexion d'un utilisateur 
 * @uri /api/users/login
 * @priority 2
 */
class UserLoginResource extends SecureResource
{
    /**
     * @method POST
     * @decrypt
     * @respjson
     */
    function login()
    {
        // Toute la partie décryptage est dans Ubik/SecureResource.php
        // fonction appelée par l'annotation @decrypt
        // si $this->request->data == null, le décryptage a foiré ou la requête est vide (??)
        if (!$this->request->data) {
            $response = new Response(Response::UNAUTHORIZED);
            $response->body = 'Accès refusé';
        }
        // sinon on pêut y aller:
        else {
            $email = $this->request->data->email;
            $password = $this->request->data->password;
            $token = $this->request->data->token;

            $dao = new Dao_User($this->container['db']);
            $user = $dao->findByEmailPassword($email, $password);

            if($user!=null){
                try{
                    // Créer la session pour l'utilsateur et enregistrer token
                    $dao->loginUser($user,$token);
                    // Prénom pour dire bonjour et page de redirection
                    $params = array(
                        'start' => '/',
                        'id' => $user->getId(),
                        'nom' => $user->getNom(),
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
}

/**
 * Déconnexion d'un utilisateur 
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
