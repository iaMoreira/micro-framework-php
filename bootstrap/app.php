<?php


error_reporting(E_ALL);
ini_set('display_errors', true);

try {
    require __DIR__."/../vendor/autoload.php";

    $conn = \Framework\Database::getInstance('database');
    \Framework\QueryBuilder::setConnection($conn);
    \Framework\AbstractRepository::setConnection($conn);

    $builder = new DI\ContainerBuilder();
    $builder->addDefinitions(__DIR__ . '/container.php');
    $container = $builder->build();

    include __DIR__."/../routes/routes.php";
    
} catch (\Exception $e) {
    echo $e->getMessage();
}

