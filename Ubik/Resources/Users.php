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
     */
    function login()
    {
        $data = json_decode($this->request->data);
        $email = $data->email;
        $password = $data->password;

        $dao = new Dao_User($this->container['db']);
        $user = $dao->findByEmailPassword($email, $password);

        if($user!=null){
            try{
                // create a session from the user
                $dao->loginUser($user);
                $params = array(
                    'start' => '/',
                    'prenom' => $user->getPrenom(),
                );

                // return a json response
                $response = new Response(Response::OK);
                $response->contentType = 'application/json';
                $response->body = json_encode($params);
            }
            catch (Exception $e) {
                $response = new Response(Response::BADREQUEST);
                $response->body = $e->getMessage();
                return $response;
            }
            return $response;
        }
        else{
            // return an unauthorized exception (401)
            $response = new Response(Response::UNAUTHORIZED);
            $response->body = 'Accès refusé';
            return $response;
        }
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
