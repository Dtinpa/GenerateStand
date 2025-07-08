<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APIRequestRouter  {

    private PDO $mysql;
    private array $config;
    private FastRoute\Dispatcher\GroupCountBased $dispatcher;
    private OAuth2\Server $server;

    function __construct(array $config, PDO $mysql) {
		$this->config = $config;
		$this->mysql = $mysql;

        $this->setupDispatcher();
        $this->setupOAuth();
    }

    private function setupDispatcher( ): void {
        $this->dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/api/index.php/action/{id:\d+}', 'APIAction');
            $r->addRoute('GET', '/api/index.php/activity/{id:\d+}', 'APIActivity');
            $r->addRoute('GET', '/api/index.php/body/{id:\d+}', 'APIBody');
            $r->addRoute('GET', '/api/index.php/power/{id:\d+}', 'APIPower');
            $r->addRoute('GET', '/api/index.php/weak/{id:\d+}', 'APIWeak');
            $r->addRoute('GET', '/api/index.php/limit/{id:\d+}', 'APILimit');
            $r->addRoute('GET', '/api/index.php/special/{id:\d+}', 'APISpecial');
            $r->addRoute('GET', '/api/index.php/action', 'APIAction');
            $r->addRoute('GET', '/api/index.php/activity', 'APIActivity');
            $r->addRoute('GET', '/api/index.php/body', 'APIBody');
            $r->addRoute('GET', '/api/index.php/power', 'APIPower');
            $r->addRoute('GET', '/api/index.php/weak', 'APIWeak');
            $r->addRoute('GET', '/api/index.php/limit', 'APILimit');
            $r->addRoute('GET', '/api/index.php/special', 'APISpecial');
            $r->addRoute('POST', '/api/index.php/token/{id:[a-zA-Z0-9]*}/{secret:[a-zA-Z0-9]*}', 'APIToken');
        });
    }

    private function setUpOauth(): void {
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $this->config['dbhost'], 'username' => $this->config['dbuser'], 'password' => $this->config['dbpass']));

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $this->server = new OAuth2\Server($storage);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
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
        $headers = apache_request_headers();

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);    
	
        $handler = '';
        $vars = array();
        $desc = '';
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                $responseCode = 404;
                $desc = "Invalid endpoint.";
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                $responseCode = 405;
                $desc = "Invalid method supplied.  Valid methods include: " . json_encode($allowedMethods);
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $responseCode = 200;
                break;
        }

        // if we manage to hit an endpoint, only then do we attempt to instantiate the class
        if($handler != '') {
            $class = new $handler($this->config, $this->mysql, $vars);
	        $clientId = array();
	   
            // verify that the token supplied is valid
            if($handler != 'APIToken') {
                if(!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
                    $this->server->getResponse()->send();
                    die;
                }
			
                // if no token was passed in through the headers, exit early
                if(!isset($headers['Authorization']) || $headers['Authorization'] == '') {
                    die;
                }
		
                // if we cant get the access token, we can't continue with the request
                try {
                    $token = explode(" ", $headers['Authorization'])[1];
                } catch(Exception $e) {
                    $responseCode = 400;
                    $desc = "Access Token provided was not parsable.";
                    return array("response_code" => $responseCode, "desc" => $desc);
                }
	
		        $clientId = $class->getClientIdByToken($token);
            } else {
                $clientId['client_id'] = $vars["id"];
                $class->setServer($this->server);
            }

            // want to limit num of requests to 50 a day.  Number in the users table is reset each day
	        $numRequests = $class->getNumOfAPIRequests($clientId['client_id']);
            if($numRequests["num_requests_made"] <= 50 && $numRequests["approved"]) {
                $result = $class->runEndpoint($vars);
                $class->incrementAPIRequests($clientId['client_id']);
                return $result;
            } else {
                $responseCode = 400;
                $desc = "Hit API Rate Limit or are not approved.";
            }
        }

        return array("response_code" => $responseCode, "desc" => $desc);
    }

}

?>
