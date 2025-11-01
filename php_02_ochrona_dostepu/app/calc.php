<?php
require_once dirname(__FILE__).'/../config.php';

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['rola'])) {
    header("Location: "._APP_URL."/index.php");
    exit();
}

$kwota = $_REQUEST['kwota'] ?? null;
$lata = $_REQUEST['lata'] ?? null;
$oprocentowanie = $_REQUEST['oprocentowanie'] ?? null;
$messages = [];

if ($kwota === "" || $lata === "" || $oprocentowanie === "") {
    $messages[] = 'Wszystkie pola muszą być wypełnione.';
}

if (empty($messages)) {
    if (!is_numeric($kwota)) $messages[] = 'Kwota musi być liczbą.';
    if (!is_numeric($lata)) $messages[] = 'Okres spłaty musi być liczbą.';
    if (!is_numeric($oprocentowanie)) $messages[] = 'Oprocentowanie musi być liczbą.';
}

if (empty($messages)) {
    $kwota = floatval($kwota);
    $lata = floatval($lata);
    $oprocentowanie = floatval($oprocentowanie);

    if ($kwota <= 0) $messages[] = 'Kwota musi być dodatnia.';
    if ($lata <= 0) $messages[] = 'Okres musi być dodatni.';
    if ($oprocentowanie < 0) $messages[] = 'Oprocentowanie nie może być ujemne.';
}

// Ograniczenia dla pracownika
if (empty($messages) && $_SESSION['rola'] == 'pracownik') {
    if ($kwota > 50000) {
        $messages[] = 'Pracownik nie może wystawiać kredytu powyżej 50 000 zł.';
    }
    if ($oprocentowanie > 10) {
        $messages[] = 'Pracownik nie może ustalać oprocentowania powyżej 10%.';
    }
}

if (empty($messages)) {
    $r_mies = $oprocentowanie / 100 / 12;
    $n = $lata * 12;
    $rata = $r_mies == 0
        ? $kwota / $n
        : ($kwota * $r_mies) / (1 - pow(1 + $r_mies, -$n));
}

include 'calc_view.php';
?>