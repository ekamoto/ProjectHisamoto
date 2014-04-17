<?php

if (!isset($_SESSION['id_user'])) {
    session_start('acesso');
}
require_once 'config/configuracoes.php';

function conectar() {
    global $dados_bd;
    try {
        $pdo = new PDO('mysql:host=' . $dados_bd['host'] . ';dbname=' . $dados_bd['dbname'] . ';', $dados_bd['user'], $dados_bd['senha']);
    } catch (PDOException $e) {
        echo 'Falha ao conectar no banco de dados: ' . $e->getMessage();
        die;
    }
    return $pdo;
}

function listarContasId($id) {
    $pdo = conectar();
    $statemente = $pdo->prepare('select * from expenses where id = ? ');
    $statemente->bindParam(1, $id, PDO::PARAM_INT);
    $executa = $statemente->execute();
    if ($executa) {
        echo '<br/>Listagem usando PrepareStatement:<br/>';
        if ($statemente) {
            foreach ($statemente as $value) {
                echo $value['title'] . '<br/>';
            }
        }
    } else {
        echo "Erro ao listar";
    }
}

function getNomeEmpresa($id_empresa) {
    $pdo = conectar();
    $statemente = $pdo->prepare('select title from enterprises where id = ? ');
    $statemente->bindParam(1, $id_empresa, PDO::PARAM_INT);
    $executa = $statemente->execute();
    $titulo = '';
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $titulo = $value['title'];
            }
        }
    }
    return $titulo;
}

function listarAnotacoes($dados) {
    $pdo = conectar();
    $sql = 'select * from notes where notes.group_id = ' . $_SESSION['group_id'];
    if (isset($dados['data_inicio']) && !empty($dados['data_inicio'])) {
        $data_inicio = ajustaData($dados['data_inicio']);
        $sql .= " AND  notes.date_note >= '" . $data_inicio . " 00:00:00'";
    }
    if (isset($dados['data_fim']) && !empty($dados['data_fim'])) {
        $data_fim = ajustaData($dados['data_fim']);
        $sql .= " AND  notes.date_note <= '" . $data_fim . " 23:59:59'";
    }
    $sql .= ' order by id desc';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $cont = 0;
    echo "<table border='1' class='gridtable shadow' style='width:70%'>";
    echo '<tr>
                    <th>
                        <strong>Id</strong>
                    </th>
                    <th>
                        <strong>Desci&ccedil;&atilde;o</strong>
                    </th>
                    <th>
                        <strong>Data</strong>
                    </th>
                    <th>
                        
                    </th>
                  </tr>';
    if ($executa) {
        if ($statemente) {

            foreach ($statemente as $value) {
                $value['date_note'] = ajustaDataPort($value['date_note']);
                $checked = $value['note_read'] === '1' ? 'checked' : '';
                $cont++;
                echo '<tr>';
                echo '<td style="width:3%;">' . $value['id'] . ' </td>';
                echo '<td style="width:60%;">' . $value['description'] . ' </td>';
                echo '<td style="width:10%;">' . $value['date_note'] . ' </td>';
                echo '<td style="width:15%;">'
                . '<input type="checkbox" class="lido" id_note="' . $value['id'] . '" ' . $checked . '> ' .
                '<input type="button" class="editar_note" id_note="' . $value['id'] . '" value="editar">' .
                '<input type="button" class="deletar_note" id_note="' . $value['id'] . '" value="deletar">' .
                '</td>';
                echo '</tr>';
            }
        }
    }
    if ($cont === 0) {
        echo '<tr><td colspan="4">Nenhuma anota&ccedil;&atilde;o registrada!</td></tr>';
    }
    echo '</table>';
}

function getSomaSalarioGrupo() {
    $pdo = conectar();
    $sql = 'SELECT  * 
           FROM   users where group_id = ' . $_SESSION['group_id'];
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            $salario = 0;
            foreach ($statemente as $value) {
                $salario += (float) $value['salary'];
            }
            return $salario;
        }
    }
    return 0;
}

