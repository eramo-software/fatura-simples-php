<?php

// Valida funções necessárias do PHP
foreach (array('curl_init' => 'CURL', 'json_decode' => 'JSON') as $function => $extension) {
    if (!function_exists($function)) {
        throw new Exception("A API da Fatura Simples precisa da extensão do PHP {$extension}.");
    }
}

require dirname(__FILE__).'/FaturaSimples/FaturaSimples.php';
require dirname(__FILE__).'/FaturaSimples/Servico.php';
require dirname(__FILE__).'/FaturaSimples/Cliente.php';
require dirname(__FILE__).'/FaturaSimples/Venda.php';
