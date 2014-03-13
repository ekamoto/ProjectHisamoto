<?php
session_start('acesso');
if (isset($_SESSION['id_user'])) {
    session_destroy();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Gerenciador</title>
        <link rel="stylesheet" type="text/css" href="css/login.css" />
    </head>
    <!--<body style="background-image: url('img/fundo3.jpg'); background-repeat: no-repeat; background-size: 100% 100%;  margin:0; padding:0;">-->
    <body style="background-repeat: no-repeat; background-size: 100% 100%;  margin:0; padding:0; ">
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
                <input type="checkbox"><label class="check" for="checkbox">Keep me logged in</label>
                <input type="submit" value="Login" id="logar">
            </div>
            <div style="width: 100%; margin-top: 10%; background-repeat: no-repeat; background-size: 100% 100%; text-align: right;">
                <label><?=date('d/m/Y H:i:s')?>&nbsp;&nbsp;&nbsp;</label>
            </div>
        </div>
        <script type="text/javascript" src="js/lib/jquery-1.9.1pp.js"></script>
        <script type="text/javascript" src="js/logar.js"></script>
    </body>
</html>