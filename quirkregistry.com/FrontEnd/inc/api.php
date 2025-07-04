<?php
    require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
    require_once dirname(__FILE__) . '/../../../QuirkPHP/DBConn.php';

    if(!isset($_GET["action"])) {
        die;
    }

    $action = $_GET["action"];
    $html = '';
    switch($action) {
        case "getEndpoint":
            if(!isset($_GET["page"])) {
                die;
            }

            $page = $_GET['page'];
            switch($page){
                case 'token':
                    $html = include("token.tpl.php");
                    break;
                case 'activity':
                    $html = include("activity.tpl.php");
                    break;
                case 'weak':
                    $html = include("weak.tpl.php");
                    break;
                case 'action':
                    $html = include("action.tpl.php");
                    break;
                case 'limit':
                    $html = include("limit.tpl.php");
                    break;
                case 'special':
                    $html = include("special.tpl.php");
                    break;
                case 'power':
                    $html = include("power.tpl.php");
                    break;
                case 'body':
                    $html = include("body.tpl.php");
                    break;
                default:
                    break;
            }
        case 'generateKey':
            if(!isset($_GET["email"])) {
                die;
            }

            $email = $_GET['email'];
            if(Validator::validate(array('email' => $email))) {
                $user = new User($config, $mysql);
                $user->generateKey();
                if($user->insertAPIKey($email)) {
                    $client = $user->getAPIKey();
                    $secret = $user->getAPISecret();
                    $html = include("client_secret.tpl.php");
                }
            }
            break;
        default:
            break;
    }

    echo $html;
?>