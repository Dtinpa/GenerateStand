<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APIRequestRouter  {

    private PDO $mysql;
    private array $config;
    private FastRoute\Dispatcher\GroupCountBased $dispatcher;

    function __construct(array $config, PDO $mysql) {
		$this->config = $config;
		$this->mysql = $mysql;

        $this->setupDispatcher();
    }

    private function setupDispatcher( ): void {
        $this->dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/api/index.php/action/{id:\d+}', 'APIAction');
            $r->addRoute('GET', '/api/index.php/activity/{id:\d+}', 'APIActivity');
            $r->addRoute('GET', '/api/index.php/body/{id:\d+}', 'APIBody');
            $r->addRoute('GET', '/api/index.php/power/{id:\d+}', 'APIPower');
            $r->addRoute('GET', '/api/index.php/weak/{id:\d+}', 'APIWeak');
            $r->addRoute('GET', '/api/index.php/action', 'APIAction');
            $r->addRoute('GET', '/api/index.php/activity', 'APIActivity');
            $r->addRoute('GET', '/api/index.php/body', 'APIBody');
            $r->addRoute('GET', '/api/index.php/power', 'APIPower');
            $r->addRoute('GET', '/api/index.php/weak', 'APIWeak');
        });
    }

    public function run(): array {
        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $responseCode = 0;

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);    

        error_log(json_encode($routeInfo));
        $handler = '';
        $vars = array();
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                $responseCode = 404;
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                $responseCode = 405;
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $responseCode = 200;
                break;
        }

        $class = new $handler($this->config, $this->mysql, $vars);
        return $class->runEndpoint($vars);
    }

}

?>