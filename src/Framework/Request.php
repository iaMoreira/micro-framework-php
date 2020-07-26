<?php

namespace Framework;

use Framework\File;
use Framework\Validate;

class Request
{
    protected $validate;
    protected $protocol;
    protected $host;
    protected $request;
    protected $uri;
    protected $base;
    protected $method;
    protected $files;

    public function __construct()
    {
        $this->protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $this->host = filter_var(trim($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING));
        $this->request = $_REQUEST;
        $this->base = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $script = explode('/', filter_var(trim($this->base, '/'), FILTER_SANITIZE_URL));
        $uri = explode('/', filter_var(trim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL));
        $this->uri = implode('/', array_diff($uri, $script));
        $this->method = $_SERVER['REQUEST_METHOD'];

        if (count($_FILES) > 0) {
            $this->setFiles();
        }
    }

    protected function setFiles()
    {
        foreach ($_FILES as $key => $value) {

            $this->files[$key] = new File($key);
        }
    }

    public function setRequest($key, $value)
    {
        $this->request[$key] = $value;
    }


    public function base()
    {
        return $this->base;
    }

    public function url()
    {
        return $this->protocol . '://' . $this->host . $this->base;
    }

    public function uriAsString()
    {
        return $this->uri;
    }

    public function uri()
    {
        return explode('/', $this->uri);
    }

    public function method()
    {
        return strtolower($this->method);
    }

    public function join(string $uri)
    {

        $uri = implode('/', explode('/', $uri));

        return $this->url() . $uri;
    }

    public function validate($validation)
    {

        $this->validate = validate()->set($validation);
        return $this;
    }

    public function fails()
    {
        return !$this->validate;
    }

    public function all()
    {
        switch (strtolower($this->method)) {
            case 'post':
                return (array) $_POST;
            case 'get':
                return (array) $_GET;
        }
    }

    public function only($args)
    {

        $args = func_get_args();

        if (count($args) > 0) {

            $result = [];

            foreach ((array) $this->all() as $key => $value) {
                if (in_array($key, $args)) {
                    $result[$key] = $value;
                }
            }
            return (object) $result;
        }
    }


    public function except($args)
    {

        $args = func_get_args();

        if (count($args) > 0) {

            $result = [];

            foreach ((array) $this->all() as $key => $value) {
                if (!in_array($key, $args)) {
                    $result[$key] = $value;
                }
            }
            return (object) $result;
        }
    }

    public function hasFile($key)
    {
        return isset($this->file[$key]);
    }

    public function file($key)
    {
        if ($this->hasFile($key)) {
            return $this->file[$key];
        }
    }

    public function has($key)
    {
        return isset($this->request[$key]);
    }

    public function get($key)
    {
        if (isset($this->request[$key])) {
            return $this->request[$key];
        }
        return false;
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __get($key)
    {
        return $this->get($key);
    }
}
