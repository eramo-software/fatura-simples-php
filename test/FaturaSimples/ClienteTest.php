<?php 

require_once __DIR__.'/../../lib/FaturaSimples.php';

class FaturaSimples_ClienteTest extends PHPUnit_Framework_TestCase {

    /**
     * Define os parâmetros de conexão com o servidor
     */
    public function setUp(){
        FaturaSimples::configure("https://docs.faturasimples.com.br/api-tests", "2y6v8qnX3SUHZb0sanKWrQUnTAY");
    }
    
    /**
     * Criação de nova cliente
     */
    public function testCriar(){
        
        $params = array(
            "data" => "12/09/2014",
            "nome" => "ERAMO SOFTWARE",
            "cnpj" => "17737572000150"
        );
        
        $result = json_decode( FaturaSimples_Cliente::criar( $params ), true );
        
        $this->assertContains( "api/cliente" , $result['request_uri'] );
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
        
        $result = json_decode( FaturaSimples_Cliente::selecionar( 22 ), true );
        
        $this->assertContains( "api/cliente/22" , $result['request_uri'] );
        $this->assertEquals( "GET" , $result['method'] );
        
    }
    
    /**
     * Selecionar um registro
     */
    public function testDeletar(){
        
        $result = json_decode( FaturaSimples_Cliente::deletar( 22 ), true );
        
        $this->assertContains( "api/cliente/22" , $result['request_uri'] );
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
            "cliente" => "Desenvolvimento de Sistemas",
            "discriminacao" => "Desenvolvimento\n\nPeríodo de 01/09/2014 a 30/09/2015",
            "valor_cliente" => 123.22
        );
        
        $result = json_decode( FaturaSimples_Cliente::atualizar( 22, $params ), true );
        
        $this->assertContains( "api/cliente/22" , $result['request_uri'] );
        $this->assertEquals( "POST" , $result['method'] );
        
        foreach( $result['post'] as $field => $value ){
            $this->assertEquals( $value , $params[$field] );
        }
        
    }

    /**
     * Teste de listagem
     */
    public function testListar(){
    
        $result = json_decode( FaturaSimples_Cliente::listar( 0, 6 ), true );
    
        $this->assertContains( "api/cliente" , $result['request_uri'] );
        $this->assertContains( "offset=0" , $result['request_uri'] );
        $this->assertContains( "limit=6" , $result['request_uri'] );
        $this->assertEquals( "GET" , $result['method'] );
        $this->assertEquals( 0 , $result['get']['offset'] );
        $this->assertEquals( 6 , $result['get']['limit'] );
    
    }
    
}



















