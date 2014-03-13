<?php

require_once('Gd.inc.php');
/*
 * @author Leandro Shindi Ekamoto
 * @copyright 2013
 *
 */

class Carteirinha extends Gd {

   public static $cart_frente_nome_inicio;
   public static $cart_frente_nome_fim;
   public static $cart_frente_caminho_origem;
   public static $cart_frente_caminho_destino;
   public static $cart_verso_nome_inicio;
   public static $cart_verso_nome_fim;
   public static $cart_verso_caminho_origem;
   public static $cart_verso_caminho_destino;
   public static $gerar_frente_verso = false;
   public static $cart_frente_verso_nome_inicio;
   public static $cart_frente_verso_nome_fim;
   public static $cart_frente_verso_caminho_origem;
   public static $cart_frente_verso_caminho_destino;
   /*
    * Variável reset é utilizado para pegar outra imagem origem para edição
    * enquanto tiver false irá editar a imagem corrente
    */
   public static $reset = true;

   public static function gerarCarteirinha($dados) {
      // Seta dados da frente do cartão
      $ret_dados_frente = self::setDadosFrente($dados);
      if ($ret_dados_frente) {
         // Seta dados no verso do cartão
         $ret_dados_verso = self::setDadosVerso($dados);
         if ($ret_dados_verso) {
            return true;
         }
      }
      return false;
   }

   private static function setString($string, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $string);
         $dados['size_fonte'] = isset($dados['size_fonte']) ? $dados['size_fonte'] : 17;
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   private static function setDadosFrente($dados) {
      if (isset($dados['Frente']['string']) && !empty($dados['Frente']['string'])) {
         foreach ($dados['Frente']['string'] as $value) {
            $ret = self::setString($value['valor'], $value['x'], $value['y']);
            if (!$ret) {
               return false;
            }
         }
      }
      if (isset($dados['Frente']['img']) && !empty($dados['Frente']['img'])) {
         foreach ($dados['Frente']['img'] as $value) {
            if (isset($value['redimensionar']) && $value['redimensionar']) {
               $caminho_foto = $value['caminho_foto'] . $value['nome'];
               $nome_img_temp = $value['nome_img_temp'];
               $caminho_foto_temp = $value['caminho_foto_temp'] . $nome_img_temp;
               $width = $value['width'];
               $heigth = $value['heigth'];
               $qualidade = $value['qualidade'];
               // Redimensionar imagem do cidadão cadastrada no sigo
               $redimensinar_ok = self::redimensionarImagem($caminho_foto, self::$cart_frente_caminho_destino, $nome_img_temp, $width, $heigth, $qualidade);
               if ($redimensinar_ok) {
                  $x = $value['x'];
                  $y = $value['y'];
                  $ret = self::setFoto(self::$cart_frente_caminho_destino, $nome_img_temp, $x, $y);
                  // Deleta imagem temporária
                  unlink($caminho_foto_temp);
                  if (!$ret) {
                     return false;
                  }
               }
            }
         }
      }
      return true;
   }

