<?php
use Tonic\Resource;
use Tonic\Response;

/**
 * Display NotFound page
 * @uri /404
 */
class ErrorNotFoundResource extends Resource
{
    /**
     * @method GET
     */
    function html()
    {
        $dao = new Dao_SystemPage('404', $this->container);
        $response = new Response(Response::NOTFOUND);
        $response->body = $dao->Render();
        return $response;
    }
}

/**
 * Display AccesDenied page
 * @uri /403
 */
class ErrorAccessDeniedResource extends Resource
{
    /**
     * @method GET
     */
    function html()
    {
        $dao = new Dao_SystemPage('403', $this->container);
        $response = new Response(Response::FORBIDDEN);
        $response->body = $dao->Render();
        return $response;
    }
}

/**
 * Display AccesDenied page
 * @uri /500
 */
class ErrorServerErrorResource extends Resource
{
    /**
     * @method GET
     */
    function html()
    {
        $dao = new Dao_SystemPage('500', $this->container);
        $response = new Response(Response::INTERNALSERVERERROR);
        $response->body = $dao->Render();
        return $response;
    }
}
