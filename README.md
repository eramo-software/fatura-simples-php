API do Fatura Simples em PHP
========
[![Build Status](https://travis-ci.org/eramo-software/fatura-simples-php.svg?branch=master)](https://travis-ci.org/eramo-software/fatura-simples-php)

Registre-se para começar a usar em https://www.faturasimples.com.br

Requerimentos
------------

PHP 5.3+

Instalação com Composer
------------

Você pode instalar a interface via [Composer](http://getcomposer.org/). Adicione ao seu arquivo **composer.json**:

    {
      "require": {
        "eramo-software/fatura-simples-php": "dev-master"
      }
    }
    
E depois execute:

    composer install

Para usar, ou utilize o autoload do [Composer Autoloader](https://getcomposer.org/doc/00-intro.md#autoloading):

    require_once('vendor/autoload.php');
    
Ou carregue manualmente:

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

Para registrar uma venda emissão de NFS-e:

    FaturaSimples::configure("https://suaempresa.faturasimples.com.br", "SUA_CHAVE_API");
    $dados = array(
        "data" => "14/09/2014",
        "cliente" => "ERAMO SOFTWARE",
        "cliente_cnpj" => "17737572000150",
        "servico" => "Consultoria em TI",
        "discriminacao" => "10 horas de serviço de consultoria",
        "valor_venda" => 2500,
        "emissao_nfse" => FaturaSimples_Venda::EMISSAO_NFSE_EMITIR_AGORA,
        "meio_pagamento" => "Depósito",
        "nfse_municipio_emissao" => 2611606,
        "nfse_item_servico" => 103,
        "nfse_cnae" => 6311900,
        "nfse_inscricao_municipal" => 123456,
        "nfse_optante_simples_nacional" => FaturaSimples_Venda::SIM,
        "nfse_incentivador_cultural" => FaturaSimples_Venda::NAO
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




