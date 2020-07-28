<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

try {
    require __DIR__."/vendor/autoload.php";
    session_start();
    $conn = \Framework\Database::getInstance('database');
    \Framework\QueryBuilder::setConnection($conn);
    \Framework\AbstractRepository::setConnection($conn);
    include __DIR__."/routes/routes.php";
    
} catch (\Exception $e) {
    echo $e->getMessage();
}

