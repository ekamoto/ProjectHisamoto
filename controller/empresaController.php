<?php

include '../functions.php';

if (isset($_REQUEST) && !empty($_REQUEST)) {
   $dados = array();
   $dados = getDadosRequest($_REQUEST);
   if (isset($dados['action']) && !empty($dados['action'])) {
      switch ($dados['action']) {
         case 'cadastrar_empresa':
            $retorno = adicionarEmpresa($dados);
            if ($retorno) {
               $msg = 'Empresa ' . $dados['titulo_empresa'] . ' cadastrada com sucesso!';
            } else {
               $msg = 'Falha ao cadastrar empresa ' . $dados['titulo_empresa'];
            }
            echo json_encode(array('ok' => (int) $retorno, 'msg' => $msg));
            break;
         default:
            die('Action não identificado!');
            break;
      }
   }
}