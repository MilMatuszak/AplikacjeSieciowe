<?php require_once dirname(__FILE__) .'/../config.php'; ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Kalkulator kredytowy</title>
</head>
<body>

<h2>Kalkulator kredytowy</h2>
<p>Zalogowany jako: <b><?php echo $_SESSION['rola']; ?></b></p>
<p><a href="<?php print(_APP_URL);?>/app/logout.php">Wyloguj</a></p>

<form action="<?php print(_APP_URL);?>/app/calc.php" method="post">
    <label for="id_kwota">Kwota kredytu (zł): </label>
    <input id="id_kwota" type="text" name="kwota" value="<?php echo isset($kwota)?$kwota:''; ?>" /><br /><br />

    <label for="id_lata">Okres spłaty (w latach): </label>
    <input id="id_lata" type="text" name="lata" value="<?php echo isset($lata)?$lata:''; ?>" /><br /><br />

    <label for="id_oproc">Oprocentowanie (%): </label>
    <input id="id_oproc" type="text" name="oprocentowanie" value="<?php echo isset($oprocentowanie)?$oprocentowanie:''; ?>" /><br /><br />

    <input type="submit" value="Oblicz ratę" />
</form>

<?php
if (isset($messages) && count($messages) > 0) {
    echo '<ol style="background-color: #f88; padding: 10px; border-radius: 5px; width:300px;">';
    foreach ($messages as $msg) echo '<li>'.$msg.'</li>';
    echo '</ol>';
}

if (isset($rata)) {
    echo '<div style="background-color: #ff0; padding: 10px; border-radius: 5px; width:300px;">';
    echo 'Miesięczna rata: '.number_format($rata, 2).' zł';
    echo '</div>';
}
?>

</body>
</html>