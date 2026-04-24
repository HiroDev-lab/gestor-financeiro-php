<?php
require_once 'reutilizaveis/sessao.php';

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit();
?>