<?php

// Valida funções necessárias do PHP
foreach( array('curl_init' => "CURL", 'json_decode' => "JSON") as $function => $extension ) {
	if (!function_exists($function)) {
	  throw new Exception("A API do Fatura Sinples precisa da extensão do PHP {$extension}.");
	}
}

require(dirname(__FILE__) . '/FaturaSimples/FaturaSimples.php');
require(dirname(__FILE__) . '/FaturaSimples/Venda.php');

/*
$params = array(
    "data" => "12/09/2014",
    "cliente" => "ERAMO SOFTWARE",
    "cliente_cnpj" => "17737572000150",
    "servico" => "Desenvolvimento de Sistemas",
    "discriminacao" => "Desenvolvimento\n\nPeríodo de 01/09/2014 a 30/09/2015",
    "valor_venda" => 123.22,
    "emissao_nfse" => FaturaSimples_Venda::EMISSAO_NFSE_EMITIR_AGORA,
    "meio_pagamento" => "Espécie",
    "nfse_municipio_emissao" => 2611606,
    "nfse_item_servico" => 103,
    "nfse_cnae" => 6311900,
    "nfse_inscricao_municipal" => 123456,
    "nfse_optante_simples_nacional" => FaturaSimples_Venda::SIM,
    "nfse_incentivador_cultural" => FaturaSimples_Venda::NAO
);

FaturaSimples::configure("http://dev/fenix_nfse/", "2y6v8qnX3SUHZb0sanKWrQUnTAY");

$venda = FaturaSimples_Venda::criar( $params );

$venda = FaturaSimples_Venda::selecionar( 47165 );

$venda = FaturaSimples_Venda::deletar( 47163 );

$params = array(
    'discriminacao' => 'Nova Atualizada ' . rand(0,21123321)
);

$venda = FaturaSimples_Venda::atualizar( 47165, $params );

$venda = FaturaSimples_Venda::nfseCancelar( 47165, FaturaSimples_Venda::NFSE_CANCELAR_ERRO_EMISSAO );

$venda = FaturaSimples_Venda::listar(0, 1);

echo $venda;
*/
