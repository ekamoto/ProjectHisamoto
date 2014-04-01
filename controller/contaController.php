<?php

include '../functions.php';

if (isset($_REQUEST) && !empty($_REQUEST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (isset($dados['action']) && !empty($dados['action'])) {
      switch ($dados['action']) {
         case 'editar_conta':
            $msg = '';
            $retorno = editarConta($dados);
            if ($retorno) {
               $msg = 'Editada(o) com sucesso!';
            } else {
               $msg = 'Falha ao editar conta!';
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'deletar_conta':
            $msg = '';
            $retorno = deletarConta($dados);
            if ($retorno) {
               $msg = 'Deletada(o) com sucesso!';
            } else {
               $msg = 'Falha ao deletar conta!';
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'add_conta':
            $retorno = false;
            $msg = '';
            /* Tipo de conta:
             * 1-Parcelado
             * 2-Fixo
             * 3-Nomal
             */
            if (isset($dados['type']) && !empty($dados['type'])) {
               if ($dados['type'] == 1) {
                  $retorno = adicionarContaParcelada($dados);
               } else if ($dados['type'] == 2) {
                  $retorno = adicionarContaFixa($dados);
               } else if ($dados['type'] == 3) {
                  $retorno = adicionarContaNormal($dados);
               }
            }
            if ($retorno) {
               $msg = 'Adicionada(o) com sucesso!';
            } else {
               $msg = 'Falha ao adicionar conta!';
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'get_status_contas':
            $msg = '';
            $retorno = getStatusContas();
            echo json_encode(array('ok' => true, 'cont' => count($retorno), 'contas' => implode("\n", $retorno)));
            break;
         default:
            die('Action não identificado!');
            break;
      }
   }
}