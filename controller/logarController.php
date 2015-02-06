<?php

require_once '../config/configuracoes.php';

if (!isset($_SESSION['id_user'])) {
    session_start('acesso');
}

function startSession($id_user, $group_id, $name) {
    
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");

    $device = 'Desktop';
    if((int)$iphone) {
        $device = 'Iphone';
    }

    if((int)$ipad) {
        $device = 'Ipad';
    }

    if((int)$android) {
        $device = 'Android';
    }
    
    if (!isset($_SESSION['id_user'])) {
        $_SESSION['id_user'] = $id_user;
        $_SESSION['group_id'] = $group_id;
        $_SESSION['name'] = $name;
        $_SESSION['device'] = $device;
    } else {
        die('Falha ao iniciar sessão!');
    }
}

function conectar() {
    global $dados_bd;
    try {
        $pdo = new PDO('mysql:host=' . $dados_bd['host'] . ';dbname=' . $dados_bd['dbname'] . ';', $dados_bd['user'], $dados_bd['senha']);
    } catch (PDOException $e) {
        echo 'Falha ao conectar no banco de dados: ' . $e->getMessage();
        die;
    }
    return $pdo;
}

function getDadosRequest($req) {
    $dados = array();
    if (isset($req) && !empty($req)) {
        foreach ($req as $key => $value) {
            $dados[$key] = !empty($value) ? trim($value) : '';
        }
    }
    return $dados;
}

function criptografar($string) {
    return sha1($string);
}

function validaAcesso($user, $password) {
    $pdo = conectar();
    $dados_retorno = array();
    $password = criptografar($password);
    $sql = "SELECT id, group_id, name FROM users WHERE username =? AND password=?";
    $statemente = $pdo->prepare($sql);
    $statemente->bindParam(1, $user, PDO::PARAM_STR);
    $statemente->bindParam(2, $password, PDO::PARAM_STR);
    $executa = $statemente->execute();
    if ($executa) {
        if ($statemente) {
            foreach ($statemente as $value) {
                $dados_retorno['id'] = $value['id'];
                $dados_retorno['group_id'] = $value['group_id'];
                $dados_retorno['name'] = $value['name'];
            }
        }
    }
    return $dados_retorno;
}

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
            if($ok) {
                //exec('mysqldump -u root -p "" > system'.date('d/m/Y H:i:s').'.sql');
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