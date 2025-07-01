<?php
    require_once dirname(__FILE__) . '/../../vendor/autoload.php';
    require_once dirname(__FILE__) . '/../../QuirkPHP/DBConn.php';

    $requestRouter = new APIRequestRouter($config, $mysql);
    print_r(json_encode($requestRouter->run()));
?>