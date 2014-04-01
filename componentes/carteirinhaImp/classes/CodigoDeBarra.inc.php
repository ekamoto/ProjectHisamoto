<?php

/**
 * @author Leandro Shindi Ekamoto
 * @copyright 2013
 */
class CodigoDeBarra {

   public static $caminho_codigo_barras;
   public static $nome_codigo_barras;
   public static $width = 450;
   public static $height = 150;

   public static function gerarCodigoBarras($code) {

      $lw = 2;
      $hi = 100;
      $ends = '101';
      $center = '01010';
      $Lencode = array('0001101', '0011001', '0010011', '0111101', '0100011',
          '0110001', '0101111', '0111011', '0110111', '0001011');
      $Rencode = array('1110010', '1100110', '1101100', '1000010', '1011100',
          '1001110', '1010000', '1000100', '1001000', '1110100');

      /* UPC-A Must be 11 digits, we compute the checksum. */
      if (strlen($code) != 11) {
         die("UPC-A Must be 11 digits.");
      }

      /* EAN-13 Checksum digit */
      $ncode = '0' . $code;
      $even = 0;
      $odd = 0;
      for ($x = 0; $x < 12; $x++) {
         if ($x % 2) {
            $odd += $ncode[$x];
         } else {
            $even += $ncode[$x];
         }
      }
      $code .= (10 - (($odd * 3 + $even) % 10)) % 10;

      $bars = $ends;
      $bars .= $Lencode[$code[0]];

      for ($x = 1; $x < 6; $x++) {
         $bars .= $Lencode[$code[$x]];
      }

      $bars .= $center;
      for ($x = 6; $x < 12; $x++) {
         $bars .= $Rencode[$code[$x]];
      }
      $bars .= $ends;
      
      $img = ImageCreate($lw * 95 + 30, $hi + 30);
      $fg = ImageColorAllocate($img, 0, 0, 0);
      $bg = ImageColorAllocate($img, 255, 255, 255);
      ImageFilledRectangle($img, 0, 0, $lw * 95 + 30, $hi + 30, $bg);

      $shift = 10;
      $size_bar = strlen($bars);
      for ($x = 0; $x < $size_bar; $x++) {
         if (($x < 10) || ($x >= 45 && $x < 50) || ($x >= 85)) {
            $sh = 10;
         } else {
            $sh = 0;
         }
         if ($bars[$x] == '1') {
            $color = $fg;
         } else {
            $color = $bg;
         }
         ImageFilledRectangle($img, ($x * $lw) + 15, 5, ($x + 1) * $lw + 14, $hi + 5 + $sh, $color);
      }

      ImageString($img, 4, 5, $hi - 5, $code[0], $fg);
      for ($x = 0; $x < 5; $x++) {
         ImageString($img, 5, $lw * (13 + $x * 6) + 15, $hi + 5, $code[$x + 1], $fg);
         ImageString($img, 5, $lw * (53 + $x * 6) + 15, $hi + 5, $code[$x + 6], $fg);
      }
      ImageString($img, 4, $lw * 95 + 17, $hi - 5, $code[11], $fg);
      imagejpeg($img, self::$caminho_codigo_barras . self::$nome_codigo_barras);
      // Gambi do krai para redimensionar
      $caminho_imagem = self::$caminho_codigo_barras . self::$nome_codigo_barras;

      $RSRbase = imagecreatefromjpeg($caminho_imagem);
      $base_w = imagesx($RSRbase);
      $base_h = imagesy($RSRbase);

      $dest_w = self::$width;
      $dest_h = self::$height;

      $RSRdest = imagecreatetruecolor($dest_w, $dest_h);
      $bool_res = imagecopyresized($RSRdest, $RSRbase, 0, 0, 0, 0, $dest_w, $dest_h, $base_w, $base_h);

      return imagejpeg($RSRdest, $caminho_imagem);
   }
}
?>