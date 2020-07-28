<?php

namespace Framework;

interface IAbstractService
{
       /**
     * Find a resource by id
     *
     * @param $id
     * @return AbstractModel|null
     */
    public function findOne(int $id): ?AbstractModel;

    /**
     * Find a resource by id
     *
     * @param $id
     * @return AbstractModel
     * @throws BaseException
     */
    public function findOneOrFail(int $id): AbstractModel;
    
    /**
     * Find a resource by criteria
     *
     * @param array $criteria
     * @return AbstractModel|null
     */
    public function findOneBy(array $criteria);

    /**
     * Search All resources by criteria
     *
     * @param array $searchCriteria
     * @return Collection
     */
    public function findAll(array $searchCriteria = []);

    /**
     * Search All resources by any values of a key
     *
     * @param string $key
     * @param array $values
     * @return Collection
     */
    public function findIn(string $key, array $values);

    /**
     * Save a resource
     *
     * @param array $data
     * @return AbstractModel
     */
    public function store(array $data): AbstractModel;

    /**
     * Update a resource
     *
     * @param integer $id
     * @param array $data
     * @return AbstractModel
     * @throws BaseException
     */
    public function update(int $id, array $data): AbstractModel;

    /**
     * Delete a resource
     *
     * @param integer $id
     * @return bool
     * @throws BaseException
     */
    public function delete(int $id): bool;
}