function listarContas($dados) {
    $pdo = conectar();
    $sql = 'SELECT  expenses.* 
            FROM    expenses expenses
            INNER JOIN users users ON (users.id = expenses.user_id) 
            INNER JOIN groups groups ON (groups.id = users.group_id)
            WHERE   expenses.id IS NOT NULL AND users.group_id = ' . $_SESSION['group_id'];
    if (!empty($dados)) {
        if (isset($dados['titulo'])) {
            $dados['titulo'] = trim($dados['titulo']);
            if (!empty($dados['titulo'])) {
                $dados['titulo'] = $dados['titulo'];
                $sql .= " AND expenses.title like '%" . $dados['titulo'] . "%'";
            }
        }
        if (isset($dados['tipo_conta_filtro']) && !empty($dados['tipo_conta_filtro'])) {
            $sql .= " AND  type=" . $dados['tipo_conta_filtro'];
        }
        if (isset($dados['enterprise_id_filtro']) && !empty($dados['enterprise_id_filtro'])) {
            $sql .= " AND  enterprise_id=" . $dados['enterprise_id_filtro'];
        }
        if (isset($dados['data_inicio']) && !empty($dados['data_inicio'])) {
            $data_inicio = ajustaData($dados['data_inicio']);
            $sql .= " AND  date >= '" . $data_inicio . " 00:00:00'";
        }
        if (isset($dados['data_fim']) && !empty($dados['data_fim'])) {
            $data_fim = ajustaData($dados['data_fim']);
            $sql .= " AND  date <= '" . $data_fim . " 23:59:59'";
        }
        if (isset($dados['user_expense_id']) && !empty($dados['user_expense_id'])) {
            $sql .= " AND  users.id = " . $dados['user_expense_id'];
        }
    }
    if (isset($dados['order_by']) && !empty($dados['order_by'])) {
        $order_by = ' order by ' . $dados['order_by'];
    } else {
        $order_by = ' order by title,id';
    }
    $sql .= $order_by;
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            echo "<table border='1' class='gridtable shadow' style='width:70%'>";
            echo '<tr>
                    <th></th>
                    <th>
                        <input type="checkbox" id="seleciona_todos">
                    </th>
                    <th>
                        <strong>Id</strong>
                    </th>
                    <th>
                        <strong>T&iacute;tulo</strong>
                    </th>
                    <th>
                        <strong>Empresa</strong>
                    </th>
                    <th>
                        <strong>QtdParc</strong>
                    </th>
                    <!--
                    <th>
                        <strong>QtParPag</strong>
                    </th>
                    -->
                    <th>
                        <strong>ValTot</strong>
                    </th>
                    <th>
                        <strong>ValPar</strong>
                    </th>
                    <th>
                        <strong>Data</strong>
                    </th>
                    <th>
                        <strong>Pago</strong>
                    </th>
                    <th>
                        <input type="button" id="editar_todos" value="Ed.Todas" style="display:inline;" title="Editar todas as contas selecionadas"><input type="button" id="deletar_todos" value="Del.Todas" style="display:inline;" title="Deletar todas as contas selecionadas">
                    </th>
                  </tr>';
            $cont = 1;
            $total = 0;
            $total_geral = 0;
            $qtd_contas_por_empresas = array();
            foreach ($statemente as $value) {
                if (!isset($qtd_contas_por_empresas[$value['enterprise_id']])) {
                    $qtd_contas_por_empresas[$value['enterprise_id']]['cont'] = 1;
                } else {
                    $qtd_contas_por_empresas[$value['enterprise_id']]['cont'] = $qtd_contas_por_empresas[$value['enterprise_id']]['cont'] + 1;
                }
                $qtd_contas_por_empresas[$value['enterprise_id']]['nome'] = getNomeEmpresa($value['enterprise_id']);
                if ($value['type'] == 1 && $value['payment'] != 1) {
                    $total += $value['portion_value'];
                } else if ($value['payment'] != 1) {
                    $total += $value['total_value'];
                }
                if ($value['type'] == 1) {
                    $total_geral += $value['portion_value'];
                } else {
                    $total_geral += $value['total_value'];
                }
                $value['portion_value'] = str_replace('.', ',', $value['portion_value']);
                $value['total_value'] = str_replace('.', ',', $value['total_value']);
                $botao_editar = "<input type='button' value='Editar' class='editar_conta' id_conta='" . $value['id'] . "' style='display:inline;' title='Editar: " . $value['title'] . "' />";
                $botao_deletar = "<input type='button' value='Deletar' class='deletar_conta' id_conta='" . $value['id'] . "' style='display:inline;' title='Deletar: " . $value['title'] . "'/>";
                $cor_linha = '';
                //1-Parcelado, 2-Fixo, 3-Normal
                $display = '';
                $display2 = '';
                if ($value['type'] == 3) {
                    $display2 = 'none';
                    $display = 'none';
                    $cor_linha = " style='background-color:#98FB98' ";
                } else if ($value['type'] == 1) {
                    $cor_linha = " style='background-color:#E9967A' ";
                } else {
                    $display = $display2 = 'none';
                    $cor_linha = " style='background-color:#ADD8E6' ";
                }

                $value['date'] = ajustaDataPort($value['date']);
                $checked_pago = $value['payment'] ? 'checked' : '';
                echo '<tr id="linha_' . $value['id'] . '">
                        <td ' . $cor_linha . '>
                            <strong>' . $cont . '</strong>
                        </td>
                        <td>
                            <input type="checkbox" id="seleciona_' . $value['id'] . '" value="' . $value['id'] . '" class="seleciona_conta">
                        </td>
                        <td>
                            <input type="text" id="id_' . $value['id'] . '" value="' . $value['id'] . '" size="6" readonly >
                        </td>
                        <td>
                            <input type="text" id="title_' . $value['id'] . '" value="' . $value['title'] . '">
                        </td>
                        <td>
                            ' . getSelectEmpresaList($value['enterprise_id'], $value['id']) . '
                        </td>
                        <td>
                            <input type="text" id="qtd_portion_' . $value['id'] . '" value="' . $value['qtd_portion'] . '" size="6" onKeyPress="return(SomenteNumero(event))" style="display:' . $display . ';">
                        </td>
                        <!--
                        <td>
                            <input type="text" id="qtd_portion_payment_' . $value['id'] . '" value="' . $value['qtd_portion_payment'] . '" size="6" onKeyPress="return(SomenteNumero(event))" style="display:' . $display . ';">
                        </td>
                        -->
                        <td>
                            <input type="text" id="total_value_' . $value['id'] . '" value="' . $value['total_value'] . '" size="6" onKeyPress="return(FormataReais(this, ' . "'.'" . ', ' . "','" . ', event))">
                        </td>
                        <td>
                            <input type="text" id="portion_value_' . $value['id'] . '" value="' . $value['portion_value'] . '" size="6" onKeyPress="return(FormataReais(this, ' . "'.'" . ', ' . "','" . ', event))" style="display:' . $display2 . ';">
                        </td>
                        <td>
                            <input type="text" class="data" id="data_' . $value['id'] . '" value="' . $value['date'] . '" size="21">
                        </td>
                        <td>
                            <!--<input type="text" id="pago_' . $value['id'] . '" value="' . $value['payment'] . '" size="2" onKeyPress="return(SomenteNumero(event))">-->
                            <input type="checkbox" id="pago_' . $value['id'] . '" ' . $checked_pago . '>
                        </td>
                        <td>
                            ' . $botao_editar . $botao_deletar . '
                        </td>
                    </tr>';
                $cont++;
            }
            if ($cont == 1) {
                echo '
                <tr>
                    <td colspan="11" style="text-align:left;">
                        <strong>Nenhum registro encontrado!</strong>
                    </td>
                </tr>';
            }
            $totalSalarios = getSomaSalarioGrupo();
            $sobra = 0;
            $sobra = $totalSalarios - $total_geral;
            echo '
             <tr>
                <td colspan="11" style="text-align:right;">
                    <strong>Entrada(s)[' . $totalSalarios . '] - Saída(s)[' . $total . ']:</strong> 
                        ' . $sobra . '
                </td>
             </tr>
         ';


            $css = "-moz-border-radius:7px;
            -webkit-border-radius:7px;
             border-radius:7px;";
            $titulo_conta_normal = "-S&atilde;o contas pontuais que s&atilde;o pagas a vista.\n-Quando &eacute; feito o cadastro de uma conta desse tipo ele gera uma conta para o m&ecirc;s escolhido.\nEx: presente, l&aacute;pis, etc.";
            $titulo_conta_parcelada = "-S&atilde;o contas que v&atilde;o ser pagas durante uma quantidade 'n' de meses sendo n > 1.\n-Quando &eacute; feito o cadastro de uma conta desse tipo ele gera n contas, come&ccedil;ando pelo m&ecirc;s da data selecionada.\nEx: carro, casa, etc.";
            $titulo_conta_fixa = "-S&atilde;o contas que aparecer&atilde;o todos os meses.\n-Quando &eacute; feito o cadastro de uma conta desse tipo, o sistema gera a mesma conta em todos os meses durante 10 anos, esse n&uacute;mero pode ser alterado pelo administrador do sistema.\nEx: conta de energia, &aacute;gua, etc.";
            echo '
            <tr>
                <td colspan="11" style="text-align:right;">
                    <div style="cursor: help; background-color:#98FB98; width:80px; dislplay:inline; float:left; text-align:center; ' . $css . '" title="' . $titulo_conta_normal . '" ><i>Normal</i></div>
                    <div style="cursor: help; background-color:#E9967A; width:80px; dislplay:inline; float:left; margin-left:10px; text-align:center; ' . $css . '" title="' . $titulo_conta_parcelada . '"><i>Parcelado</i></div>
                    <div style="cursor: help; background-color:#ADD8E6; width:80px; dislplay:inline; float:left; margin-left:10px; text-align:center; ' . $css . '" title="' . $titulo_conta_fixa . '"><i>Fixo</i></div>
                    <strong>Total a Pagar: </strong>' . $total . ' | <strong>Total Geral: </strong> ' . $total_geral . '
                </td>
            </tr>';
            echo '</table>';
            $dados_grafico_4 = '[';
            $dad = array();
            foreach ($qtd_contas_por_empresas as $value) {
                // $dad[] = '["' . $value['nome'] . '", ' . $value['cont'] . ']';
                $dad[] = '{label:"' . $value['nome'] . '", data:' . $value['cont'] . '}';
            }
            if (!empty($dad)) {
                $dados_grafico_4 .= implode(',', $dad) . ']';
            } else {
                $dados_grafico_4 = '[]';
            }
            echo '<script type="text/javascript"> var data_1_grafico_4 = ' . $dados_grafico_4 . '; </script>';
        }
    } else {
        echo "Erro ao listar";
    }
}

