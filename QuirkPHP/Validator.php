<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

class Validator {
    public static function validate(array $vars): bool {
        $isValid = true;
        foreach($vars as $key => $value) {
            switch($key) {
                case "email":
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $isValid = false;
                    }
                    break;
                case "string":
                    if(!is_string($value)) {
                        $isValid = false;
                    }
                    break;
                case "int":
                    if(!is_numeric($value)) {
                        $isValid = false;
                    }
                    break;
                default:
                    $isValid = false;
                    break;
            }
        }

        return $isValid;
    }
}

?>