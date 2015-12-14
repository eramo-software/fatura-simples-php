<?php

require_once __DIR__.'/../../lib/FaturaSimples.php';

class FaturaSimples_VendaTest extends PHPUnit_Framework_TestCase
{
    /**
     * Define os parâmetros de conexão com o servidor.
     */
    public function setUp()
    {
        FaturaSimples::configure('https://docs.faturasimples.com.br/api-tests', '2y6v8qnX3SUHZb0sanKWrQUnTAY');
    }

    /**
     * Criação de nova venda.
     */
    public function testCriar()
    {
        $params = array(
            'data' => '12/09/2014',
            'cliente' => 'ERAMO SOFTWARE',
            'cliente_cnpj' => '17737572000150',
            'servico' => 'Desenvolvimento de Sistemas',
            'discriminacao' => "Desenvolvimento\n\nPeríodo de 01/09/2014 a 30/09/2015",
            'valor_venda' => 123.22,
            'emissao_nfse' => FaturaSimples_Venda::EMISSAO_NFSE_EMITIR_AGORA,
            'meio_pagamento' => 'Espécie',
            'nfse_municipio_emissao' => 2611606,
            'nfse_item_servico' => 103,
            'nfse_cnae' => 6311900,
            'nfse_inscricao_municipal' => 123456,
            'nfse_optante_simples_nacional' => FaturaSimples_Venda::SIM,
            'nfse_incentivador_cultural' => FaturaSimples_Venda::NAO,
        );

        $result = json_decode(FaturaSimples_Venda::criar($params), true);

        $this->assertContains('api/venda', $result['request_uri']);
        $this->assertEquals('POST', $result['method']);
        $this->assertEquals('2y6v8qnX3SUHZb0sanKWrQUnTAY', $result['api_key']);

        foreach ($result['post'] as $field => $value) {
            $this->assertEquals($value, $params[$field]);
        }
    }

    /**
     * Selecionar um registro.
     */
    public function testSelecionar()
    {
        $result = json_decode(FaturaSimples_Venda::selecionar(47165), true);

        $this->assertContains('api/venda/47165', $result['request_uri']);
        $this->assertEquals('GET', $result['method']);
    }

    /**
     * Selecionar um registro.
     */
    public function testDeletar()
    {
        $result = json_decode(FaturaSimples_Venda::deletar(47165), true);

        $this->assertContains('api/venda/47165', $result['request_uri']);
        $this->assertEquals('DELETE', $result['method']);
    }

    /**
     * Atualizar um registro.
     */
    public function testUpdate()
    {
        $params = array(
            'data' => '12/09/2014',
            'cliente' => 'ERAMO SOFTWARE',
            'cliente_cnpj' => '17737572000150',
            'servico' => 'Desenvolvimento de Sistemas',
            'discriminacao' => "Desenvolvimento\n\nPeríodo de 01/09/2014 a 30/09/2015",
            'valor_venda' => 123.22,
        );

        $result = json_decode(FaturaSimples_Venda::atualizar(47165, $params), true);

        $this->assertContains('api/venda/47165', $result['request_uri']);
        $this->assertEquals('POST', $result['method']);

        foreach ($result['post'] as $field => $value) {
            $this->assertEquals($value, $params[$field]);
        }
    }

    /**
     * Cancelamento de NFS-e.
     */
    public function testNfseCancelar()
    {
        $result = json_decode(FaturaSimples_Venda::nfseCancelar(47165, FaturaSimples_Venda::NFSE_CANCELAR_ERRO_EMISSAO), true);

        $this->assertContains('api/venda/47165/nfse-cancelar', $result['request_uri']);
        $this->assertEquals('POST', $result['method']);
        $this->assertEquals(1, $result['post']['cancelamento_codigo']);
        $this->assertEquals('Erro de preenchimento dos dados da NFe.', $result['post']['cancelamento_motivo']);
    }

    /**
     * Cancelamento de NFS-e.
     */
    public function testNfseCancelar2()
    {
        $motivo = 'motivo do cancelamento preicsa ser informado quando o tipo de cancelamento é outros';

        $result = json_decode(FaturaSimples_Venda::nfseCancelar(47165, FaturaSimples_Venda::NFSE_CANCELAR_OUTROS, $motivo), true);

        $this->assertContains('api/venda/47165/nfse-cancelar', $result['request_uri']);
        $this->assertEquals('POST', $result['method']);
        $this->assertEquals(4, $result['post']['cancelamento_codigo']);
        $this->assertEquals($motivo, $result['post']['cancelamento_motivo']);
    }

    /**
     * Cancelamento de NFS-e.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Motivo do cancelamento deve conter
     */
    public function testNfseCancelar3()
    {
        $result = json_decode(FaturaSimples_Venda::nfseCancelar(47165, FaturaSimples_Venda::NFSE_CANCELAR_OUTROS, 'muito pequeno'), true);
    }

    /**
     * Cancelamento de nota fiscal com codigo incorreto.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Cancelamento deve estar entre
     */
    public function testNfseCancelarCodigoIncorreto()
    {
        FaturaSimples_Venda::nfseCancelar(47165, 6);
    }

    /**
     * Cancelamento de nota fiscal com codigo incorreto.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Cancelamento deve estar entre
     */
    public function testNfseCancelarCodigoIncorreto2()
    {
        FaturaSimples_Venda::nfseCancelar(47165, -1);
    }

