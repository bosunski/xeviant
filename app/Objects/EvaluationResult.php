<?php

namespace App\Objects;

use JsonSerializable;

class EvaluationResult implements JsonSerializable
{
    /**
     * @var string
     */
    private $result;

    public function __construct(string $result)
    {
        $this->result = $result;
    }

    public function __toString()
    {
        return $this->result;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->__toString();
    }
}
