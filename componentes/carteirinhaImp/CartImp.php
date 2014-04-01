<?php

require_once 'componentes/carteirinhaImp/classes/Util.inc.php';
require_once 'componentes/carteirinhaImp/classes/Carteirinha.inc.php';
require_once 'componentes/carteirinhaImp/classes/Barcode.inc.php';
require_once 'componentes/carteirinhaImp/classes/CodigoDeBarra.inc.php';
require_once 'componentes/carteirinhaImp/classes/Gd.inc.php';

class CartImp {
   private static $dados_cartao;
   static public function setParam($param) {
      if (!empty($param)) {
         if (isset($param['barcode']) && $param['barcode']) {
            $caminho_destino_cod = isset($param['param_barcode']['caminho_codigo_barras']) ? $param['param_barcode']['caminho_codigo_barras'] : false;
            if ($caminho_destino_cod) {
               CodigoDeBarra::$caminho_codigo_barras = $caminho_destino_cod;
               if (isset($param['param_barcode']['cod']) && !empty($param['param_barcode']['cod'])) {
                  $cod = $param['param_barcode']['cod'];
                  $cod = Util::addZero($cod, 11);
                  CodigoDeBarra::$nome_codigo_barras = $cod . '.jpg';
                  $ret = CodigoDeBarra::gerarCodigoBarras($cod);
                  $param['dados']['codigo_barras'] = $cod . '.jpg';
                  $param['dados']['caminho_codigo_barras'] = $caminho_destino_cod;
                  $param['dados']['x_codigo_barras'] = $param['param_barcode']['x_codigo_barras'];
                  $param['dados']['y_codigo_barras'] = $param['param_barcode']['y_codigo_barras'];
                  if (!$ret) {
                     die('Falha ao gerar código de barras.');
                  }
               }
            }
         }
         //----------------------------------------- FRENTE -----------------------------------------
         // Caminho onde está a imagem
         Carteirinha::$cart_frente_caminho_origem = $param['frente']['cart_frente_caminho_origem'];
         // Caminho para onde vai a imagem
         Carteirinha::$cart_frente_caminho_destino = $param['frente']['cart_frente_caminho_destino'];
         // Nome da imagem origem, nome do modelo
         Carteirinha::$cart_frente_nome_inicio = $param['frente']['cart_frente_nome_inicio'];
         // Nome da imagem destino
         Carteirinha::$cart_frente_nome_fim = $param['frente']['cart_frente_nome_fim'];
         //-----------------------------------------VERSO-----------------------------------------
         // Caminho onde está a imagem
         Carteirinha::$cart_verso_caminho_origem = $param['verso']['cart_verso_caminho_origem'];
         // Caminho para onde vai a imagem
         Carteirinha::$cart_verso_caminho_destino = $param['verso']['cart_verso_caminho_destino'];
         // Nome da imagem origem, nome do modelo
         Carteirinha::$cart_verso_nome_inicio = $param['verso']['cart_verso_nome_inicio'];
         // Nome da imagem destino
         Carteirinha::$cart_verso_nome_fim = $param['verso']['cart_verso_nome_fim'];
         self::$dados_cartao = $param['dados'];
      } else {
         die('Falha ao carregar parâmetros!');
      }
   }

   public static function gerarCartao() {
      $ret = Carteirinha::gerarCarteirinha(self::$dados_cartao);
      return $ret;
   }
}

