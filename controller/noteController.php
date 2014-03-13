<?php

include '../functions.php';

class noteController {

   private $dados;

   public function setDados($dados) {
      $this->setDados($dados);
   }

   public function editarNote($dados) {
      $retorno = editarNote($dados);
      if ($retorno) {
         $msg = 'Anotação editada com sucesso!';
      } else {
         $msg = 'Falha ao editar anotação!';
      }
      echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
   }
}

if (isset($_REQUEST) && !empty($_REQUEST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (!empty($dados)) {
      $controller = new noteController();
      $dados['action'] = trim($dados['action']);
      $action = (isset($dados['action']) && !empty($dados['action'])) ? $dados['action'] : false;
      if ($action && method_exists($controller, $action)) {
         $controller->$action($dados);
         // Tem esse dia aqui porque ainda tem o switch.
         // Futuramente não vai existir e poderá ser removido
         die();
      }
   }
}

//Arrumar daqui para baixo metodologia antiga
if (isset($_REQUEST) && !empty($_REQUEST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (isset($dados['action']) && !empty($dados['action'])) {
      switch ($dados['action']) {
         case 'getQtdAnotacoesNaoLidas':
            $count = getQtdAnotacoesNaoLidas();
            echo json_encode(array('cont' => $count));
            break;
         case 'carregarDados':
            $retorno = getDadosNote($dados);
            echo json_encode($retorno);
            break;
         case 'cadastrarNote':
            $retorno = cadastrarNote($dados);
            if ($retorno) {
               $msg = 'Anotação cadastrada com sucesso!';
            } else {
               $msg = 'Falha ao cadastrar anotação!';
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'ler_note':
            $retorno = lerNote($dados);
            if ($retorno) {
               $msg = 'Anotação lida com sucesso!';
            } else {
               $msg = 'Falha ao ler anotação!';
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'not_ler_note':
            $retorno = NotLerNote($dados);
            if ($retorno) {
               $msg = 'Leitura cancelada!';
            } else {
               $msg = 'Falha ao cancelar leitura!';
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'deletarNote':
            echo json_encode(array('ok' => deletarNote($dados)));
            break;
         default:
            die('Action não identificado!');
            break;
      }
   }
}