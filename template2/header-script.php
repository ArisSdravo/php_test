<?php
    require dirname(__FILE__,2).'/vendor/autoload.php';

    include dirname(__FILE__,2).'/connect.php';
    include dirname(__FILE__,2).'/helper_files/GeneralFunctions.php';
    include dirname(__FILE__,2).'/model/Department.php';
    include dirname(__FILE__,2).'/model/Subdepartment.php';
    include dirname(__FILE__,2).'/model/Categories.php';

    // Uncomment for localhost running
    // $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
    // $dotenv->load();

    $MDB_USER = $_ENV['MDB_USER'];
    $MDB_PASS = $_ENV['MDB_PASS'];
    $ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];

    $connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);
    $department = new Department($connection);
    $subdepartment = new Subdepartment($connection);
    $categories = new Categories($connection);
    header('Content-Type: text/html; charset=utf-8');