function listarContasAtrasadas($dados) {
    $pdo = conectar();
    $sql = 'SELECT  expenses.* 
            FROM    expenses expenses
            INNER JOIN users users ON (users.id = expenses.user_id) 
            INNER JOIN groups groups ON (groups.id = users.group_id)
            WHERE users.group_id = ' . $_SESSION['group_id'] .
            ' AND expenses.date < "' . date('Y-m-d') . '" AND expenses.payment = 0';
    if (!empty($dados)) {
        if (isset($dados['titulo'])) {
            $dados['titulo'] = trim($dados['titulo']);
            if (!empty($dados['titulo'])) {
                $sql .= " AND expenses.title like '%" . $dados['titulo'] . "%'";
            }
        }
        if (isset($dados['tipo_conta_filtro']) && !empty($dados['tipo_conta_filtro'])) {
            $sql .= " AND  type=" . $dados['tipo_conta_filtro'];
        }
        if (isset($dados['enterprise_id_filtro']) && !empty($dados['enterprise_id_filtro'])) {
            $sql .= " AND  enterprise_id=" . $dados['enterprise_id_filtro'];
        }
        if (isset($dados['data_inicio']) && !empty($dados['data_inicio'])) {
            $data_inicio = ajustaData($dados['data_inicio']);
            $sql .= " AND  date >= '" . $data_inicio . " 00:00:00'";
        }
        if (isset($dados['data_fim']) && !empty($dados['data_fim'])) {
            $data_fim = ajustaData($dados['data_fim']);
            $sql .= " AND  date <= '" . $data_fim . " 23:59:59'";
        }
        if (isset($dados['user_expense_id']) && !empty($dados['user_expense_id'])) {
            $sql .= " AND  users.id = " . $dados['user_expense_id'];
        }
    }
    if (isset($dados['order_by']) && !empty($dados['order_by'])) {
        $order_by = ' order by ' . $dados['order_by'];
    } else {
        $order_by = ' order by title,id';
    }
    $sql .= $order_by;
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            echo "<table border='1' class='gridtable shadow' style='width:70%'>";
            echo '<tr>
                    <th></th>
                    <th>
                        <input type="checkbox" id="seleciona_todos_contas_atrasadas">
                    </th>
                    <th>
                        <strong>Id</strong>
                    </th>
                    <th>
                        <strong>T&iacute;tulo</strong>
                    </th>
                    <th>
                        <strong>Empresa</strong>
                    </th>
                    <th>
                        <strong>QtdParc</strong>
                    </th>
                    <!--
                    <th>
                        <strong>QtParPag</strong>
                    </th>
                    -->
                    <th>
                        <strong>ValTot</strong>
                    </th>
                    <th>
                        <strong>ValPar</strong>
                    </th>
                    <th>
                        <strong>Data</strong>
                    </th>
                    <th>
                        <strong>Pago</strong>
                    </th>
                    <th>
                        <input type="button" id="editar_todos_conta_atrasada" value="Ed.Todas" style="display:inline;" title="Editar todas as contas selecionadas"><input type="button" id="deletar_todos_conta_atrasada" value="Del.Todas" style="display:inline;" title="Deletar todas as contas selecionadas">
                    </th>
                  </tr>';
            $cont = 1;
            $total = 0;
            $total_geral = 0;
            foreach ($statemente as $value) {
                if ($value['type'] == 1) {
                    $total += $value['portion_value'];
                } else {
                    $total += $value['total_value'];
                }
                $value['portion_value'] = str_replace('.', ',', $value['portion_value']);
                $value['total_value'] = str_replace('.', ',', $value['total_value']);
                $botao_editar = "<input type='button' value='Editar' class='editar_conta_atrasada' id_conta='" . $value['id'] . "' style='display:inline;' title='Editar: " . $value['title'] . "'/>";
                $botao_deletar = "<input type='button' value='Deletar' class='deletar_conta_atrasada' id_conta='" . $value['id'] . "' style='display:inline;' title='Deletar: " . $value['title'] . "'/>";
                $value['date'] = ajustaDataPort($value['date']);
                $checked_pago = $value['payment'] ? 'checked' : '';
                //1-Parcelado, 2-Fixo, 3-Normal
                $display = '';
                $display2 = '';
                if ($value['type'] == 3) {
                    $display2 = 'none';
                    $display = 'none';
                    $cor_linha = " style='background-color:#98FB98' ";
                } else if ($value['type'] == 1) {
                    $cor_linha = " style='background-color:#E9967A' ";
                } else {
                    $display = $display2 = 'none';
                    $cor_linha = " style='background-color:#ADD8E6' ";
                }
                echo '<tr id="linha_' . $value['id'] . '_atrasada">
                        <td ' . $cor_linha . '>
                            <strong>' . $cont . '</strong>
                        </td>
                        <td>
                            <input type="checkbox" id="seleciona_atrasada_' . $value['id'] . '" value="' . $value['id'] . '" class="seleciona_conta_atrasada">
                        </td>
                        <td>
                            <input type="text" id="id_atrasada_' . $value['id'] . '" value="' . $value['id'] . '" size="6">
                        </td>
                        <td>
                            <input type="text" id="title_atrasada_' . $value['id'] . '" value="' . $value['title'] . '">
                        </td>
                        <td>
                            ' . getSelectEmpresaListContaAtrasada($value['enterprise_id'], $value['id']) . '
                        </td>
                        <td>
                            <input type="text" id="qtd_portion_atrasada_' . $value['id'] . '" value="' . $value['qtd_portion'] . '" size="6" onKeyPress="return(SomenteNumero(event))" style="display:' . $display . ';">
                        </td>
                        <!--
                        <td>
                            <input type="text" id="qtd_portion_payment_atrasada_' . $value['id'] . '" value="' . $value['qtd_portion_payment'] . '" size="6" onKeyPress="return(SomenteNumero(event))" style="display:' . $display . ';">
                        </td>
                        -->
                        <td>
                            <input type="text" id="total_value_atrasada_' . $value['id'] . '" value="' . $value['total_value'] . '" size="6" onKeyPress="return(FormataReais(this, ' . "'.'" . ', ' . "','" . ', event))">
                        </td>
                        <td>
                            <input type="text" id="portion_value_atrasada_' . $value['id'] . '" value="' . $value['portion_value'] . '" size="6" onKeyPress="return(FormataReais(this, ' . "'.'" . ', ' . "','" . ', event))" style="display:' . $display2 . ';">
                        </td>
                        <td>
                            <input type="text" class="data" id="data_atrasada_' . $value['id'] . '" value="' . $value['date'] . '" size="21">
                        </td>
                        <td>
                            <!--<input type="text" id="pago_atrasada_' . $value['id'] . '" value="' . $value['payment'] . '" size="2" onKeyPress="return(SomenteNumero(event))">-->
                            <input type="checkbox" id="pago_atrasada_' . $value['id'] . '" ' . $checked_pago . '>
                        </td>
                        <td>
                            ' . $botao_editar . $botao_deletar . '
                        </td>
                    </tr>';

                $total_geral = $total;
                $cont++;
            }
            if ($cont == 1) {
                echo '
                <tr>
                    <td colspan="11" style="text-align:left;">
                        <strong>Nenhum registro encontrado!</strong>
                    </td>
                </tr>';
            }
            $css = "-moz-border-radius:7px;
            -webkit-border-radius:7px;
             border-radius:7px;";
            $titulo_conta_normal = "-S&atilde;o contas pontuais que s&atilde;o pagas a vista.\n-Quando &eacute; feito o cadastro de uma conta desse tipo ele gera uma conta para o m&ecirc;s escolhido.\nEx: presente, l&aacute;pis, etc.";
            $titulo_conta_parcelada = "-S&atilde;o contas que v&atilde;o ser pagas durante uma quantidade 'n' de meses sendo n > 1.\n-Quando &eacute; feito o cadastro de uma conta desse tipo ele gera n contas, come&ccedil;ando pelo m&ecirc;s da data selecionada.\nEx: carro, casa, etc.";
            $titulo_conta_fixa = "-S&atilde;o contas que aparecer&atilde;o todos os meses.\n-Quando &eacute; feito o cadastro de uma conta desse tipo, o sistema gera a mesma conta em todos os meses durante 10 anos, esse n&uacute;mero pode ser alterado pelo administrador do sistema.\nEx: conta de energia, &aacute;gua, etc.";
            echo '
            <tr>
                <td colspan="11" style="text-align:right;">
                    <div style="cursor: help; background-color:#98FB98; width:80px; dislplay:inline; float:left; text-align:center; ' . $css . '" title="' . $titulo_conta_normal . '" ><i>Normal</i></div>
                    <div style="cursor: help; background-color:#E9967A; width:80px; dislplay:inline; float:left; margin-left:10px; text-align:center; ' . $css . '" title="' . $titulo_conta_parcelada . '"><i>Parcelado</i></div>
                    <div style="cursor: help; background-color:#ADD8E6; width:80px; dislplay:inline; float:left; margin-left:10px; text-align:center; ' . $css . '" title="' . $titulo_conta_fixa . '"><i>Fixo</i></div>
                    <strong>Total a Pagar: </strong>' . $total . ' | <strong>Total Geral: </strong> ' . $total_geral . '
                </td>
            </tr>';
            echo '</table>';
        }
    } else {
        echo "Erro ao listar";
    }
}

