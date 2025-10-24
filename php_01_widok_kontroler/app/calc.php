<?php
require_once dirname(__FILE__).'/../config.php';

// 1. Pobranie parametrów
$kwota = $_REQUEST['kwota'] ?? null;
$lata = $_REQUEST['lata'] ?? null;
$oprocentowanie = $_REQUEST['oprocentowanie'] ?? null;

// 2. Walidacja danych
$messages = [];

if (!isset($kwota) || !isset($lata) || !isset($oprocentowanie)) {
    $messages[] = 'Brak jednego z parametrów.';
}

if ($kwota === "") $messages[] = 'Nie podano kwoty kredytu.';
if ($lata === "") $messages[] = 'Nie podano okresu spłaty.';
if ($oprocentowanie === "") $messages[] = 'Nie podano oprocentowania.';

if (empty($messages)) {
    if (!is_numeric($kwota)) $messages[] = 'Kwota musi być liczbą.';
    if (!is_numeric($lata)) $messages[] = 'Okres spłaty (lata) musi być liczbą.';
    if (!is_numeric($oprocentowanie)) $messages[] = 'Oprocentowanie musi być liczbą.';
}

// 3. Obliczenia
if (empty($messages)) {
    $kwota = floatval($kwota);
    $lata = floatval($lata);
    $oprocentowanie = floatval($oprocentowanie);

    if ($kwota <= 0) $messages[] = 'Kwota musi być większa od zera.';
    if ($lata <= 0) $messages[] = 'Okres spłaty musi być większy od zera.';
    if ($oprocentowanie < 0) $messages[] = 'Oprocentowanie nie może być ujemne.';
}

if (empty($messages)) {
    $r_mies = $oprocentowanie / 100 / 12;
    $n = $lata * 12;

    if ($r_mies == 0) {
        $rata = $kwota / $n; // bez odsetek
    } else {
        $rata = ($kwota * $r_mies) / (1 - pow(1 + $r_mies, -$n));
    }
}

// 4. Wywołanie widoku
include 'calc_view.php';
?>