<?php

namespace Jurnal;

use Guzzle\HttpClient;
use stdObject;

abstract class AbstractRequest extends HttpClient;
{
    const HTTP_METHOD_GET = "GET";
    const HTTP_METHOD_POST = "POST";
    const HTTP_METHOD_PATCH  = "PATCH";
    const HTTP_METHOD_DELETE = "DELETE";

    protected static $method = self::HTTP_METHOD_GET;
    private $headers = [];

    public function __invoke(array $args = [])
    {
        // Filter arguments
        foreach ($args as $key => $value)
        {
            $this->set($key, $value);
        }

        // Only get public and protected non static variable
        // Use this as request parameters
        $arguments = get_object_vars($this);

        $client = new HttpClient();
        $client->request();
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            $filter = $this->getPropertyClassMethod("get", $name);
            if (is_null($filter)) {
                return $this->{$name};
            }
            return $this->{$filter}();
        } else {
            throw new Exception(sprintf("Properties %s not found", $name));
        }
    }

    public function __set(string $name, $value) : void
    {
        $this->set($name, $value);
    }

    private function set(string $string, $value) : void
    {
        if ($name == "headers") {
            throw new Exception("use addHeader, setHeader, setHeader function to set headers");
        }

        if ($name == "method") {
            throw new Exception("use setMethod function to set method");
        }

        if (property_exists($this, $name)) {
            $filter = $this->getPropertyClassMethod("set", $name);
            if (is_null($filter)) {
                $this->{$name} = $value;
            }
            $this->{$filter}($value);
        } else {
            throw new Exception(sprintf("Properties %s not found", $name));
        }
    }

    private function getPropertyClassMethod($type = "get", $property, $throw_error = true) : ?string
    {
        $method = sprintf("%s%s", $type, implode('', array_map('ucfirst', explode('_', $property))));
        if (method_exists($this, $method)) {
            return $method;
        }
        return null;
    }
}

class getProduct extends AbstractRequest
{
    protected $id;
    protected $paid_only;

}