function getSelectTipoContaFiltro($dados) {
    $tipo_conta = isset($dados['tipo_conta_filtro']) ? $dados['tipo_conta_filtro'] : 0;
    $selected = array();
    $selected[0] = '';
    $selected[1] = '';
    $selected[2] = '';
    $selected[3] = '';
    $selected[$tipo_conta] = 'selected';
    echo '<select id="tipo_conta_filtro" name="tipo_conta_filtro" >
            <option value="" ' . $selected[0] . '>Selecione tipo da conta</option>
            <option value="1" ' . $selected[1] . ' >Parcelado</option>
            <option value="2" ' . $selected[2] . ' >Fixo</option>
            <option value="3" ' . $selected[3] . ' >Normal</option>
          </select>';
}

function getSelectMes($mes) {
    $selected = array();
    $selected[0] = '';
    $selected[1] = '';
    $selected[2] = '';
    $selected[3] = '';
    $selected[4] = '';
    $selected[5] = '';
    $selected[6] = '';
    $selected[7] = '';
    $selected[8] = '';
    $selected[9] = '';
    $selected[10] = '';
    $selected[11] = '';
    $selected[12] = '';
    $selected[$mes] = 'selected';
    echo '<select id="mes" name="mes" class="dados_cad_conta">
            <option value="" ' . $selected[0] . '>Selecione um m&ecirc;s</option>
            <option value="1" ' . $selected[1] . ' >Janeiro</option>
            <option value="2" ' . $selected[2] . ' >Fevereiro</option>
            <option value="3" ' . $selected[3] . ' >Mar&ccedil;o</option>
            <option value="4" ' . $selected[4] . ' >Abril</option>
            <option value="5" ' . $selected[5] . ' >Maio</option>
            <option value="6" ' . $selected[6] . ' >Junho</option>
            <option value="7" ' . $selected[7] . ' >Julho</option>
            <option value="8" ' . $selected[8] . ' >Agosto</option>
            <option value="9" ' . $selected[9] . ' >Setembro</option>
            <option value="10" ' . $selected[10] . ' >Outubro</option>
            <option value="11" ' . $selected[11] . ' >Novembro</option>
            <option value="12" ' . $selected[12] . ' >Dezembro</option>
          </select>';
}

