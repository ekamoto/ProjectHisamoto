<?php
session_start('acesso');
if (!isset($_SESSION['id_user'])) {
    die('Acesso Negado!');
}


if (isset($_SESSION['device']) && $_SESSION['device'] !== 'Desktop') {

    include 'functions.php';
    include 'permission.php';
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
            <script type="text/javascript" src="js/lib/jquery-1.9.1pp.js"></script>
            <link href="css/home.css" rel="stylesheet">
            <link href="js/lib/jquery-ui-1.10.3/themes/base/jquery.ui.dialog.css" rel="stylesheet">
            <link href="js/lib/jquery-ui-1.10.3/themes/smoothness/jquerycustom.css" rel="stylesheet">
            <link href="css/index.css" rel="stylesheet">
            <link href="css/abas.css" rel="stylesheet">
            <link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />
            <link rel="stylesheet" type="text/css" href="css/ddsmoothmenu-v.css" />
            <link rel="stylesheet" type="text/css" href="js/lib/CodeSeven-toastr-0ad3ca3/toastr.css" />
            <link rel="stylesheet" type="text/css" href="js/lib/CodeSeven-toastr-0ad3ca3/toastr.min.css" />
            <link rel="stylesheet" type="text/css" href="css/flot.css" />

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
                        <a class="navbar-brand" href="https://leandroekamoto.wordpress.com/leandro-shindi-ekamoto/" target="blanck">HisamotoSystem</a>
                    </div>

                    <div id="navbar" class="navbar-brand collapse">

                        <div class="deslogar">Sair</div>
                    </div>

                </div>
            </nav>

            <!-- Main jumbotron for a primary marketing message or call to action -->
            <div class="jumbotron">
                <div class="container">
                    <h1>Contas</h1>
                    <form id="form_filtro" action="home_mobile.php" method="POST" >
                        Inicio<input type="text" id="data_inicio" name="data_inicio" class="data form-control" value="<?php echo $data_inicio ? $data_inicio : $dados['data_inicio'] = '01/' . date('m/Y'); ?>">
                        Fim<input type="text" id="data_fim" name="data_fim" class="data form-control" value="<?php echo $data_fim ? : $dados['data_fim'] = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) . date('/m/Y'); ?>">
                        <br>
                        <button class="btn btn-primary btn-lg" role="button" id="consultar_conta">Buscar</button>
                        <div class="btn btn-primary btn-lg" id="id_cadastrar">Conta</div>
                        <div class="btn btn-primary btn-lg" role="button">Anotações</div>
                    </form>
                </div>
            </div>

            <div class="container">

                <?php listarContasMobile($dados); ?>


                <footer>
                    <p>&copy; Company 2014</p>
                </footer>
            </div> <!-- /container -->
             <div class="nova_conta">
            <form id='cadastrar_conta'>
                <table>
                    <tr>
                        <td>
                            <strong>Usu&aacute;rio:</strong>
                        </td>
                        <td>
                            <?php echo getSelectUsuarios(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tipo:</strong>
                        </td>
                        <td>
                            <?php echo getSelectTipoConta($dados); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Empresa:</strong>
                        </td>
                        <td>
                            <?php echo getSelectEmpresa($dados); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>T&iacute;tulo:</strong>
                        </td>
                        <td>
                            <input type="text" id="title" name="title" class="dados_cad_conta" size="35" title="T&iacute;tulo"/>
                        </td>
                    </tr>
                    <tr class="quantidade_parcela" style="display: none;">
                        <td>
                            <strong>Qtd.Parc.:</strong>
                        </td>
                        <td>
                            <input type="text" id="qtd_portion" name="qtd_portion" class="dados_cad_conta" title="Quantidade de parcelas" onKeyPress="return(SomenteNumero(event))"/>
                        </td>
                    </tr>
                    <tr class="valor_parcela" style="display: none;">
                        <td>
                            <strong>V.Parc.:</strong>
                        </td>
                        <td>
                            <input type="text" id="portion_value" name="portion_value" class="dados_cad_conta" title="Valor da parcela"  onKeyPress="return(FormataReais(this, '.', ',', event))"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>V.Tot.:</strong>
                        </td>
                        <td>
                            <input type="text" id="total_value" name="total_value" class="dados_cad_conta" title="Valor total da conta" onKeyPress="return(FormataReais(this, '.', ',', event))"/>
                        </td>
                    </tr>
                    <tr class="qtd_parc_pagas" style="display: none;">
                        <td>
                            <strong>Qtd.ParcPag.:</strong>
                        </td>
                        <td>
                            <input type="text" id="qtd_portion_payment" name="qtd_portion_payment" class="" title="Quantidade de parcelas pagas" size="5" onKeyPress="return(SomenteNumero(event))" value="0"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Data:</strong>
                        </td>
                        <td>
                            <input type="text" id="date" name="date" class="data dados_cad_conta" title="Data do vencimento"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Dia.Venc.:</strong>
                        </td>
                        <td>
                            <input type="text" id="expiration_day" name="expiration_day" class="dados_cad_conta" title="Dia do vencimento" size="5" onKeyPress="return(SomenteNumero(event))"/>
                            &nbsp;&nbsp;<strong>Pago:</strong>
                            <input type="checkbox" id="payment" name="payment" class="dados_cad_conta" />
                            &nbsp;&nbsp;
                            <input type="radio" name="modality" checked="checked" value="1" class="modality">&nbsp;Pagar
                            <input type="radio" name="modality" value="2" class="modality">&nbsp;Receber
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Desc.:</strong>
                        </td>
                        <td>
                            <textarea id="description" name="description" class="dados_cad_conta" title="Descri&ccedil;&atilde;o da conta"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="add_conta" value="Cadastrar"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_cadastro_conta"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

            <!-- Bootstrap core JavaScript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
            <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
            <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
            <script src="node_modules/bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
            <script type="text/javascript" src="js/lib/jquery.maskedinput-1.31.js"></script>
            <script type="text/javascript" src="js/lib/jquery-ui-1.10.3/ui/jquery-ui.js"></script>

            <script type="text/javascript" src="js/lib/jquery.blockUI-2.57.js"></script>
            
            <script type="text/javascript" src="js/Util.js"></script>
            <script type="text/javascript" src="js/functions.js"></script>
            <script type="text/javascript" src="js/index.js"></script>
            <script type="text/javascript" src="js/view/noteView.js"></script>
            <script type="text/javascript" src="js/data_services/system.dataservice.js"></script>
            <script type="text/javascript" src="js/data_services/dataservice.js"></script>
            <script type="text/javascript" src="js/data_services/paginas/noteDataService.js"></script>
            <script type="text/javascript" src="js/controller/noteController.js"></script>
            <script type="text/javascript" src="js/lib/ui.datepicker-pt-BR.js"></script>
            <script type="text/javascript" src="js/lib/CodeSeven-toastr-0ad3ca3/toastr.js"></script>
            <script type="text/javascript" src="js/lib/CodeSeven-toastr-0ad3ca3/toastr.min.js"></script>    

            <script type="text/javascript">
                $(function () {
                    $(".data").datepicker();
                    $('.data').mask('99/99/9999');
                    $(".deslogar").on("click", function() {
                        location.href = "index.php";
                    });
                });
            </script>
        </body>
    </html>
    <?php
} else {
    die('Opss');
}