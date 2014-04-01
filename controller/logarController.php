<?php

include '../functions.php';

if (isset($_POST) && !empty($_POST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (isset($dados['action']) && !empty($dados['action'])) {
      switch ($dados['action']) {
         case 'logar':
            $msg = '';
            /* Array
             *  (
             *      [id] => 29
             *      [group_id] => 26
             *      [name] => Leandro Shindi Ekamoto
             *  )
             */
            $ok = false;
            $dados_retorno = array();
            $dados_retorno = validaAcesso($dados['user'], $dados['password']);
            if (!empty($dados_retorno)) {
               startSession($dados_retorno['id'], $dados_retorno['group_id'], $dados_retorno['name']);
               $ok = true;
            }
            echo json_encode(array('ok' => $ok, 'msg' => $msg));
            break;
         default:
            die('Action não identificado!');
            break;
      }
   }
} else {
   echo 'Sua tentativa de invadir o sistema foi gravada!';
}