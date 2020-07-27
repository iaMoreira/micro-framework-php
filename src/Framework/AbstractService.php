<?php

namespace Framework;

abstract class AbstractService implements IAbstractService
{

    /**
     * Instance that 
     *
     * @var AbstractRepository $repository
     */
    protected $repository;

    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get AbstractModel instance
     *
     * @return AbstractModel
     */
    public function getModel(): AbstractModel
    {
        return $this->model;
    }

    /**
     * Find a resource by id
     *
     * @param $id
     * @return AbstractModel|null
     */
    public function findOne(int $id): ?AbstractModel
    {
        return $this->repository->find($id);
    }

    /**
     * Find a resource by id or fail
     *
     * @param $id
     * @return AbstractModel
     * @throws BaseException
     */
    public function findOneOrFail(int $id): AbstractModel
    {
        $model = $this->repository->find($id);
        if (\is_null($model)) {
            throw new \Exception($this->getClassName() . ' not found', 404);
        }

        return $model;
    }

    /**
     * Find a resource by criteria
     *
     * @param array $criteria
     * @return AbstractModel|null
     */
    public function findOneBy(array $criteria): ?AbstractModel
    {
        return $this->repository->where($criteria);
    }

    /**
     * Search All resources by any values of a key
     *
     * @param string $key
     * @param array $values
     * @return Collection
     */
    public function findIn(string $key, array $values)
    {
        return $this->repository->whereIn($key, $values)->get();
    }

    /**
     * Search All resources by criteria
     *
     * @param array $searchCriteria
     * @return Collection
     */
    public function findAll(array $searchCriteria = [])
    {
        return $this->repository->all();
    }

    /**
     * Save a resource
     *
     * @param array $data
     * @return AbstractModel
     */
    public function store(array $data): AbstractModel
    {
        $filledProperties = $this->repository->getModel()->getFillable();
        $keys = array_keys($data);

        foreach ($keys as $key) {
            if (!in_array($key, $filledProperties)) {
                unset($data[$key]);
            }
        }

        $model = $this->repository->save($data);
        return $model;
    }

    /**
     * Update a resource
     *
     * @param integer $id
     * @param array $data
     * @return AbstractModel
     * @throws BaseException
     */
    public function update(int $id, array $data): AbstractModel
    {
        $model = $this->findOneOrFail($id);

        $filledProperties = $this->repository->getModel()->getFillable();
        $keys = array_keys($data);
        foreach ($keys as $key) {
            if (in_array($key, $filledProperties)) {
                $model->$key = $data[$key];
            }
        }

        $model = $this->repository->save($data, $model);
        return $model;
    }

    /**
     * Delete a resource
     *
     * @param integer $id
     * @return bool
     * @throws BaseException
     */
    public function delete(int $id): bool
    {
        $model = $this->findOneOrFail($id);
        return $this->repository->delete($model);
    }

    /**
     * get class name of resource
     *
     * @return string
     */
    protected function getClassName(): string
    {
        $array = explode('\\', get_class($this->repository->getModel()));
        return strtolower(end($array));
    }

    /**
     * get rules of resource
     *
     * @return string
     */
    public function getRules(int $id = null): array
    {
        return $this->repository->getModel()->getRules($id);
    }

}