   private static function setDadosVerso($dados) {
      // Trocando a imagem corrente
      self::$reset = true;
      $caminho_imagem_base = self::$cart_verso_caminho_origem . self::$cart_verso_nome_inicio; // Imagem base
      if (isset($dados['codigo_barras']) && !empty($dados['codigo_barras']) && isset($dados['caminho_codigo_barras'])) {
         // Anexo o código de barras no verso da imagem
         $ret = self::setCodigoBarras($dados['caminho_codigo_barras'], $caminho_imagem_base, $dados['codigo_barras'], $dados['x_codigo_barras'], $dados['y_codigo_barras']);
         if (!$ret) {
            return false;
         }
         // Pega a imagem do verso gerada com o código de barras
         self::$cart_frente_caminho_origem = self::$cart_verso_caminho_destino;
         // Seto o nome inicial da imagem
         self::$cart_frente_nome_inicio = self::$cart_verso_nome_fim;
      } else {
         // Pega a imagem do verso gerada sem o código de barras
         self::$cart_frente_caminho_origem = self::$cart_verso_caminho_origem;
         // Seto o nome inicial da imagem
         self::$cart_frente_nome_inicio = self::$cart_verso_nome_inicio;
      }
      $ret_cnpj = false;
      $ret_nm_resp = false;
      // Seto o nome que vai ter a imagem fim
      self::$cart_frente_nome_fim = self::$cart_verso_nome_fim;
      if (isset($dados['Verso']['string']) && !empty($dados['Verso']['string'])) {
         foreach ($dados['Verso']['string'] as $value) {
            $ret = self::setString($value['valor'], $value['x'], $value['y']);
            if (!$ret) {
               return false;
            }
         }
      }
      if (isset($dados['Verso']['img']) && !empty($dados['Verso']['img'])) {
         foreach ($dados['Verso']['img'] as $value) {
            if (isset($value['redimensionar']) && $value['redimensionar']) {
               $caminho_foto = $value['caminho_foto'] . $value['nome'];
               $nome_img_temp = $value['nome_img_temp'];
               $caminho_foto_temp = $value['caminho_foto_temp'] . $nome_img_temp;
               $width = $value['width'];
               $heigth = $value['heigth'];
               $qualidade = $value['qualidade'];
               // Redimensionar imagem do cidadão cadastrada no sigo
               $redimensinar_ok = self::redimensionarImagem($caminho_foto, self::$cart_frente_caminho_destino, $nome_img_temp, $width, $heigth, $qualidade);
               if ($redimensinar_ok) {
                  $x = $value['x'];
                  $y = $value['y'];
                  $ret = self::setFoto(self::$cart_frente_caminho_destino, $nome_img_temp, $x, $y);
                  // Deleta imagem temporária
                  unlink($caminho_foto_temp);
                  if (!$ret) {
                     return false;
                  }
               }
            }
         }
      }
      return true;
   }

   public static function setNomeResponsavel($nm_responsavel, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $nm_responsavel);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   private static function setDadosFrenteVerso($dados) {
      if (self::$gerar_frente_verso) {
         $ret = self::gerarFrenteVerso($dados);
         if (!$ret) {
            return false;
         }
      }
   }

   private static function setParamInsertString($x = 0, $y = 0, $string = '', $size_font = 5, $nm_fim = 'nome_default', $camin_fim = '', $qual = 100, $cor_rgb = '0,0,0') {
      $dados = array();
      // Posição da string
      $dados['x'] = $x;
      $dados['y'] = $y;
      $dados['string'] = utf8_decode($string);
      $vet = array();
      $vet = explode(',', $cor_rgb);
      // Cor da letra
      $dados['cor']['r'] = $vet[0];
      $dados['cor']['g'] = $vet[1];
      $dados['cor']['b'] = $vet[2];
      // Tamanho da fonte [1,5]
      $dados['tamanho_font'] = $size_font;
      // Nome do arquivo criado
      $dados['nome_arquivo'] = $nm_fim;
      // Caminho da pasta onde vai o arquivo
      $dados['pasta_arquivo'] = $camin_fim;
      // Nível de qualidade da impressão.[0,100]
      $dados['qualidade_imp'] = $qual;
      return $dados;
   }

   private static function open() {
      if (!self::$reset) {
         $imagem = self::$cart_frente_caminho_destino . self::$cart_frente_nome_fim;
      } else {
         $imagem = self::$cart_frente_caminho_origem . self::$cart_frente_nome_inicio;
         self::$reset = false;
      }
      if (file_exists($imagem)) {
         return self::abrirImagem($imagem);
      }
      return false;
   }

   private static function setParamStrin($x, $y, $string) {
      // [1,5]
      $size_font = 5;
      // Qualidade
      $qualidade = 100;
      // Cor da letra rgb
      $cor = '0,0,0';
      $dados = array();
      $dados = self::setParamInsertString($x, $y, $string, $size_font, self::$cart_frente_nome_fim, self::$cart_frente_caminho_destino, $qualidade, $cor);
      return $dados;
   }

   public static function setNomeAgente($nome_agente, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $nome_agente);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setNomeMae($nome_mae, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $nome_mae);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setNomePai($nome_pai, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $nome_pai);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setRg($rg, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $rg);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setCpf($cpf, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $cpf);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setDataExpedicao($dt_expedicao, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $dt_expedicao);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setValidade($dt_validade, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $dt_validade);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setNomeEntidade($nm_entidade, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $nm_entidade);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setCnpj($cnpj, $x, $y) {
      if (self::open()) {
         $dados = array();
         $dados = self::setParamStrin($x, $y, $cnpj);
         if (self::setStringImage($dados)) {
            return true;
         }
      }
      return false;
   }

