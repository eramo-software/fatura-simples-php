<?php 

require_once __DIR__.'/../../lib/FaturaSimples.php';

class FaturaSimples_VendaTest extends PHPUnit_Framework_TestCase {

    /**
     * Define os parâmetros de conexão com o servidor
     */
    public function setUp(){
        FaturaSimples::configure("https://docs.faturasimples.com.br/api-tests", "2y6v8qnX3SUHZb0sanKWrQUnTAY");
    }
    
    /**
     * Criação de nova venda
     */
    public function testCriar(){
        
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
        
        $result = json_decode( FaturaSimples_Venda::criar( $params ), true );
        
        $this->assertContains( "api/venda" , $result['request_uri'] );
        $this->assertEquals( "POST" , $result['method'] );
        $this->assertEquals( "2y6v8qnX3SUHZb0sanKWrQUnTAY" , $result['api_key'] );
        
        foreach( $result['post'] as $field => $value ){
            $this->assertEquals( $value , $params[$field] );
        }
        
    }
    
    /**
     * Selecionar um registro
     */
    public function testSelecionar(){
        
        $result = json_decode( FaturaSimples_Venda::selecionar( 47165 ), true );
        
        $this->assertContains( "api/venda/47165" , $result['request_uri'] );
        $this->assertEquals( "GET" , $result['method'] );
        
    }
    
    /**
     * Selecionar um registro
     */
    public function testDeletar(){
        
        $result = json_decode( FaturaSimples_Venda::deletar( 47165 ), true );
        
        $this->assertContains( "api/venda/47165" , $result['request_uri'] );
        $this->assertEquals( "DELETE" , $result['method'] );
        
    }
    
    /**
     * Atualizar um registro
     */
    public function testUpdate(){
        
        $params = array(
            "data" => "12/09/2014",
            "cliente" => "ERAMO SOFTWARE",
            "cliente_cnpj" => "17737572000150",
            "servico" => "Desenvolvimento de Sistemas",
            "discriminacao" => "Desenvolvimento\n\nPeríodo de 01/09/2014 a 30/09/2015",
            "valor_venda" => 123.22
        );
        
        $result = json_decode( FaturaSimples_Venda::atualizar( 47165, $params ), true );
        
        $this->assertContains( "api/venda/47165" , $result['request_uri'] );
        $this->assertEquals( "POST" , $result['method'] );
        
        foreach( $result['post'] as $field => $value ){
            $this->assertEquals( $value , $params[$field] );
        }
        
    }

    /**
     * Cancelamento de NFS-e
     */
    public function testNfseCancelar(){
    
        $result = json_decode( FaturaSimples_Venda::nfseCancelar( 47165, FaturaSimples_Venda::NFSE_CANCELAR_ERRO_EMISSAO ), true );
        
        $this->assertContains( "api/venda/47165/nfse-cancelar" , $result['request_uri'] );
        $this->assertEquals( "POST" , $result['method'] );
        $this->assertEquals( 1 , $result['post']['cancelamento_codigo'] );
    
    }

    /**
     * Cancelamento de NFS-e
     */
    public function testNfseCancelar2(){
    
        $result = json_decode( FaturaSimples_Venda::nfseCancelar( 47165, FaturaSimples_Venda::NFSE_CANCELAR_OUTROS ), true );
        
        $this->assertContains( "api/venda/47165/nfse-cancelar" , $result['request_uri'] );
        $this->assertEquals( "POST" , $result['method'] );
        $this->assertEquals( 4 , $result['post']['cancelamento_codigo'] );
    
    }

    /**
     * Cancelamento de nota fiscal com codigo incorreto
	 * @expectedException Exception
	 * @expectedExceptionMessage Cancelamento deve estar entre
     */
    public function testNfseCancelarCodigoIncorreto(){
    
        FaturaSimples_Venda::nfseCancelar( 47165, 6 );
    
    }

    /**
     * Cancelamento de nota fiscal com codigo incorreto
	 * @expectedException Exception
	 * @expectedExceptionMessage Cancelamento deve estar entre
     */
    public function testNfseCancelarCodigoIncorreto2(){
    
        FaturaSimples_Venda::nfseCancelar( 47165, -1 );
    
    }
}



















