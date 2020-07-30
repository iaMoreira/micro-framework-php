<?php

namespace Framework;

interface IAbstractRepository
{
    public function save(array $content, AbstractModel $model = null): AbstractModel;

    public function find($parameter): ?AbstractModel;

    public function where($arguments): QueryBuilder;

    public function delete(AbstractModel $model);

    public function all(string $filter = '', int $limit = 0, int $offset = 0): array;

    public function count(string $fieldName = '*', string $filter = ''): int;

    public function findFisrt(string $filter = '');
}