   public static function setFoto($caminho_foto, $foto, $margin_right, $margin_botton) {
      $caminho_imagem_base = self::$cart_frente_caminho_destino . self::$cart_frente_nome_fim;
      $caminho_imagem = $caminho_foto . $foto; // Imagem inserida
      if (file_exists($caminho_imagem_base) && file_exists($caminho_imagem)) {
         $dados = array();
         $dados['imagem1'] = $caminho_imagem_base;
         $dados['imagem2'] = $caminho_imagem;
         $dados['nome_final'] = self::$cart_frente_nome_fim;
         $dados['pasta_arquivo'] = self::$cart_frente_caminho_destino;
         $dados['margin-right'] = $margin_right;
         $dados['margin-botton'] = $margin_botton;
         // Nível de qualidade da impressão.[0,100]
         $dados['qualidade_imp'] = 100;
         return self::mergeImagens($dados);
      }
      return false;
   }

   public static function setCodigoBarras($caminho_foto, $caminho_imagem_base, $foto, $x = 0, $y = -7) {
      $caminho_imagem = $caminho_foto . $foto;
      if (file_exists($caminho_imagem_base) && file_exists($caminho_imagem)) {
         $dados = array();
         $dados['imagem1'] = $caminho_imagem_base;
         $dados['imagem2'] = $caminho_imagem;
         $dados['nome_final'] = self::$cart_verso_nome_fim;
         $dados['pasta_arquivo'] = self::$cart_verso_caminho_destino;
         $dados['margin-right'] = $x;
         $dados['margin-botton'] = $y;
         // Nível de qualidade da impressão.[0,100]
         $dados['qualidade_imp'] = 100;
         return self::mergeImagens($dados);
      }
      return false;
   }
   
   public static function gerarFrenteVerso($dados) {
      self::$cart_frente_caminho_origem = self::$cart_frente_verso_caminho_origem;
      self::$cart_frente_nome_inicio = self::$cart_frente_verso_nome_inicio;
      self::$cart_frente_nome_fim = self::$cart_frente_verso_nome_fim;
      Carteirinha::$reset = true;
      $ajust_x = 45;
      $ajust_y = 30;
      if (isset($dados['nome_agente']) && !empty($dados['nome_agente'])) {
         $ret = self::setNomeAgente($dados['nome_agente'], 312 + $ajust_x, 259 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['nome_mae']) && !empty($dados['nome_mae'])) {
         $ret = self::setNomeMae($dados['nome_mae'], 312 + $ajust_x, 349 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['nome_pai']) && !empty($dados['nome_pai'])) {
         $ret = self::setNomePai($dados['nome_pai'], 312 + $ajust_x, 379 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['rg']) && !empty($dados['rg'])) {
         $ret = self::setRg($dados['rg'], 312 + $ajust_x, 479 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['cpf']) && !empty($dados['cpf'])) {
         $ret = self::setCpf($dados['cpf'], 612 + $ajust_x, 479 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['data_expedicao']) && !empty($dados['data_expedicao'])) {
         $ret = self::setDataExpedicao($dados['data_expedicao'], 312 + $ajust_x, 545 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['dt_validade']) && !empty($dados['dt_validade'])) {
         $ret = self::setValidade($dados['dt_validade'], 612 + $ajust_x, 545 + $ajust_y);
         if (!$ret) {
            return false;
         }
      }
      if (isset($dados['foto']) && !empty($dados['foto']) && isset($dados['caminho_foto'])) {
         $margin_botton = 691;
         $margin_right = 699;
         $ret = self::setFoto(self::$cart_frente_caminho_destino, 'temp.jpg', $margin_right, $margin_botton);
         if (!$ret) {
            return false;
         }
      }
      $caminho_foto = $dados['caminho_codigo_barras'];
      $foto = $dados['codigo_barras'];
      $caminho_imagem_base = self::$cart_frente_caminho_destino . self::$cart_frente_nome_fim;
      $caminho_imagem = $caminho_foto . $foto;
      if (file_exists($caminho_imagem_base) && file_exists($caminho_imagem)) {
         $dados = array();
         $dados['imagem1'] = $caminho_imagem_base;
         $dados['imagem2'] = $caminho_imagem;
         $dados['nome_final'] = self::$cart_frente_nome_fim;
         $dados['pasta_arquivo'] = self::$cart_frente_caminho_destino;
         $dados['margin-right'] = 11;
         $dados['margin-botton'] = 10;
         // Nível de qualidade da impressão.[0,100]
         $dados['qualidade_imp'] = 100;
         return self::mergeImagens($dados);
      }
      return false;
   }
}
?>