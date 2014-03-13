<?php if (isset($_GET['showRevision'])) die('<p>$Id: Util.inc.php 23444 2013-09-26 22:00:57Z leandroe $</p>'); ?>
<?php

require_once ('config.php');
require_once (_DIR_HOME_ . 'vasa.php');
require_once (_DIR_HOME_ . 'connect.php');
require_once (_DIR_HOME_ . 'functions.php');
require_once (_DIR_CLASSES_ . 'classes.inc.php');
require_once (_DIR_CLASSES_ . 'generico.inc.php');

/**
 * Classe Util
 *
 * @author Leandro Shindi Ekamoto
 * @version 1.0
 * @copyright 2013 Compnet Tecnologia
 *
 */
class Util extends tbObjetoExt {

   public static $bd = null;

   public static function preparaBD() {
      if (self::$bd == null) {
         self::$bd = new fncsPHP ();
      }
   }

   public static function mask($val, $mask) {

      if (!empty($val)) {
         $val = trim($val);
         $mask = trim($mask);
         $texto = (string) $val;
         $cont_carac = strlen($texto);
         $cont_carac_mask = substr_count($mask, '#');

         if ($cont_carac != $cont_carac_mask) {
            return $val;
         }

         $maskared = '';
         $k = 0;
         for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
               if (isset($val[$k])) {
                  $maskared .= $val[$k++];
               }
            } else {
               if (isset($mask[$i]))
                  $maskared .= $mask[$i];
            }
         }
         return $maskared;
      }
      return false;
   }

   // Acrescenta os zeros
   public static function addZero($string, $qtd) {
      if (!empty($string)) {
         $string = (string) $string;
         $tam = strlen($string);

         $qtd_dig = ($qtd - $tam);

         for ($i = 0; $i < $qtd_dig; $i++) {
            $string = '0' . $string;
         }
         return $string;
      }
      return false;
   }

   public function shortstr($string, $limit = 10) {

      //$pontos = '...';
      $pontos = '';

      $string = (strlen($string) > $limit) ? substr($string, 0, $limit) . $pontos : $string;
      return $string;
   }

}

?>