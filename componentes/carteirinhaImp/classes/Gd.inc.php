<?php

/**
 * @author Leandro Shindi Ekamoto
 * @copyright 2013
 */
class Gd {

   private static $img;

   function __construct($caminho_imagem) {
      $this->abrirImagem($caminho_imagem);
   }

   public static function setImg($img) {
      self::$img = $img;
   }

   public static function abrirImagem($caminho_imagem) {

      $dados = getimagesize($caminho_imagem);

      // Determinar se o tipo de imagem é suportado
      $tipo = $dados[2];
      if ($tipo & imagetypes()) {
         switch ($tipo) {
            case IMAGETYPE_GIF:
               $img = imagecreatefromgif($caminho_imagem);
               self::$img = $img;
               break;
            case IMAGETYPE_JPEG:
               $img = imagecreatefromjpeg($caminho_imagem);
               self::$img = $img;
               break;
            case IMAGETYPE_PNG:
               $img = imagecreatefrompng($caminho_imagem);
               self::$img = $img;
               break;
            case IMAGETYPE_WBMP:
               $img = imagecreatefromwbmp($caminho_imagem);
               self::$img = $img;
               break;
            case IMAGETYPE_XPM:
               $img = imagecreatefromxpm($caminho_imagem);
               self::$img = $img;
               break;
            default:
               $conteudo = file_get_contents($caminho_imagem);
               $img = imagecreatefromstring($conteudo);
               self::$img = $img;
               break;
         }
         if (!self::$img) {
            return false;
         }
         return true;
      }
   }

   public static function setStringImage($dados) {
      if (!empty(self::$img)) {
         $str = $dados['string'];
         $RSR = self::$img;
         $str_color = imagecolorallocate($RSR, $dados['cor']['r'], $dados['cor']['g'], $dados['cor']['b']);
         $fnt = $dados['tamanho_font'];
         $int_x = $dados['x'];
         $int_y = $dados['y'];

         $size_font = $dados['size_fonte'];

         // Tive que trocar de função porque a função antiga não me permitia aumentar a fonte
         $caminho_fonte = _DIR_HOME_ . 'cer/Images/carteirinha/fontes/';
         $ret = imagettftext($RSR, $size_font, 0, $int_x, $int_y, $str_color, $caminho_fonte . "Arial-Bold.ttf", $str);

         if ($ret) {
            if (!empty($dados['pasta_arquivo'])) {
               if (!is_dir($dados['pasta_arquivo'])) {
                  return false;
               }
            }

            $ret = imagejpeg($RSR, $dados['pasta_arquivo'] . $dados['nome_arquivo'], $dados['qualidade_imp']);

            if (!$ret) {
               return false;
            }

            return file_exists($dados['pasta_arquivo'] . $dados['nome_arquivo']);
         }
         return false;
      } else {
         return false;
      }
   }

   public static function redimensionarImagem($imagem, $pasta_arquivo, $nome_arquivo, $width, $height, $qualidade) {

      $RSRseal = imagecreatefromjpeg($imagem);
      $base_w = imagesx($RSRseal);
      $base_h = imagesy($RSRseal);

      $dest_w = $width;
      $dest_h = $height;

      $RSRdest = imagecreatetruecolor($dest_w, $dest_h);
      $bool_res = imagecopyresized($RSRdest, $RSRseal, 0, 0, 0, 0, $dest_w, $dest_h, $base_w, $base_h);

      return imagejpeg($RSRdest, $pasta_arquivo . $nome_arquivo, $qualidade);
   }

   public static function mergeImagens($dados) {

      $RSRbase = imagecreatefromjpeg($dados['imagem1']);
      $RSRseal = imagecreatefromjpeg($dados['imagem2']);

      $base_w = imagesx($RSRbase);
      $base_h = imagesy($RSRbase);

      $seal_w = imagesx($RSRseal);
      $seal_h = imagesy($RSRseal);

      $base_x = (imagesx($RSRbase) - $seal_w) - $dados['margin-right'];
      $base_y = (imagesy($RSRbase) - $seal_h) - $dados['margin-botton'];

      $ret = imagecopymerge($RSRbase, $RSRseal, $base_x, $base_y, 0, 0, $seal_w, $seal_h, $dados['qualidade_imp']);
      if ($ret) {
         return imagejpeg($RSRbase, $dados['pasta_arquivo'] . $dados['nome_final'], $dados['qualidade_imp']);
      }
      return false;
   }

}

?>