    /**
     * Teste de listagem.
     */
    public function testListar()
    {
        $result = json_decode(FaturaSimples_Venda::listar(0, 44), true);

        $this->assertContains('api/venda', $result['request_uri']);
        $this->assertContains('inicio=0', $result['request_uri']);
        $this->assertContains('limite=44', $result['request_uri']);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals(0, $result['get']['inicio']);
        $this->assertEquals(44, $result['get']['limite']);
    }

    /**
     * Confirmação de recebimento sem data.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Data de Recebimento precisa ser informada e estar no pad
     */
    public function testRecebimentoConfirmarSemData()
    {
        FaturaSimples_Venda::recebimentoConfirmar(47165, null);
    }

    /**
     * Confirmação de recebimento.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Data de Recebimento precisa ser informada e estar no pad
     */
    public function testRecebimentoConfirmarDataIncorreta()
    {
        FaturaSimples_Venda::recebimentoConfirmar(47165, '12/12/12');
    }

    /**
     * Confirmação de recebimento.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Data de Recebimento precisa ser informada e estar no pad
     */
    public function testRecebimentoConfirmarDataIncorreta2()
    {
        FaturaSimples_Venda::recebimentoConfirmar(47165, '12/12');
    }

    /**
     * Confirmação de recebimento.
     */
    public function testRecebimentoConfirmarDataCorreta()
    {
        $result = json_decode(FaturaSimples_Venda::recebimentoConfirmar(47165, '2012-12-12'), true);

        $this->assertContains('api/venda/47165/recebimento-confirmar', $result['request_uri']);

        $this->assertEquals('POST', $result['method']);
        $this->assertEquals('2012-12-12', $result['post']['data_recebimento']);
    }

    /**
     * Confirmação de recebimento.
     */
    public function testRecebimentoConfirmarDataCorretaComValor()
    {
        $result = json_decode(FaturaSimples_Venda::recebimentoConfirmar(47165, '2012-12-12', 1000.22), true);

        $this->assertContains('api/venda/47165/recebimento-confirmar', $result['request_uri']);

        $this->assertEquals('POST', $result['method']);
        $this->assertEquals('2012-12-12', $result['post']['data_recebimento']);
        $this->assertEquals(1000.22, $result['post']['valor_recebido']);
    }

    /**
     * Confirmação de recebimento com valor incorreto.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Caso definido, o Valor Recebido precisa ser um inteir
     */
    public function testRecebimentoConfirmarDataCorretaComValorIncorreto()
    {
        FaturaSimples_Venda::recebimentoConfirmar(47165, '2012-12-12', 'abc');
    }

    /**
     * Confirmação de recebimento com valor incorreto.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Caso definido, o Valor Recebido precisa ser um inteir
     */
    public function testRecebimentoConfirmarDataCorretaComValorIncorreto2()
    {
        FaturaSimples_Venda::recebimentoConfirmar(47165, '2012-12-12', '-200');
    }

    /**
     * Confirmação de recebimento com valor incorreto.
     *
     * @expectedException Exception
     * @expectedExceptionMessage Caso definido, o Valor Recebido precisa ser um inteir
     */
    public function testRecebimentoConfirmarDataCorretaComValorIncorreto3()
    {
        FaturaSimples_Venda::recebimentoConfirmar(47165, '2012-12-12', -200);
    }

    /**
     * Confirmação de recebimento com valor correto.
     */
    public function testRecebimentoConfirmarDataCorretaComValorCorreto()
    {
        $result = json_decode(FaturaSimples_Venda::recebimentoConfirmar(47165, '2012-12-12', 123.45), true);

        $this->assertContains('api/venda/47165/recebimento-confirmar', $result['request_uri']);

        $this->assertEquals('POST', $result['method']);
        $this->assertEquals('2012-12-12', $result['post']['data_recebimento']);
        $this->assertEquals(123.45, $result['post']['valor_recebido']);
    }

    /**
     * Confirmação de recebimento com valor correto.
     */
    public function testBoletoAtualizar()
    {
        $result = json_decode(FaturaSimples_Venda::boletoAtualizar(1, 3, '2015-12-30', 123.45, 1.23, 3.21, 2.11, 2.3), true);

        $this->assertContains('api/venda/1/boleto-atualizar', $result['request_uri']);

        $this->assertEquals('POST', $result['method']);
        $this->assertEquals('2015-12-30', $result['post']['data_vencimento_novo']);
        $this->assertEquals(123.45, $result['post']['valor_novo']);
        $this->assertEquals(1.23, $result['post']['multa']);
        $this->assertEquals(3.21, $result['post']['multa_total']);
        $this->assertEquals(2.11, $result['post']['juros_mensal']);
        $this->assertEquals(2.3, $result['post']['juros_mensal_total']);
        $this->assertEquals(3, $result['post']['parcela']);
    }

    /**
     * Atualização de boleto com data inválida
     *
     * @expectedException Exception
     * @expectedExceptionMessage Data de Recebimento precisa ser informada e estar no
     */
    public function testBoletoAtualizarDataInvalida()
    {
        FaturaSimples_Venda::boletoAtualizar(47165, 1, '20-12-12', 123.45);
    }
    
    /**
     * Atualização de boleto com valor inválido
     *
     * @expectedException Exception
     * @expectedExceptionMessage O Valor precisa ser um inteiro ou float
     */
    public function testBoletoAtualizarValorInvalido()
    {
        FaturaSimples_Venda::boletoAtualizar(47165, 1, '2015-12-12', -1000);
    }
}
