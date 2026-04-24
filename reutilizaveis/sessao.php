<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['transacoes'])){
    $_SESSION['transacoes'] = [];
}