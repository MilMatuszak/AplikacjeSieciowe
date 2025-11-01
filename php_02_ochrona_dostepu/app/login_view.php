<?php require_once dirname(__FILE__) . '/../config.php'; ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Logowanie</title>
</head>
<body>
<h2>Logowanie do systemu</h2>

<form action="<?php print(_APP_URL);?>/app/login.php" method="post">
    <label for="id_login">Login:</label>
    <input id="id_login" type="text" name="login"><br /><br />
    <label for="id_pass">Hasło:</label>
    <input id="id_pass" type="password" name="pass"><br /><br />
    <input type="submit" value="Zaloguj">
</form>

<?php
if (isset($messages) && count($messages) > 0) {
    echo '<ul style="color:red;">';
    foreach ($messages as $msg) {
        echo '<li>'.$msg.'</li>';
    }
    echo '</ul>';
}
?>
</body>
</html>