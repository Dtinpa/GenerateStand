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
}

?>