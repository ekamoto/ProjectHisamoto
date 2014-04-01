<?php
include './functions.php';
/**
 * Classe para testar as funções do sistema
 *
 * @author Leandro Shindi Ekamoto
 */
class TesteFunctions {
   function CadastrarEditarDeletarContaNormal() {
      echo 'CadastrarEditarDeletarContaNormal<br/>';
      $dados['title'] = 'fantasma';
      $dados['qtd_portion'] = '';
      $dados['qtd_portion_payment'] = '';
      $dados['total_value'] = '';
      $dados['date'] = '';
      $dados['payment'] = '';
      $dados['portion_value'] = '';
      $dados['description'] = '';
      $dados['type'] = '';
      $dados['user_id'] = '';
      $dados['expiration_day'] = '';
      $dados['enterprise_id'] = '';
      echo 'Resultado:<br/>' . (int)adicionarContaNormal($dados);
   }
}

$teste = new TesteFunctions();
$teste->CadastrarEditarDeletarContaNormal();




?>
