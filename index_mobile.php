<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HisamotoSystem</title>

    <!-- Bootstrap core CSS -->
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="" rel="stylesheet">
	</head>
	<body>
		
		<form class="navbar-form navbar-top" onsubmit="return false;">
			<div class="form-group">
			  <input type="text" id="user" name="user" placeholder="Usuário" class="form-control">
			</div>
			<div class="form-group">
			  <input type="password" id="password" name="password" placeholder="Senha" class="form-control">
			  
			</div>
			<button id="logar" class="btn btn-success">Entrar</button>
			<div style="text-align: center; display:none; font-color:#9d9d9d;" id="msg_falha_logar">
			    <a>Usuário ou senha incorretos!</a>
			</div>
		</form>
		<!-- Bootstrap core JavaScript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	    <script type="text/javascript" src="js/logar.js"></script>
	    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	    <script src="node_modules/bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>
