<?php 

namespace App\Services\Translators\NlpTranslation;

class Response
{
    public static function fromResponse($response)
    {
        $instance = new static();
        $instance->map($response);

        return $instance;
    }

    private function map(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = is_array($value) ? Response::fromResponse($value) : $value;
        }
    }

    public function __get($name)
    {
        return $this->$name;
    }
}