function getSelectTipoConta($dados) {
    $tipo_conta = isset($dados['tipo_conta']) ? $dados['tipo_conta'] : 0;
    $selected = array();
    $selected[0] = '';
    $selected[1] = '';
    $selected[2] = '';
    $selected[3] = '';
    $selected[$tipo_conta] = 'selected';
    echo '<select id="tipo_conta" name="tipo_conta" class="dados_cad_conta">
            <option value="" ' . $selected[0] . '>Selecione tipo da conta</option>
            <option value="1" ' . $selected[1] . ' >Parcelado</option>
            <option value="2" ' . $selected[2] . ' >Fixo</option>
            <option value="3" ' . $selected[3] . ' >Normal</option>
          </select>';
}

function getSelectEmpresaFiltro($enterprise_id_filtro) {
    $pdo = conectar();
    $sql = 'SELECT  id, title 
            FROM    enterprises 
            WHERE   group_id = ' . $_SESSION['group_id'] .
            ' ORDER BY title';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar empresas...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="enterprise_id_filtro" name="enterprise_id_filtro">';
            $html_select .= '<option value="">Selecione uma empresa</option>';
            foreach ($statemente as $value) {
                $selected = '';
                if ($enterprise_id_filtro == $value['id']) {
                    $selected = ' selected ';
                }
                $html_select .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['title'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getSelectGrupoEdit($id_grupo, $admin) {
    $pdo = conectar();
    $sql = 'SELECT  id, description
            FROM    groups 
            ORDER BY description';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar empresas...';
    if ($executa) {
        if ($statemente) {
            $disabled = '';
            if (!$admin) {
                $disabled = 'disabled="disabled"';
            }
            $html_select = '<select id="group_id_edit" name="group_id_edit" ' . $disabled . '>';
            $html_select .= '<option value="">Selecione um grupo</option>';
            foreach ($statemente as $value) {
                $selected = '';
                if ($id_grupo == $value['id']) {
                    $selected = ' selected ';
                }
                $html_select .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['description'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getSelectGrupo($id_grupo) {
    $pdo = conectar();
    $sql = 'SELECT  id, description
            FROM    groups 
            ORDER BY description';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar empresas...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="group_id" name="group_id">';
            $html_select .= '<option value="">Selecione um grupo</option>';
            foreach ($statemente as $value) {
                $selected = '';
                if ($id_grupo == $value['id']) {
                    $selected = ' selected ';
                }
                $html_select .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['description'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getUsuarios($user_expense_id) {
    $pdo = conectar();
    $sql = 'SELECT  id, name
            FROM    users 
            WHERE   group_id = ' . $_SESSION['group_id'] .
            ' ORDER BY name';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar empresas...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="user_expense_id" name="user_expense_id">';
            $html_select .= '<option value="">Selecione um usu&aacute;rio</option>';
            foreach ($statemente as $value) {
                $selected = '';
                if ($user_expense_id == $value['id']) {
                    $selected = ' selected ';
                }
                $html_select .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getSelectEmpresa($dados) {
    $pdo = conectar();
    $sql = 'SELECT  enterprises.id, enterprises.title 
            FROM    enterprises enterprises
            WHERE   enterprises.group_id = ' . $_SESSION['group_id'] .
            ' ORDER BY enterprises.title';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'N&atilde;o existe empresa cadastrada...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="enterprise_id" name="enterprise_id" class="dados_cad_conta">';
            $html_select .= '<option>Selecione uma empresa</option>';
            foreach ($statemente as $value) {
                $html_select .= '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getSelectEmpresaList($id_empresa, $id) {
    $pdo = conectar();
    $sql = 'select id, title from enterprises where id is not null 
            and  group_id = ' . $_SESSION['group_id'] . '
            order by title';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar empresas...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="enterprise_id_list_' . $id . '" name="enterprise_id_lis_' . $id . '">';
            $html_select .= '<option>Selecione uma empresa</option>';
            foreach ($statemente as $value) {
                $selected = '';
                if ($id_empresa == $value['id']) {
                    $selected = ' selected ';
                }
                $html_select .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['title'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getSelectEmpresaListContaAtrasada($id_empresa, $id) {
    $pdo = conectar();
    $sql = 'select id, title from enterprises where id is not null 
            and group_id = ' . $_SESSION['group_id'] . '
            order by title';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar empresas...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="enterprise_id_list_atrasada_' . $id . '" name="enterprise_id_list_atrasada_' . $id . '">';
            $html_select .= '<option>Selecione uma empresa</option>';
            foreach ($statemente as $value) {
                $selected = '';
                if ($id_empresa == $value['id']) {
                    $selected = ' selected ';
                }
                $html_select .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['title'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getSelectUsuarios() {
    $pdo = conectar();
    $sql = 'SELECT  users.id, users.name 
            FROM    users users 
            INNER JOIN groups groups on (groups.id = users.group_id)
            WHERE  users.group_id = ' . $_SESSION['group_id'];
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    $html_select = 'Falha ao carregar usuários...';
    if ($executa) {
        if ($statemente) {
            $html_select = '<select id="user_id" name="user_id" class="dados_cad_conta">';
            $html_select .= '<option>Selecione um usu&aacute;rio</option>';
            foreach ($statemente as $value) {
                $html_select .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
            }
            $html_select .= '</select>';
        }
    }
    return $html_select;
}

function getDadosRequest($req) {
    $dados = array();
    if (isset($req) && !empty($req)) {
        foreach ($req as $key => $value) {
            $dados[$key] = !empty($value) ? trim($value) : '';
        }
    }
    return $dados;
}

function editarConta($dados) {
    $pdo = conectar();
    $dados['data'] = trim($dados['data']);
    $dados['data'] = ajustaData($dados['data']);
    $sql = 'UPDATE expenses SET title = ?,
                                qtd_portion = ?, 
                                qtd_portion_payment = ?, 
                                total_value = ?, 
                                date = ?, 
                                payment = ?,
                                portion_value = ?,
                                enterprise_id = ?
            WHERE id = ?';
    $statemente = $pdo->prepare($sql);
    $dados['qtd_portion_payment'] = (int)$dados['qtd_portion_payment'];
    $dados['total_value'] = (float)$dados['total_value'];
    $dados['payment'] = (int)$dados['payment'];
    $statemente->bindParam(1, $dados['title'], PDO::PARAM_STR);
    $statemente->bindParam(2, $dados['qtd_portion'], PDO::PARAM_INT);
    $statemente->bindParam(3, $dados['qtd_portion_payment'], PDO::PARAM_INT);
    $statemente->bindParam(4, $dados['total_value'], PDO::PARAM_INT);
    $statemente->bindParam(5, $dados['data'], PDO::PARAM_STR);
    $statemente->bindParam(6, $dados['payment'], PDO::PARAM_INT);
    $statemente->bindParam(7, $dados['portion_value'], PDO::PARAM_INT);
    $statemente->bindParam(8, $dados['enterprise_id'], PDO::PARAM_INT);
    $statemente->bindParam(9, $dados['id'], PDO::PARAM_INT);
    $executa = $statemente->execute();
    return $executa;
}

function ajustaData($data) {
    $dt_retorno = '';
    if (!empty($data)) {
        $vet = array();
        $data = trim($data);
        $vet = explode('/', $data);
        $dt_retorno = $vet[2] . '-' . $vet[1] . '-' . $vet[0];
    }
    return $dt_retorno;
}

function ajustaDataPort($data) {
    $dt_retorno = '';
    if (!empty($data)) {
        $data = trim($data);
        $data = explode(' ', $data);
        $data = $data[0];
        $vet = array();
        $vet = explode('-', $data);
        $dt_retorno = $vet[2] . '/' . $vet[1] . '/' . $vet[0];
    }
    return $dt_retorno;
}

function adicionarContaNormal($dados) {
    $pdo = conectar();
    if (isset($dados['date']) && !empty($dados['date'])) {
        $dados['date'] = ajustaData($dados['date']);
    }
    $sql = 'insert into expenses (  title, 
                                    qtd_portion, 
                                    qtd_portion_payment, 
                                    total_value, 
                                    date, 
                                    payment,
                                    portion_value,
                                    description,
                                    type,
                                    user_id,
                                    expiration_day,
                                    enterprise_id) 
                                    values
                                  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $statemente = $pdo->prepare($sql);
    $dados['qtd_portion_payment'] = (int) $dados['qtd_portion_payment'];
    $dados['total_value'] = (float) $dados['total_value'];
    $dados['payment'] = (int) $dados['payment'];
    $statemente->bindParam(1, $dados['title'], PDO::PARAM_STR);
    $statemente->bindParam(2, $dados['qtd_portion'], PDO::PARAM_INT);
    $statemente->bindParam(3, $dados['qtd_portion_payment'], PDO::PARAM_INT);
    $statemente->bindParam(4, $dados['total_value'], PDO::PARAM_INT);
    $statemente->bindParam(5, $dados['date'], PDO::PARAM_STR);
    $statemente->bindParam(6, $dados['payment'], PDO::PARAM_INT);
    $statemente->bindParam(7, $dados['portion_value'], PDO::PARAM_INT);
    $statemente->bindParam(8, $dados['description'], PDO::PARAM_STR);
    $statemente->bindParam(9, $dados['type'], PDO::PARAM_INT);
    $statemente->bindParam(10, $dados['user_id'], PDO::PARAM_INT);
    $statemente->bindParam(11, $dados['expiration_day'], PDO::PARAM_INT);
    $statemente->bindParam(12, $dados['enterprise_id'], PDO::PARAM_INT);
    $executa = $statemente->execute();
    if ($executa) {
        return true;
    }
    $erro = $statemente->errorInfo();
    print_r($erro);
    die;
    return $executa;
}

function addMes($data) {
    $dt_retorno = '';
    if (!empty($data)) {
        $vet = array();
        $data = trim($data);
        $vet = explode('-', $data);
        if ($vet[1] == 12) {
            $vet[1] = 1;
            $vet[0] ++;
        } else {
            $vet[1] ++;
        }
//      $dt_retorno = $vet[0] . '-' . $vet[1] . '-' . $vet[2] . ' ' . date('H:i:s'); Não preciso da hora
        $dt_retorno = $vet[0] . '-' . $vet[1] . '-' . $vet[2];
    }
    return $dt_retorno;
}

function adicionarContaParcelada($dados) {
    $pdo = conectar();
    if (isset($dados['date']) && !empty($dados['date'])) {
        $dados['date'] = ajustaData($dados['date']);
    }
    for ($i = 1; $i <= $dados['qtd_portion']; $i++) {
        $sql = 'insert into expenses (  title, 
                                        qtd_portion, 
                                        qtd_portion_payment, 
                                        total_value, 
                                        date, 
                                        payment,
                                        portion_value,
                                        description,
                                        type,
                                        user_id,
                                        expiration_day,
                                        enterprise_id) 
                                        values
                                  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $statemente = $pdo->prepare($sql);
        $dados['qtd_portion_payment'] = (int) $dados['qtd_portion_payment'];
        $dados['payment'] = (int) $dados['payment'];
        $statemente->bindParam(1, $dados['title'], PDO::PARAM_STR);
        $statemente->bindParam(2, $dados['qtd_portion'], PDO::PARAM_INT);
        $statemente->bindParam(3, $dados['qtd_portion_payment'], PDO::PARAM_INT);
        $statemente->bindParam(4, $dados['total_value'], PDO::PARAM_INT);
        $statemente->bindParam(5, $dados['date'], PDO::PARAM_STR);
        $statemente->bindParam(6, $dados['payment'], PDO::PARAM_INT);
        $statemente->bindParam(7, $dados['portion_value'], PDO::PARAM_INT);
        $statemente->bindParam(8, $dados['description'], PDO::PARAM_STR);
        $statemente->bindParam(9, $dados['type'], PDO::PARAM_INT);
        $statemente->bindParam(10, $dados['user_id'], PDO::PARAM_INT);
        $statemente->bindParam(11, $dados['expiration_day'], PDO::PARAM_INT);
        $statemente->bindParam(12, $dados['enterprise_id'], PDO::PARAM_INT);
        $executa = $statemente->execute();
//      $eero = $statemente->errorInfo();
//      print_r($eero);
//      die;
        if (!$executa) {
            break;
        }
        $dados['date'] = addMes($dados['date']);
    }
    $pdo = NULL;
    return $executa;
}

function adicionarContaFixa($dados) {
    global $conf_contas;
    $pdo = conectar();
    if (isset($dados['date']) && !empty($dados['date'])) {
        $dados['date'] = ajustaData($dados['date']);
    }
    
    for ($i = 1; $i <= $conf_contas['qtd_meses_conta_fixa']; $i++) {
        
        $sql = 'insert into expenses (  title, 
                                        qtd_portion, 
                                        qtd_portion_payment, 
                                        total_value, 
                                        date, 
                                        payment,
                                        portion_value,
                                        description,
                                        type,
                                        user_id,
                                        expiration_day,
                                        enterprise_id) 
                                        values
                                  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $statemente = $pdo->prepare($sql);
        $dados['qtd_portion_payment'] = (int) $dados['qtd_portion_payment'];
        $dados['total_value'] = (float) $dados['total_value'];
        $dados['payment'] = (int) $dados['payment'];
        $statemente->bindParam(1, $dados['title'], PDO::PARAM_STR);
        $statemente->bindParam(2, $dados['qtd_portion'], PDO::PARAM_INT);
        $statemente->bindParam(3, $dados['qtd_portion_payment'], PDO::PARAM_INT);
        $statemente->bindParam(4, $dados['total_value'], PDO::PARAM_INT);
        $statemente->bindParam(5, $dados['date'], PDO::PARAM_STR);
        $statemente->bindParam(6, $dados['payment'], PDO::PARAM_INT);
        $statemente->bindParam(7, $dados['portion_value'], PDO::PARAM_INT);
        $statemente->bindParam(8, $dados['description'], PDO::PARAM_STR);
        $statemente->bindParam(9, $dados['type'], PDO::PARAM_INT);
        $statemente->bindParam(10, $dados['user_id'], PDO::PARAM_INT);
        $statemente->bindParam(11, $dados['expiration_day'], PDO::PARAM_INT);
        $statemente->bindParam(12, $dados['enterprise_id'], PDO::PARAM_INT);
        $executa = $statemente->execute();
        if (!$executa) {
            return false;
        }
        $dados['date'] = addMes($dados['date']);
    }
    return $executa;
}

function deletarConta($dados) {
    $pdo = conectar();
    $sql = 'DELETE FROM expenses WHERE id = ?';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['id'], PDO::PARAM_INT);
    $executa = $statemente->execute();
    $pdo = NULL;
    return $executa;
}

function getQuantidadeConta($ano, $mes, $tipo) {
    $dados = array('cont' => 0);
    $pdo = conectar();
    $sql = "SELECT  COUNT(expenses.id) AS cont 
            FROM    expenses expenses
            INNER JOIN users users on (users.id = expenses.user_id)
            WHERE   date_format(expenses.date, '%m')=? 
            AND     date_format(expenses.date, '%Y')=? 
            AND     expenses.type=?
            AND     users.group_id = " . $_SESSION['group_id'];
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $mes, PDO::PARAM_STR);
    $statemente->bindParam(2, $ano, PDO::PARAM_STR);
    $statemente->bindParam(3, $tipo, PDO::PARAM_INT);

    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $dados = $value;
            }
        }
    }
    return $dados['cont'];
}

function getValorTotalMes($ano, $mes) {
    $pdo = conectar();
    $sql = "SELECT expenses.type, expenses.total_value, expenses.portion_value 
            FROM expenses expenses
            INNER JOIN users users on (users.id = expenses.user_id)
            WHERE date_format(expenses.date, '%m')=? 
            AND date_format(expenses.date, '%Y')=? 
            AND users.group_id=" . $_SESSION['group_id'];
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $mes, PDO::PARAM_STR);
    $statemente->bindParam(2, $ano, PDO::PARAM_STR);
    $executa = $statemente->execute();
    $total = 0;
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                // 1-Parcelado, 2-Fixo, 3-Normal
                if ($value['type'] == 1) {
                    $total += $value['portion_value'];
                } else {
                    $total += $value['total_value'];
                }
            }
        }
    }
    return $total;
}

function getValorTotalMesContasAtrasadas($ano, $mes) {
    $pdo = conectar();
    $sql = "SELECT expenses.type, expenses.total_value, expenses.portion_value 
            FROM expenses expenses
            INNER JOIN users users on (users.id = expenses.user_id)
            WHERE date_format(expenses.date, '%m')=? 
            AND date_format(expenses.date, '%Y')=? 
            AND users.group_id=" . $_SESSION['group_id'] .
            ' AND expenses.date < "' . date('Y-m-d') . '" AND expenses.payment = 0';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $mes, PDO::PARAM_STR);
    $statemente->bindParam(2, $ano, PDO::PARAM_STR);
    $executa = $statemente->execute();
    $total = 0;
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                // 1-Parcelado, 2-Fixo, 3-Normal
                if ($value['type'] == 1) {
                    $total += $value['portion_value'];
                } else {
                    $total += $value['total_value'];
                }
            }
        }
    }
    return $total;
}

function adicionarEmpresa($dados) {
    $pdo = conectar();
    $sql = 'insert into enterprises (title, description, group_id) values (?, ?, ?)';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['titulo_empresa'], PDO::PARAM_STR);
    $statemente->bindParam(2, $dados['descricao_empresa'], PDO::PARAM_STR);
    $statemente->bindParam(3, $_SESSION['group_id'], PDO::PARAM_INT);
    $executa = $statemente->execute();
    return $executa;
}

function adicionarUsuario($dados) {
    $pdo = conectar();
    $sql = 'insert into users (group_id, name, username, password, email, sex, age, tel_resid, tel_cel) values (?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['group_id'], PDO::PARAM_INT);
    $statemente->bindParam(2, $dados['name'], PDO::PARAM_STR);
    $statemente->bindParam(3, $dados['username'], PDO::PARAM_STR);
    $statemente->bindParam(4, $dados['password'], PDO::PARAM_STR);
    $statemente->bindParam(5, $dados['email'], PDO::PARAM_STR);
    $statemente->bindParam(6, $dados['sex'], PDO::PARAM_STR);
    $statemente->bindParam(7, $dados['age'], PDO::PARAM_INT);
    $statemente->bindParam(8, $dados['tel_resid'], PDO::PARAM_STR);
    $statemente->bindParam(9, $dados['tel_cel'], PDO::PARAM_STR);
    $executa = $statemente->execute();
    return $executa;
}

function editarUsuario($dados) {
    $pdo = conectar();
    $sql = 'update users set group_id = ?, name = ?, username = ?, password = ?, email = ?, sex = ?, age = ?, tel_resid = ?, tel_cel = ?, salary=? where id = ' . $_SESSION['id_user'];
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['group_id'], PDO::PARAM_INT);
    $statemente->bindParam(2, $dados['name'], PDO::PARAM_STR);
    $statemente->bindParam(3, $dados['username'], PDO::PARAM_STR);
    $statemente->bindParam(4, criptografar($dados['password']), PDO::PARAM_STR);
    $statemente->bindParam(5, $dados['email'], PDO::PARAM_STR);
    $statemente->bindParam(6, $dados['sex'], PDO::PARAM_STR);
    $statemente->bindParam(7, $dados['age'], PDO::PARAM_INT);
    $statemente->bindParam(8, $dados['tel_resid'], PDO::PARAM_STR);
    $statemente->bindParam(9, $dados['tel_cel'], PDO::PARAM_STR);
    $statemente->bindParam(10, $dados['salary'], PDO::PARAM_INT);
    $executa = $statemente->execute();
    if ($executa) {
        updateSession($_SESSION['id_user'], $dados['group_id'], $dados['name']);
    }
    return $executa;
}

function getDadosUsuario() {
    $pdo = conectar();
    $statemente = $pdo->prepare('select * from users where id = ? ');
    $statemente->bindParam(1, $_SESSION['id_user'], PDO::PARAM_INT);
    $executa = $statemente->execute();
    $dados = array();
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $dados = $value;
            }
        }
    }
    return $dados;
}

