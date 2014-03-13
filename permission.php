<?php
$admin = false;
$permissao = array(
    'admin' => array(29,34)
);
$admin = in_array($_SESSION['id_user'], $permissao['admin']);