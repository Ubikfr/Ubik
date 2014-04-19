<?php
class Model_Page extends Model_Base
{
    protected $url;
    protected $title;
    protected $template;
    protected $auth;
    protected $pulish;
    protected $fields;

    /**
     * Get url
     * @return string url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set 
     * @param string $
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get title
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get template
     * @return string template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set template
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Get auth
     * @return string auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set auth
     * @param string $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }
    /**
     * Get publish
     * @return bool publish
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set publish
     * @param bool $publish
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
    }

    /**
     * Get fields
     * @return array fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set fields
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
}
