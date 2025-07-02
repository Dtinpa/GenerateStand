<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APILimit extends APIRequestHandler  {

    public function runEndpoint(array $vars) {
        $page = $this->getPage();
        $id = isset($vars['id']) ? $vars['id'] : null;

        return $this->getLimit($id, $page);
    }

    private function getLimit(?string $id, int $page): array {
        $sql = "SELECT `id`, `power_id`, `limit` FROM `Limit`";

        // if an id was supplied, then select for only that id
        $isId = !is_null($id);
        if($isId) {
            $sql .= " WHERE `id` = :id";
        }

        $calcPage = ($page - 1) * $this->numResultsOnPage;
        $sql .= " LIMIT " . $calcPage . ", " . $this->numResultsOnPage;

		$query = $this->mysql->prepare($sql);
        if($isId) {
            $query->bindValue(":id", $id);
        }

		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
		if(!empty($result)) {
			$totalRows = $this->getRowCount("`Limit`");
            $dataSet = $this->buildDataSet($result, $page, $totalRows);
			return $dataSet;
		} else {
			return array();
		}
    }
}

?>