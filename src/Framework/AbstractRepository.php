<?php

namespace Framework;

abstract class AbstractRepository implements IAbstractRepository
{
    private static $connection;

    /**
     * Instance that 
     *
     * @var AbstractModel $model
     */
    protected $model;

    public function save(array $content, AbstractModel $model = null): AbstractModel
    {
        if (isset($model)) {

            $sets = array_filter(array_map(function ($field) {
                if ($field === $this->model->id() || $field == 'created_at' || $field == 'updated_at') {
                    return;
                }
                return "{$field} = :{$field}";
            }, array_keys($content)));

            if ($this->model->logTimestamp === TRUE) {
                $sets[]       = 'updated_at = :updated_at';
                $content[] = "updated_at = '" . date('Y-m-d H:i:s') . "'";
            }
            $content = array_merge([$this->model->id() => $model->id], $content);
            $sql = "UPDATE {$this->model->table()} SET " . implode(', ', $sets) . " WHERE {$this->model->id()} = :{$this->model->id()};";
        } else {
            if ($this->model->logTimestamp == TRUE) {
                $content['created_at'] = date('Y-m-d H:i:s');
                $content['updated_at'] = date('Y-m-d H:i:s');
            }
            $sql = "INSERT INTO {$this->model->table()} (" . implode(', ', array_keys($content)) . ') VALUES (:' . implode(
                ', :',
                array_keys($content)
            ) . ');';
        }
        if (self::$connection) {
            $db = self::$connection->prepare($sql);
            $db->execute($content);
            // if ($db->rowCount()) {
            $content = $model ? array_merge($model->toArray(), $content) : $content;
            $modelClass = get_class($this->model);
            $model = $model ?? new $modelClass;
            $content[$this->model->id()] = $model->id ?? self::$connection->lastInsertId();
            $model->fromArray($content);
            return  $model;
            // } else {}
        } else {
            throw new \Exception("there is no database connection.", 400);
        }
    }

    public function where($arguments): QueryBuilder
    {
        $obj  = new QueryBuilder($this->table());
        $data = func_get_args();
        return call_user_func_array(array($obj, 'where'), $data);
    }

    public function whereIn($field, $arguments): QueryBuilder
    {
        $obj = new QueryBuilder($this->table);
        $obj->whereIn($field, $arguments);
        return $obj;
    }

    public function find($parameter): ?AbstractModel
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
            throw new \Exception("there is no database connection.", 400);
        }
    }

    public function delete(AbstractModel $model)
    {
        if (isset($model->id)) {

            $sql = "DELETE FROM {$this->model->table()} WHERE {$this->model->id()} = {$model->id};";

            if (self::$connection) {
                return self::$connection->exec($sql);
            } else {
                throw new \Exception("there is no database connection.", 400);
            }
        }
    }

    public function all(string $filter = '', int $limit = 0, int $offset = 0): array
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
            throw new \Exception("there is no database connection.", 400);
        }
    }

    public function count(string $fieldName = '*', string $filter = ''): int
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
            throw new \Exception("there is no database connection.", 400);
        }
    }

    public function findFisrt(string $filter = '')
    {
        return $this->all($filter, 1)[1];
    }

    public static function setConnection(\PDO $connection)
    {
        self::$connection = $connection;
    }

    public function table(): string
    {
        return $this->model->table();
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
