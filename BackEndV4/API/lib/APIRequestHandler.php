<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APIRequestHandler  {
    protected PDO $mysql;
    protected array $config;
    protected array $vars;
    protected int $numResultsOnPage = 10;

    function __construct(array $config, PDO $mysql, array $vars) {
		$this->config = $config;
		$this->mysql = $mysql;
        $this->vars = $vars;
    }

    protected function getPage(): int {
        return isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    }

    protected function getRowCount(string $tableName): string {
        $sql = "SELECT COUNT(*) AS count FROM " . $tableName;
        $query = $this->mysql->prepare($sql);
        $query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
        return $result["count"];
    }

    protected function buildDataSet(array $dataSet, int $currentPage, int $totalRows): array {
        $maxPage = ceil($totalRows / $this->numResultsOnPage);

        // if its the first or last page, then ignore the math on current page and just give either 1 or the max page number
        $prev = ($currentPage - 1) <= 0 ? 1 : $currentPage - 1;
        $next = ($currentPage + 1) >= $maxPage ? $maxPage : $currentPage + 1;
        $data = array();
        $data["data"] = $dataSet;
        $data["meta"] = array("prev page" => $prev, "current page" => $currentPage, "next page" => $next, "max page" => $maxPage);

        return $data;
    }

    // in order to do consistant request tracking, we need to link back to the users oauth client id instead of a temp access token
    public function getClientIdByToken(string $token): array {
        $sql = "SELECT oauth_clients.client_id FROM oauth_clients
                INNER JOIN oauth_access_tokens ON oauth_access_tokens.client_id = oauth_clients.client_id
                WHERE oauth_access_tokens.access_token = :token";
        $query = $this->mysql->prepare($sql);
        $query->bindValue(":token", $token);
        $query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function getNumOfAPIRequests(string $clientId): array {
        $sql = "SELECT num_requests_made, approved FROM user WHERE user.oauth_client_id = :clientId";
        $query = $this->mysql->prepare($sql);
        $query->bindValue(":clientId", $clientId);
        $query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function incrementAPIRequests(string $clientId): bool {
        $sql = "UPDATE user SET num_requests_made = num_requests_made + 1 WHERE oauth_client_id = :clientId";
        $query = $this->mysql->prepare($sql);
        $query->bindValue(":clientId", $clientId);
        return $query->execute();
    }
}

?>