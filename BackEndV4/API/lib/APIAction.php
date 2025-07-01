<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

class APIAction extends APIRequestHandler  {

    public function runEndpoint(array $vars) {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $id = isset($vars['id']) ? $vars['id'] : null;

        return $this->getAction($id);
    }

    private function getAction(?string $id): array {
        $sql = "SELECT `action` FROM `Action`";

        $isId = !is_null($id);
        if($isId) {
            $sql .= " WHERE `id_body_action` = :id";
        }

		$query = $this->mysql->prepare($sql);
        if($isId) {
            $query->bindValue(":id", $id);
        }

		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
				
		if(!empty($result)) {
			return $result;
		} else {
			return array();
		}
    }
}

?>