function adicionarGrupo($dados) {
    $pdo = conectar();
    $sql = 'insert into groups (title, description) values (?, ?)';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['title'], PDO::PARAM_STR);
    $statemente->bindParam(2, $dados['description'], PDO::PARAM_STR);
    $executa = $statemente->execute();
    return $executa;
}

function cadastrarNote($dados) {
    $pdo = conectar();
    $sql = 'insert into notes (date_note, description, group_id) values (?, ?, ?)';
    if (isset($dados['data_anotacao']) && !empty($dados['data_anotacao'])) {
        $dados['data_anotacao'] = ajustaData($dados['data_anotacao']);
    }
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['data_anotacao'], PDO::PARAM_STR);
    $statemente->bindParam(2, $dados['descricao_anotacao'], PDO::PARAM_STR);
    $statemente->bindParam(3, $_SESSION['group_id'], PDO::PARAM_STR);
    $executa = $statemente->execute();
    return $executa;
}

function editarNote($dados) {
    $pdo = conectar();
    $sql = 'update notes set date_note=?, description=?, group_id=? where id=?';
    if (isset($dados['data_anotacao']) && !empty($dados['data_anotacao'])) {
        $dados['data_anotacao'] = ajustaData($dados['data_anotacao']);
    }
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['data_anotacao'], PDO::PARAM_STR);
    $statemente->bindParam(2, $dados['descricao_anotacao'], PDO::PARAM_STR);
    $statemente->bindParam(3, $_SESSION['group_id'], PDO::PARAM_STR);
    $statemente->bindParam(4, $dados['id'], PDO::PARAM_STR);
    $executa = $statemente->execute();
    return $executa;
}

