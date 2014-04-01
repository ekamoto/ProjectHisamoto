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
        <style>
            body {
                -webkit-touch-callout: none;                /* prevent callout to copy image, etc when tap to hold */
                -webkit-text-size-adjust: none;             /* prevent webkit from resizing text to fit */
                -webkit-user-select: none;                  /* prevent copy paste, to allow, change 'none' to 'text' */
                background-color:#E4E4E4;
                background-image:linear-gradient(top, #A7A7A7 0%, #E4E4E4 51%);
                background-image:-webkit-linear-gradient(top, #A7A7A7 0%, #E4E4E4 51%);
                background-image:-ms-linear-gradient(top, #A7A7A7 0%, #E4E4E4 51%);
                background-image:-webkit-gradient(
                    linear,
                    left top,
                    left bottom,
                    color-stop(0, #A7A7A7),
                    color-stop(0.51, #E4E4E4)
                    );
                background-attachment:fixed;
                font-family:'HelveticaNeue-Light', 'HelveticaNeue', Helvetica, Arial, sans-serif;
                font-size:12px;
                height:100%;
                margin:0px;
                padding:0px;
                width:100%;
            }
        </style>
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
                <input type="checkbox"><label class="check" for="checkbox">Keep me logged in</label>
                <input type="submit" value="Login" id="logar">
            </div>
            <div style="width: 100%; margin-top: 10%; background-repeat: no-repeat; background-size: 100% 100%; text-align: right;">
                <label><?= date('d/m/Y H:i:s') ?>&nbsp;&nbsp;&nbsp;</label>
            </div>
        </div>
        <script type="text/javascript" src="js/lib/jquery-1.9.1pp.js"></script>
        <script type="text/javascript" src="js/logar.js"></script>
    </body>
</html>