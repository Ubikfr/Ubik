<?php
use Tonic\Resource;
use Tonic\Response;
use Tonic\ConditionException;

/**
 * Resource de test pour HAMC & co
 * @uri /api/test
 * @priority 2
 */
class TestResource extends SecureResource
{
    /**
     * @method POST
     * @auth
     * @respjson
     */
    function html()
    {
        $text = array( 'text' => 'REQUÊTE LÉGITIME' );
        $response = new Response(Response::OK);
        $response->body = $text;

        return $response;
   }
}
