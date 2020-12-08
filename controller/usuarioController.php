<?php

include '../functions.php';

if (isset($_REQUEST) && !empty($_REQUEST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (isset($dados['action']) && !empty($dados['action'])) {
      switch ($dados['action']) {
         case 'cadastrar_usuario':
            $retorno = adicionarUsuario($dados);
            if ($retorno) {
               $msg = 'Usuario ' . $dados['name'] . ' cadastrada com sucesso!';
            } else {
               $msg = 'Falha ao cadastrar usuario ' . $dados['name'];
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'editar_usuario':
            $retorno = editarUsuario($dados);
            if ($retorno) {
               $msg = 'Usuario ' . $dados['name'] . ' editado com sucesso!';
            } else {
               $msg = 'Falha ao editar usuario ' . $dados['name'];
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         case 'get_dados_usuario':
            $retorno = getDadosUsuario();
            echo json_encode($retorno);
            break;
         default:
            die('Action não identificado!');
            break;
      }
   }
}