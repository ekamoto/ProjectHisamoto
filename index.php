<?php
session_start('acesso');
if (isset($_SESSION['id_user'])) {
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>HisamotoSystem</title>

    <!-- Bootstrap core CSS -->
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="node_modules/bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">HisamotoSystem</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

          <form class="navbar-form navbar-right" onsubmit="return false;">
            <div class="form-group">
              <input type="text" id="user" name="user" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" id="password" name="password" placeholder="Senha" class="form-control">
              
            </div>
            <button id="logar" class="btn btn-success">Entrar</button>
            <div style="text-align: center; display:none; font-color:#9d9d9d;" id="msg_falha_logar">
                <a>Usuário ou senha incorretos!</a>
            </div>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Gerenciador Financeiro</h1>
        <p>Organize suas contas, planeje antes de efetuar uma compra, tenha o controle total das suas financas.<br>
        Através do Gerenciador você saberá exatamente o valor de suas despesas por período e poderá controllar quais contas foram pagas e as que ainda estão em aberto. O gerenciador conta com gráficos que te auxiliam a avaliar o seu comportamento financeiro, possibilitando uma tomada de decisão antecipada para reduzir ou até evitar prejuizos</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Leia mais &raquo;</a></p>
        <img src="img/sistema.png" style="width: 1000px; height: 500px;"/>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Contas</h2>
          <p>Gerenciamento de contas</p>
          <p><a class="btn btn-default" href="#" role="button">Detalhes &raquo;</a></p>
          <img src="img/financas.jpg" style="width: 200px; height: 200px;"/>
        </div>
        <div class="col-md-4">
          <h2>Anotações</h2>
          <p>Com as anotações é possível organizar mensagens ou lembretes</p>
          <p><a class="btn btn-default" href="#" role="button">Detalhes &raquo;</a></p>
          <img src="img/anotacao.jpg" style="width: 200px; height: 200px;"/>
       </div>
        <div class="col-md-4">
          <h2>Gráficos</h2>
          <p>O sistema possui gráficos para: Total de despesas por mês, Total de despesas separadas por tipo(Parcelada, Fixa e Normal), Total de despesas que estão em débito</p>
          <p><a class="btn btn-default" href="#" role="button">Detalhes &raquo;</a></p>
          <img src="img/graficos.png" style="width: 200px; height: 200px;"/>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->


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