<?php
require_once dirname(__FILE__).'/../config.php';
session_destroy();
header("Location: "._APP_URL."/index.php");
?>