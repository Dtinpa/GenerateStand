<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APIRequestHandler  {
    protected PDO $mysql;
    protected array $config;
    protected array $vars;

    function __construct(array $config, PDO $mysql, array $vars) {
		$this->config = $config;
		$this->mysql = $mysql;
        $this->vars = $vars;
    }
}

?>