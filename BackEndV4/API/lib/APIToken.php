<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APIToken extends APIRequestHandler  {

    private OAuth2\Server $server;

    public function setServer(Oauth2\Server $serv): void {
        $this->server = $serv;
    }

    public function runEndpoint($vars) {
        $result = $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
        $return = is_null($result) ? array() : $result;
        return $return;
    }

}

?>