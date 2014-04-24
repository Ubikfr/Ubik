<?php
use Tonic\Resource;
use Tonic\Response;
use Tonic\ConditionException;

/**
 * Display Index page
 * @uri /
 * @priority 2
 * provides text/html
 */
class IndexPageResource extends Resource
{
    /**
     * @method GET
     */
    function html()
    {
        $session = new Session('html', $this->url, 'public', $this->container);
        $this->container = $session->Check();
        $parser = new Utils_PageParser(CONTENT.DS.'index.md');
        $this->meta = $parser->getMeta();
        $this->content =  $parser->getHtml();
        $dao = new Dao_Page('index', $this->meta, $this->content, $this->container);
        $response = new Response(Response::OK);
        $response->body = $dao->Render();
        return $response;
    }

    /**
     * @method POST
     */
    function wtf()
    {
        $session = new Session('html', $this->url, 'public', $this->container);
        $this->container = $session->Check();
        $parser = new Utils_PageParser(CONTENT.DS.'index.md');
        $this->meta = $parser->getMeta();
        $this->content =  $parser->getHtml();
        $dao = new Dao_Page('index', $this->meta, $this->content, $this->container);
        $response = new Response(Response::OK);
        $response->body = $dao->Render();
        return $response;
    }
}

/**
 * Display Login page
 * @uri /login
 * @priority 2
 */
class LoginPageResource extends Resource
{
    /**
     * @method GET
     */
    function html()
    {
        $session = new Session('login', '/login', 'public', $this->container);
        $this->container = $session->Check();
        $dao = new Dao_SystemPage('login', $this->container);
        $response = new Response(Response::OK);
        $response->body = $dao->Render();
        return $response;
    }
}

/**
 * RSS feed
 * @uri /feed
 * provide text/xml
 * @priority 2
 */
class FeedResource extends Resource
{
    /**
     * @method GET
     */
    function html()
    {
        $dao = new Dao_Feed($this->container);
        $response = new Response(Response::OK, $dao->Render(), array('content-type' => 'text/xml'));
        return $response;
    }
}

/**
 * Send Email
 * @uri /mail
 * @priority 2
 */
class MailResource extends Resource
{
    /**
     * @method POST
     */
    function html()
    {
        $data = json_decode($this->request->data);
        $dao = new Dao_Mail($this->container, $data);
        $errors = $dao->checkData();
        if (count($errors)) {
            $response = new Response(Response::BADREQUEST);
            $response->contentType = 'application/json';
            $response->body = json_encode($errors);
        }
        else {
            //$dao->Send();
            $msg = array('message' => 'Votre message a été envoyé. Merci.');
            $response = new Response(Response::OK);
            $response->contentType = 'application/json';
            $response->body = json_encode($msg);
        }
        
        return $response;
    }
}