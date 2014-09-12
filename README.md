Interface com a API do Fatura Simples em PHP
========
[![Build Status](https://travis-ci.org/eramo-software/fatura-simples-php.svg?branch=master)](https://travis-ci.org/eramo-software/fatura-simples-php)

Registre-se para começar a usar em https://www.faturasimples.com.br

Requerimentos
------------

PHP 5.3+

Instalação com Composer
------------

Você pode instalar a interface via Composer[http://getcomposer.org/]. Adicionar ao seu arquivo +composer.json+:

    {
      "require": {
        "eramo-software/fatura-simples-php": "*"
      }
    }
    
E depois execute:

    composer install

Para usar ou utilize o autoload do Composer [https://getcomposer.org/doc/00-intro.md#autoloading]:

    require_once('vendor/autoload.php');
    
Ou manualmente:

    require_once('/path/to/vendor/eramo-software/fatura-simples-php/lib/FaturaSimples.php');

Instalação Manual
------------

Obtenha a última versão disponível com:

    git clone https://github.com/eramo-software/fatura-simples-php

Para usar essa versão, adicione a seguinte linha na sua aplicação:

    require_once("/path/to/fatura-simples-php/lib/FaturaSimples.php");

Iniciando o uso
------------

Um caso de uso extremamente simples:

    FaturaSimples::configure("https://suaempresa.faturasimples.com.br", "SUA_CHAVE_API");
    $dados = array(
        'cliente' => 'NOME DO CLIENTE',
        'servico' => 'Consultoria em TI',
        'valor_venda' => 100.22,
        'emissao_nfse' => FaturaSimples_Venda::EMISSAO_NFSE_NAO_EMITIR_NFSE,
        'meio_pagamento' => 'Espécie'
    );
    $venda = FaturaSimples_Venda::criar( $dados );
    echo $venda;

Documentação
------------

Acesse https://docs.faturasimples.com.br/api/ para a documentação completa da nossa API

Testes
------------

Para executar os testes você precisa instalar o PHPUnit, usando composer execute:

    composer update --dev

Para executar os testes:

    php vendor/bin/phpunit test/FaturaSimples/




