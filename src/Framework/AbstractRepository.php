<?php

namespace Framework;

abstract class AbstractRepository
{
    private static $connection;

    /**
     * Instance that 
     *
     * @var AbstractModel $model
     */
    protected $model;

    private function convertContent(array $content)
    {
        $newContent = array();
        foreach ($content as $key => $value) {
            if (is_scalar($value)) {
                $newContent[$key] = $value;
            }
        }
        return $newContent;
    }

    public function save(array $content, AbstractModel $model = null)
    {
        $newContent = $this->convertContent($content);

        if (isset($model)) {

            $sets = array_filter(array_map(function ($field) {
                if ($field === $this->model->id() || $field == 'created_at' || $field == 'updated_at') {
                    return;
                }
                return "{$field} = :{$field}";
            }, array_keys($newContent)));

            if ($this->model->logTimestamp === TRUE) {
                $sets[]       = 'updated_at = :updated_at';
                $newContent[] = "updated_at = '" . date('Y-m-d H:i:s') . "'";
            }
            $newContent = array_merge([$this->model->id() => $model->id], $newContent);
            $sql = "UPDATE {$this->model->table()} SET " . implode(', ', $sets) . " WHERE {$this->model->id()} = :{$this->model->id()};";
        } else {
            if ($this->model->logTimestamp == TRUE) {
                $newContent['created_at'] = date('Y-m-d H:i:s');
                $newContent['updated_at'] = date('Y-m-d H:i:s');
            }
            $sql = "INSERT INTO {$this->model->table()} (" . implode(', ', array_keys($newContent)) . ') VALUES (:' . implode(
                ', :',
                array_keys($newContent)
            ) . ');';
        }
        if (self::$connection) {
            $db = self::$connection->prepare($sql);
            $db->execute($newContent);
            // if ($db->rowCount()) {
            $newContent = $model ? array_merge($model->toArray(), $newContent) : $newContent;
            $modelClass = get_class($this->model);
            $model = $model ?? new $modelClass;
            $newContent[$this->model->id()] = $model->id ?? self::$connection->lastInsertId();
            $model->fromArray($newContent);
            return  $model;
            // } else {}
        } else {
            throw new \Exception("Não há conexão com Banco de dados!");
        }
    }

    public function where($arguments): QueryBuilder
    {
        $obj  = new QueryBuilder($this->table());
        $data = func_get_args();
        return call_user_func_array(array($obj, 'where'), $data);
    }

    public function _whereIn($field, $arguments)
    {

        QueryBuilder::setConnection(self::$connection);
        $obj = new QueryBuilder($this->table);
        $obj->whereIn($field, $arguments);
        return $obj;
    }

    public function _find($parameter): ?AbstractModel
    {
        $sql = 'SELECT * FROM ' . $this->model->table();
        $sql .= ' WHERE ' . $this->model->id();
        $sql .= " = {$parameter} ;";

        if (self::$connection) {
            $result = self::$connection->query($sql);

            if ($result) {
                $newObject = $result->fetchObject(get_class($this->model));
                return $newObject ? $newObject : null;
            }
            return null;
        } else {
            throw new \Exception("Não há conexão com Banco de dados!");
        }
    }

    public function delete(AbstractModel $model)
    {
        if (isset($model->id)) {

            $sql = "DELETE FROM {$this->model->table()} WHERE {$this->model->id()} = {$model->id};";

            if (self::$connection) {
                return self::$connection->exec($sql);
            } else {
                throw new \Exception("Não há conexão com Banco de dados!");
            }
        }
    }

    public function all(string $filter = '', int $limit = 0, int $offset = 0)
    {

        $sql = 'SELECT * FROM ' . $this->model::table();
        $sql .= ($filter !== '') ? " WHERE {$filter}" : "";
        $sql .= ($limit > 0) ? " LIMIT {$limit}" : "";
        $sql .= ($offset > 0) ? " OFFSET {$offset}" : "";
        $sql .= ';';

        if (self::$connection) {
            $result = self::$connection->query($sql);
            return $result->fetchAll(\PDO::FETCH_CLASS, get_class($this->model));
        } else {
            throw new \Exception("Não há conexão com Banco de dados!");
        }
    }

    public static function count(string $fieldName = '*', string $filter = ''): int
    {
        $sql = "SELECT count($fieldName) as t FROM " . self::table();
        $sql .= ($filter !== '') ? " WHERE {$filter}" : "";
        $sql .= ';';
        if (self::$connection) {
            $q = self::$connection->prepare($sql);
            $q->execute();
            $a = $q->fetch(\PDO::FETCH_ASSOC);
            return (int) $a['t'];
        } else {
            throw new \Exception("Não há conexão com Banco de dados!");
        }
    }

    public static function findFisrt(string $filter = '')
    {
        return self::all($filter, 1);
    }

    public static function setConnection(\PDO $connection)
    {
        self::$connection = $connection;
    }

    public function __call($name, $arguments)
    {

        if ($name === 'where') {
            $obj = get_class();
            $obj = new $obj;
            return call_user_func_array(array($obj, '_where'), $arguments);
        }

        if ($name === 'whereIn') {
            $obj = get_class();
            $obj = new $obj;
            return call_user_func_array(array($obj, '_whereIn'), $arguments);
        }

        if ($name === 'destroy') {
            $obj = get_class();
            return $obj::find($arguments[0])->delete();
        }

        if ($name === 'find') {
            $obj = get_class();
            return $obj::_find($arguments[0]);
        }

        if ($name === 'create') {

            $data = $arguments[0];
            if (!is_array($data)) {
                $data = (array) $data;
                unset($data[self::id()]);
            }
            $obj = get_class();
            $obj = new $obj;
            $obj->fromArray($data);
            $obj->save();
            return $obj;
        }
    }

    public static function __callStatic($name, $arguments)
    {

        if ($name === 'where') {

            $obj = new static;
            return call_user_func_array(array($obj, '_where'), $arguments);
        }

        if ($name === 'whereIn') {

            $obj = new static;
            return call_user_func_array(array($obj, '_whereIn'), $arguments);
        }

        if ($name === 'destroy') {

            return self::find($arguments[0])->delete();
        }

        if ($name === 'find') {

            return self::_find($arguments[0]);
        }

        if ($name === 'save') {

            $data = $arguments[0];
            if (!is_array($data)) {
                $data = (array) $data;
                unset($data[self::id()]);
            }
            // $obj = new static;
            // $obj->fromArray($data);
            $obj->save($data);
            return $obj;
        }
    }

    public static function table()
    {
        return (new static)->model::table();
    }

    public function getModel(): AbstractModel
    {
        return $this->model;
    }

    public function query(): QueryBuilder
    {
        $query = new QueryBuilder(self::table());
        return $query;
    }
}
