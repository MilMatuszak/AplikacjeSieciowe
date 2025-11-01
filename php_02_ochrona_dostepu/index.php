<?php
require_once dirname(__FILE__).'/config.php';

if (!isset($_SESSION['rola'])) {
    include _ROOT_PATH.'/app/login_view.php';
} else {
    include _ROOT_PATH.'/app/calc_view.php';
}
?>