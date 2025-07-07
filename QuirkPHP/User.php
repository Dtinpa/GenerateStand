<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

class User {
    protected PDO $mysql;
    protected array $config;
    protected string $apiKey;
    protected string $apiSecret;

    function __construct(array $config, PDO $mysql) {
		$this->config = $config;
		$this->mysql = $mysql;
    }

    public function getAPIKey(): string {
        return $this->apiKey;
    }

    public function getAPISecret(): string {
        return $this->apiSecret;
    }

    public function generateKey(): void {
        $this->apiKey = bin2hex(random_bytes(32));
        $this->apiSecret = bin2hex(random_bytes(32));
    }

    public function insertAPIKey(string $email): bool {
        $this->mysql->beginTransaction();
        
        $wasInserted = false;
        $sql = "INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES (:testclient, :testpass, 'http://www.generatestand.com');";
        $query = $this->mysql->prepare($sql);
        $query->bindValue(":testclient", $this->apiKey);
        $query->bindValue(":testpass", $this->apiSecret);
        $query->execute();
		
        $oauthClientId = $this->mysql->lastInsertId();
		if(is_string($oauthClientId)) {
            $sql = "INSERT INTO user (`email`, oauth_client_id) VALUES (:email, :oauth_client_id)";
            $query = $this->mysql->prepare($sql);
            $query->bindValue(":email", $email);
            $query->bindValue(":oauth_client_id", $this->apiKey);
            $query->execute();

            $wasInserted = is_string($this->mysql->lastInsertId());

            $this->mysql->commit();
        } else {
            $this->mysql->rollback();
        }

        return $wasInserted;
    }

    public function checkUserExists(string $email) : bool {
        $sql = "SELECT id FROM user WHERE email=:email";
		$query = $this->mysql->prepare($sql);
		$query->bindValue(":email", $email);
		$query->execute();

        if(empty($query->fetch(PDO::FETCH_ASSOC))) {
            return false;
        } else {
            return true;
        }
    }
}

?>