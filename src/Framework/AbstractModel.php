<?php

namespace Framework;

abstract class AbstractModel
{
    private $content;
    protected $table;
    protected $idField;
    public $logTimestamp;
    protected $query;
    protected $fillable;
    protected $hidden = [];

    public function __construct()
    {
        if (!is_bool($this->logTimestamp)) {
            $this->logTimestamp = TRUE;
        }

        if (is_null($this->table)) {
            $table       = explode("\\", strtolower(get_class($this)));
            $this->table = array_pop($table);
        }

        if (is_null($this->idField)) {
            $this->idField = 'id';
        }
    }

    public function __set($parameter, $value)
    {
        $this->content[$parameter] = $value;
    }

    public function __get($parameter)
    {
        return $this->content[$parameter];
    }

    public function __isset($parameter)
    {
        return isset($this->content[$parameter]);
    }

    public function __unset($parameter)
    {
        if (isset($parameter)) {
            unset($this->content[$parameter]);
            return true;
        }
        return false;
    }

    private function __clone()
    {
        if (isset($this->content[$this->idField])) {
            unset($this->content[$this->idField]);
        }
    }

    public function toArray(): array
    {
        return array_diff_key($this->content, array_flip($this->hidden));
    }

    public function fromArray(array $array)
    {
        $this->content = $array;
    }

    public function toJson()
    {
        return json_encode($this->content);
    }

    public function fromJson(string $json)
    {
        $this->content = json_decode($json);
    }

    private function format($value)
    {
        if (is_string($value) && !empty($value)) {
            return "'" . addslashes($value) . "'";
        } else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        } else if ($value !== '') {
            return $value;
        } else {
            return "NULL";
        }
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public static function table(): string
    {
        return (new static)->table;
    }

    public static function id()
    {
        return (new static)->idField;
    }

    public static function getRules(int $id = null): array
    {
        return [];
    }
}