/*
$dados = array();
//---------------------------FRENTE---------------------------
// Nome agente
$dados['Frente']['string'][0]['valor'] = strtoupper('Leandro');
$dados['Frente']['string'][0]['x'] = 312;
$dados['Frente']['string'][0]['y'] = 259;

// Nome mãe
$dados['Frente']['string'][1]['valor'] = strtoupper('Neide');
$dados['Frente']['string'][1]['x'] = 312;
$dados['Frente']['string'][1]['y'] = 349;

// Nome pai
$dados['Frente']['string'][2]['valor'] = strtoupper('Edson');
$dados['Frente']['string'][2]['x'] = 312;
$dados['Frente']['string'][2]['y'] = 379;

// Rg
$dados['Frente']['string'][3]['valor'] = 23423432;
$dados['Frente']['string'][3]['x'] = 312;
$dados['Frente']['string'][3]['y'] = 479;

// Cpf
$dados['Frente']['string'][4]['valor'] = 123123;
$dados['Frente']['string'][4]['x'] = 612;
$dados['Frente']['string'][4]['y'] = 479;

// Data expedição
$dados['Frente']['string'][5]['valor'] = '21/07/2013';
$dados['Frente']['string'][5]['x'] = 312;
$dados['Frente']['string'][5]['y'] = 545;

// Data validade
$dados['Frente']['string'][6]['valor'] = '21/12/2014';
$dados['Frente']['string'][6]['x'] = 612;
$dados['Frente']['string'][6]['y'] = 545;

//---------------------------VERSO---------------------------
// Nome da entidade
$dados['Verso']['string'][0]['valor'] = 'Entidade fantasma';
$dados['Verso']['string'][0]['x'] = 8;
$dados['Verso']['string'][0]['y'] = 52;

// Cnpj
$dados['Verso']['string'][1]['valor'] = Util::mask('30335390000111', '##.###.###/####-##');
$dados['Verso']['string'][1]['x'] = 676;
$dados['Verso']['string'][1]['y'] = 58;

// Nome do responsável
$dados['Verso']['string'][2]['valor'] = 'Responsável da entiade';
$dados['Verso']['string'][2]['x'] = 7;
$dados['Verso']['string'][2]['y'] = 438;

// Foto 1
$dados['Frente']['img'][0]['nome'] = '117597ROD_3.jpg';
$dados['Frente']['img'][0]['caminho_foto'] = _DIR_FOTO_CIDADAO_;
$dados['Frente']['img'][0]['redimensionar'] = true;
$dados['Frente']['img'][0]['width'] = 257;
$dados['Frente']['img'][0]['heigth'] = 354;
$dados['Frente']['img'][0]['nome_img_temp'] = 'temp.jpg';
$dados['Frente']['img'][0]['caminho_foto_temp'] = 'Images/carteirinha/carteirinhas/';
$dados['Frente']['img'][0]['qualidade'] = 100;
$dados['Frente']['img'][0]['x'] = 660;
$dados['Frente']['img'][0]['y'] = 25;

$cod_code_barras = 28;
$id_agente = 12;


$param = array(	'barcode' 		=> true,
				'param_barcode' => array(	'caminho_codigo_barras' => 'Images/carteirinha/bar/',
											'cod' => $cod_code_barras,
											'nome_codigo_barras' => Util::addZero($cod_code_barras, 11)),
				'frente'        => array(	'cart_frente_caminho_origem' => 'Images/carteirinha/modelo/',
											'cart_frente_caminho_destino' => 'Images/carteirinha/carteirinhas/',
											'cart_frente_nome_inicio' => 'cart_visitante_frentee_v4.jpg',
											'cart_frente_nome_fim' => 'carteirinha_frente_primeira_via_' . $id_agente . '.jpg'),
				'verso' 		=> array(	'cart_verso_caminho_origem' => 'Images/carteirinha/modelo/',
											'cart_verso_caminho_destino' => 'Images/carteirinha/carteirinhas/',
						  					'cart_verso_nome_inicio' => 'cart_visitante_versoo_v4.jpg',
											'cart_verso_nome_fim' => 'carteirinha_verso_primeira_via_' . $id_agente . '.jpg'),
				'dados'			=> $dados);

CartImp::setParam($param);
$ret = CartImp::gerarCartao();

if($ret) {
	die('Cartao gerado corretamente');
}
die('Falha ao gerar cartao');
*/