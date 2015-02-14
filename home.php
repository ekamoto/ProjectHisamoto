<?php
session_start('acesso');
if (!isset($_SESSION['id_user'])) {
    die('Acesso Negado!');
}

if($_SESSION['device']!=='Desktop') {
    header("location:home_mobile.php");
}

include 'functions.php';
include 'permission.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Gerenciador</title>
        <meta charset="UTF-8"/>
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
    </head>
    <body style="text-align: left; font-family: monospace;  background-repeat: no-repeat ; background-attachment: fixed; background-size: 100% 100%; margin:0; padding:0;">
        <div id="smoothmenu1" class="ddsmoothmenu shadow">
            <ul>
                <li class="editar_usuario">
                    <a href="#">
                        <?php echo '(', $_SESSION['name'], ')'; echo $admin ? '[ADM]' : ''; ?>
                    </a>
                </li>
                <li>
                    <a href="#">Cadastros</a>
                    <ul>
                        <li class="add_empresa"><a href="#">Empresas</a></li>
                        <li id="id_cadastrar"><a href="#">Conta</a></li>
                        <li class="add_empresa"><a href="#">Empresa</a></li>
                        <?php if ($admin): ?>
                            <li class="add_usuario"><a href="#">Usu&aacute;rios</a></li>
                            <li class="add_grupo"><a href="#">Grupo</a></li>
                        <?php endif; ?>
                        <li class="add_note"><a href="#">Anota&ccedil;&otilde;es</a></li>
                    </ul>
                </li>
                <li class="deslogar"><a href="#">Sair</a></li>
            </ul>
            <br style="clear: left" />
        </div>
        <form id="form_filtro" action="home.php" method="POST" >
            <table cellspacing="0" class="gridtable shadow" >
                <tr >
                    <td>
                        <strong>T&iacute;tulo:</strong>
                    </td>
                    <td colspan="5">
                        <input type="text" id="titulo" name="titulo" value="<?php echo $titulo ? $titulo : ''; ?>" size="30"/>
                        <strong>Per&iacute;odo:</strong>
                        <input type="text" class="data" id="data_inicio" name="data_inicio" value="<?php echo $data_inicio ? $data_inicio : $dados['data_inicio'] = '01/' . date('m/Y'); ?>">
                        <strong>a</strong>
                        <input type="text" class="data" id="data_fim" name="data_fim" value="<?php echo $data_fim ? : $dados['data_fim'] = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) . date('/m/Y'); ?>">
                        <?php //getSelectMes($mes);   ?>
                        <div id="contas_atrasadas"></div>
                        <div id="anotacoes_nao_lidas"></div>
                    </td>
                    <td rowspan="4">
                        <div id="grafico4">
                            <div class="demo-container">
                                <div id="placeholder" class="demo-placeholder" style=" height: 200px;">&nbsp;</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Tipo:</strong>
                    </td>
                    <td>
                        <?php getSelectTipoContaFiltro($dados); ?>
                    </td>
                    <td>
                        <strong>Empresa:</strong>
                    </td>
                    <td>
                        <?php echo getSelectEmpresaFiltro($enterprise_id_filtro); ?>
                    </td>
                    <td>
                        <strong>Ordenado por:</strong>
                    </td>
                    <td>
                        <select id="order_by" name="order_by">
                            <option value="" <?php echo $order_by == '' ? 'selected' : '' ?> >Seleciona uma ordem</option>
                            <option value="title" <?php echo $order_by == 'title' ? 'selected' : '' ?> >T&iacute;tulo</option>
                            <option value="date" <?php echo $order_by == 'date' ? 'selected' : '' ?>>Data</option>
                            <option value="id" <?php echo $order_by == 'id' ? 'selected' : '' ?>>Id</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Usu&aacute;rio:</strong>
                    </td>
                    <td colspan="5">
                        <?php echo getUsuarios($user_expense_id); ?>&nbsp;&nbsp;
                        <input type="radio" name="paga" value="1" <?php if($paga==1 || $paga==='')echo 'checked';?> > Todas
                        <input type="radio" name="paga" value="2" <?php if($paga==2)echo 'checked';?>> Paga
                        <input type="radio" name="paga" value="3" <?php if($paga==3)echo 'checked';?>> Não Paga
                        <?php //echo $paga; ?>
                        &nbsp;&nbsp;
                        <input id="consultar_conta" type="submit" value="Consultar">
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div class="carregando">
                            <img src="img/load3.gif" style="width: 20px; height: 20px;"/><i>Carregando dados...</i>
                        </div>
                        <div id="msg"></div>
                    </td>
                </tr>
            </table>
        </form>
        <br/>
        <ul id="tabs" class="titulos_aba" style="display:none;">
            <li><a href="#" name="#tab1">Contas</a></li>
            <li><a href="#" name="#tab2">Contas Atrasadas</a></li>
            <li><a href="#" name="#tab3">Gr&aacute;ficos</a></li>
            <!--<li><a href="#" name="#tab4">Manual</a></li>-->
            <li><a href="#" name="#tab5">Anota&ccedil;&otilde;es</a></li>
        </ul>
        <div id="content" style="display:none;"> 
            <div id="tab1" >
                <?php include 'view/abas/aba1.php'; ?>
            </div>
            <div id="tab2">
                <?php include 'view/abas/aba2.php'; ?>
            </div>
            <div id="tab3">
                <?php include 'view/abas/aba3.php'; ?>            
            </div>
            <!--
           <div id="tab4" style="background-color: #FFFAF0; width:90%;">
                <?php include 'view/abas/aba4.php'; ?>                
           </div>
            -->
            <div id="tab5">
                <?php include 'view/abas/aba5.php'; ?>                
            </div>
        </div>
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
        <div class="nova_empresa">
            <form id='cadastrar_empresa'>
                <table>
                    <tr>
                        <td>
                            <strong>T&iacute;tulo:</strong>
                        </td>
                        <td>
                            <input type="text" id="titulo_empresa" class="dados_cad_empresa"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Descri&ccedil;&atilde;o:</strong>
                        </td>
                        <td>
                            <input type="text" id="descricao_empresa" class="dados_cad_empresa"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="salvar_empresa" value="Cadastrar"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_cadastro_empresa"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="novo_grupo">
            <form id='cadastrar_grupo'>
                <table>
                    <tr>
                        <td>
                            <strong>T&iacute;tulo:</strong>
                        </td>
                        <td>
                            <input type="text" id="titulo_grupo" class="dados_cad_grupo"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Descri&ccedil;&atilde;o:</strong>
                        </td>
                        <td>
                            <input type="text" id="descricao_grupo" class="dados_cad_grupo"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="salvar_grupo" value="Cadastrar"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_cadastro_grupo"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="mensagem_sistema">
        </div>
        <div class="nova_anotacao">
            <form id='cadastrar_anotacao'>
                <table>
                    <tr>
                        <td>
                            <strong>Descri&ccedil;&atilde;o:</strong>
                        </td>
                        <td>
                            <textarea id="descricao_anotacao" class="dados_cad_anotacao" style="width: 400px; height: 250px; text-align:justify; background-color:#FFFACD;" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Data:</strong>
                        </td>
                        <td>
                            <input type="text" id="data_anotacao" class="dados_cad_anotacao data"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="salvar_anotacao" value="Cadastrar"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_cadastro_anotacao"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="ed_anotacao">
            <form id='editar_anotacao'>
                <table>
                    <tr>
                        <td>
                            <strong>Descri&ccedil;&atilde;o:</strong>
                        </td>
                        <td>
                            <input type="hidden" id="edit_id_note"/>
                            <textarea id="edit_descricao_anotacao" class="dados_edit_anotacao" style="width: 400px; height: 250px; text-align:justify; background-color:#FFFACD; " maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Data:</strong>
                        </td>
                        <td>
                            <input type="text" id="edit_data_anotacao" class="dados_edit_anotacao data"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="edit_anotacao" value="Editar"/>
                            <?php if ($admin): ?>
                            <input type="button" id="gerar_gasto_padrao" value="Gerar gasto padrão"/>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_editar_anotacao"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="novo_usuario">
            <form id='cadastrar_usuario'>
                <table>
                    <tr>
                        <td>
                            <strong>Grupo:</strong>
                        </td>
                        <td>
                            <?php echo getSelectGrupo($id_grupo); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Nome:</strong>
                        </td>
                        <td>
                            <input type="text" id="name" name="name" class="dados_cad_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Login:</strong>
                        </td>
                        <td>
                            <input type="text" id="username" name="username" class="dados_cad_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Senha:</strong>
                        </td>
                        <td>
                            <input type="password" id="password" name="password" class="dados_cad_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Email:</strong>
                        </td>
                        <td>
                            <input type="text" id="email" name="email" class="dados_cad_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Sexo:</strong>
                        </td>
                        <td>
                            <select id="sex" name="sex">
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Idade:</strong>
                        </td>
                        <td>
                            <input type="text" id="age" name="age" class="dados_cad_usuario" onKeyPress="return(SomenteNumero(event))"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tel. celular:</strong>
                        </td>
                        <td>
                            <input type="text" id="tel_cel" name="tel_cel" class="dados_cad_usuario telefone"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tel. resid.:</strong>
                        </td>
                        <td>
                            <input type="text" id="tel_resid" name="tel_resid" class="dados_cad_usuario telefone"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="salvar_usuario" value="Cadastrar"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_cadastro_usuario"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="usuario">
            <form id='editar_usuario'>
                <table>
                    <tr>
                        <td>
                            <strong>Grupo:</strong>
                        </td>
                        <td>
                            <?php echo getSelectGrupoEdit($id_grupo, $admin); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Nome:</strong>
                        </td>
                        <td>
                            <input type="text" id="name_edit" name="name_edit" class="dados_edit_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Login:</strong>
                        </td>
                        <td>
                            <input type="text" id="username_edit" name="username_edit" class="dados_edit_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Senha:</strong>
                        </td>
                        <td>
                            <input type="password" id="password_edit" name="password_edit"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Email:</strong>
                        </td>
                        <td>
                            <input type="text" id="email_edit" name="email_edit" class="dados_edit_usuario"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Sexo:</strong>
                        </td>
                        <td>
                            <select id="sex_edit" name="sex">
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Idade:</strong>
                        </td>
                        <td>
                            <input type="text" id="age_edit" name="age_edit" class="dados_edit_usuario" onKeyPress="return(SomenteNumero(event))"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tel. celular:</strong>
                        </td>
                        <td>
                            <input type="text" id="tel_cel_edit" name="tel_cel_edit" class="dados_edit_usuario telefone"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tel. resid.:</strong>
                        </td>
                        <td>
                            <input type="text" id="tel_resid_edit" name="tel_resid_edit" class="dados_edit_usuario telefone"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Salário:</strong>
                        </td>
                        <td>
                            <input type="text" id="salario_edit" name="salario_edit" class="dados_edit_usuario" onkeypress="return(FormataReais(this, '.', ',', event))"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="edit_usuario" value="Editar"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <div id="msg_edit_usuario"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <script type="text/javascript" src="js/lib/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
        <script type="text/javascript" src="js/lib/jquery.blockUI-2.57.js"></script>
        <script type="text/javascript" src="js/lib/jquery.maskedinput-1.31.js"></script>
        <script type="text/javascript" src="js/Util.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        <script type="text/javascript" src="js/index.js"></script>
        <script type="text/javascript" src="js/view/noteView.js"></script>
        <script type="text/javascript" src="js/data_services/system.dataservice.js"></script>
        <script type="text/javascript" src="js/data_services/dataservice.js"></script>
        <script type="text/javascript" src="js/data_services/paginas/noteDataService.js"></script>
        <script type="text/javascript" src="js/controller/noteController.js"></script>
        <script type="text/javascript" src="js/lib/ui.datepicker-pt-BR.js"></script>
        <script type="text/javascript" src="js/abas.js"></script>
        <script type="text/javascript" src="js/lib/ddsmoothmenu.js"></script>
        <script type="text/javascript" src="js/menu.js"></script>
        <script type="text/javascript" src="js/lib/CodeSeven-toastr-0ad3ca3/toastr.js"></script>
        <script type="text/javascript" src="js/lib/CodeSeven-toastr-0ad3ca3/toastr.min.js"></script>
        <script type="text/javascript" src="js/lib/Hightcharts/highcharts.js"></script>
        <script type="text/javascript" src="js/lib/Hightcharts/modules/exporting.js"></script>
        <script language="javascript" src="js/lib/flot/excanvas.min.js"></script>
        <script language="javascript" src="js/lib/flot/jquery.flot.js"></script>
        <script language="javascript" src="js/lib/flot/jquery.flot.pie.js"></script>
        <script language="javascript" src="js/lib/flot/jquery.flot.pie.min.js"></script>
        <script type="text/javascript" src="js/grafico_aba1.js"></script>
        <script type="text/javascript" src="js/grafico_aba1.2.js"></script>
        <script type="text/javascript" src="js/grafico_aba1.3.js"></script>
        <script type="text/javascript" src="js/grafico_aba1.4.js"></script>
    </body>
</html>