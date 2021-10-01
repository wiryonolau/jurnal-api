<?php

namespace Jurnal;

use stdClass;
use Exception;

class ApiResponse
{
    protected $properties;

    public function __construct()
    {
        $this->properties = new stdClass();
    }

    public function setProperties(string $json)
    {
        $this->properties = json_decode($json);
    }

    public function setProperty(string $name, $value)
    {
        if (is_object($value) or is_array($value)) {
            $this->properties->{$name} = $value;
        }
        throw new Exception("value must be an object or an array");
    }

    public function __get(string $name)
    {
        return $this->properties->{$name};
    }

    public function getArrayCopy() : array
    {
        return (array) $this->properties;
    }

    public function toJson() : string
    {
        return json_encode($this->properties);
    }
}