function getDadosNote($dados) {
    $pdo = conectar();
    $dados_retorno = array();
    $sql = "SELECT * FROM notes WHERE id=?";
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['idNote'], PDO::PARAM_STR);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $dados_retorno['id'] = $value['id'];
                $dados_retorno['date_note'] = $value['date_note'];
                $dados_retorno['description'] = $value['description'];
            }
        }
    }
    return $dados_retorno;
}

function lerNote($dados) {
    $pdo = conectar();
    $sql = 'update notes set note_read = 1 where id = ?';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['id_note'], PDO::PARAM_INT);
    return $statemente->execute();
}

function NotLerNote($dados) {
    $pdo = conectar();
    $sql = 'update notes set note_read = 0 where id = ?';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['id_note'], PDO::PARAM_INT);
    return $statemente->execute();
}

function deletarNote($dados) {
    $pdo = conectar();
    $sql = 'delete from notes where id = ?';
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $dados['id_note'], PDO::PARAM_INT);
    return $statemente->execute();
}

function getStatusContas() {
    $pdo = conectar();
    $sql = 'SELECT  expenses.title, expenses.date, expenses.id
            FROM    expenses expenses
            INNER JOIN users users ON (users.id = expenses.user_id) 
            INNER JOIN groups groups ON (groups.id = users.group_id)
            WHERE   users.group_id = ' . $_SESSION['group_id'] .
            ' AND expenses.date < "' . date('Y-m-d') . '" AND expenses.payment = 0';
    $statemente = $pdo->prepare($sql);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            $dados = array();
            $i = 1;
            foreach ($statemente as $value) {
                $dados[] = $i . '-' . $value['title'] . ' (' . ajustaDataPort($value['date']) . ')<br/>';
                $i++;
            }
        }
    }
    return $dados;
}

