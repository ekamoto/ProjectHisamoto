<?php

include '../functions.php';

if (isset($_REQUEST) && !empty($_REQUEST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (isset($dados['action']) && !empty($dados['action'])) {
      switch ($dados['action']) {
         case 'cadastrar_grupo':
            $retorno = adicionarGrupo($dados);
            if ($retorno) {
               $msg = 'Grupo ' . $dados['title'] . ' cadastrado com sucesso!';
            } else {
               $msg = 'Falha ao cadastrar grupo ' . $dados['title'];
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         default:
            die('Action não identificado!');
            break;
      }
   }
}