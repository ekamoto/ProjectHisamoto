<?php
session_start('acesso');
if (isset($_SESSION['id_user'])) {
    session_destroy();
}
// exec('mysqldump -u root -p "" > system'.date('d/m/Y H:i:s').'.sql');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Gerenciador</title>
        <link rel="stylesheet" type="text/css" href="css/login.css" />
    </head>
    <body >
        <div id="container" style="height: 350px;">
            <br/>
            <label for="username">Username:</label>
            <input type="text" id="user" name="user">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <div style="width: 100%; margin-top: 0%; text-align: center; display:none;" id="msg_falha_logar">
                <label>User or Password incorrect . . .</label>
            </div>
            <div id="lower">
                <input type="submit" value="Login" id="logar">
            </div>
        </div>
        <script type="text/javascript" src="js/lib/jquery-1.9.1pp.js"></script>
        <script type="text/javascript" src="js/logar.js"></script>
    </body>
</html>