function criptografar($string) {
    return sha1($string);
}

function validaAcesso($user, $password) {
    $pdo = conectar();
    $dados_retorno = array();
    $password = criptografar($password);
    $sql = "SELECT id, group_id, name FROM users WHERE username =? AND password=?";
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $user, PDO::PARAM_STR);
    $statemente->bindParam(2, $password, PDO::PARAM_STR);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $dados_retorno['id'] = $value['id'];
                $dados_retorno['group_id'] = $value['group_id'];
                $dados_retorno['name'] = $value['name'];
            }
        }
    }
    return $dados_retorno;
}

function startSession($id_user, $group_id, $name) {
    if (!isset($_SESSION['id_user'])) {
        $_SESSION['id_user'] = $id_user;
        $_SESSION['group_id'] = $group_id;
        $_SESSION['name'] = $name;
    } else {
        die('Falha ao iniciar sessão!');
    }
}

function updateSession($id_user, $group_id, $name) {
    $_SESSION['id_user'] = $id_user;
    $_SESSION['group_id'] = $group_id;
    $_SESSION['name'] = $name;
}

function getQtdAnotacoesNaoLidas() {
    $pdo = conectar();
    $dados = array();
    $sql = "SELECT count(id) as cont FROM notes WHERE group_id = ? AND note_read =0";
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $_SESSION['group_id'], PDO::PARAM_STR);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $dados = $value;
            }
        }
    }
    return $dados['cont'];
}

$dados = array();
$titulo = '';
$enterprise_id_filtro = '';
$order_by = '';
$data_inicio = '';
$data_fim = '';
$user_expense_id = '';
$mes = '';
$id_grupo = '';
if (!empty($_REQUEST)) {
    $dados = getDadosRequest($_REQUEST);
    $titulo = isset($dados['titulo']) ? $dados['titulo'] : '';
    $enterprise_id_filtro = isset($dados['enterprise_id_filtro']) ? $dados['enterprise_id_filtro'] : '';
    $order_by = isset($dados['order_by']) ? $dados['order_by'] : '';
    $data_inicio = isset($dados['data_inicio']) ? $dados['data_inicio'] : '';
    $data_fim = isset($dados['data_fim']) ? $dados['data_fim'] : '';
    $user_expense_id = isset($dados['user_expense_id']) ? $dados['user_expense_id'] : '';
    $mes = isset($dados['mes']) ? $dados['mes'] : '';
    $id_grupo = isset($dados['id_grupo']) ? $dados['id_grupo'] : '';
}
?>