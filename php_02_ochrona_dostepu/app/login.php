<?php
require_once dirname(__FILE__).'/../config.php';

$login = $_REQUEST['login'] ?? null;
$pass = $_REQUEST['pass'] ?? null;
$messages = [];

if (!isset($login) || !isset($pass)) {
    $messages[] = 'Brak loginu lub hasła.';
} else {
    if ($login == "pracownik" && $pass == "pracownik") {
        $_SESSION['rola'] = 'pracownik';
    } else if ($login == "menager" && $pass == "menager") {
        $_SESSION['rola'] = 'menager';
    } else {
        $messages[] = 'Niepoprawny login lub hasło.';
    }
}

if (count($messages) == 0) {
    header("Location: "._APP_URL."/app/calc_view.php");
} else {
    include 'login_view.php';
}
?>