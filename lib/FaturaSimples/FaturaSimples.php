<?php

abstract class FaturaSimples {

    /**
     * Chave de API
     * @var String
     */
	protected static $apiKey = "";

	/**
	 * Base do endpoint da api de uma instalação do Fatura Simples
	 * @var String
	 */
	protected static $endpoint = "";

	/**
	 * Reseta as configurações da classe para permitir uso em outra instância
	 */
	public static function reset(){
	    self::$apiKey = "";
	    self::$endpoint = "";
	}
	
	/**
	 * Configura o objeto com a chave de API e o endpoint da instalação
	 * @param String $endpoint Domínio da sua instalação
	 * @param String $apiKey Chave de api gerada dentro do Fatura Simples
	 */
	public static function configure($endpoint, $apiKey){

		if(strlen($apiKey) > 10){
			self::$apiKey = $apiKey;
		} else {
			throw new Exception(__CLASS__ . ': informe uma chave de api válida.');
		}

		if(strlen($endpoint) > 0){
			// Aceita que o endpoint seja passado completo como uma URL 
		    if( stripos($endpoint, "http") !== false ){
		        self::$endpoint = $endpoint;       
		    }
		    // Caso padrão onde se passa somente o domínio
		    else {
		        self::$endpoint = "https://{$endpoint}.faturasimples.com.br";
		    }
		}

		if( !strlen(self::$endpoint) || !preg_match("/^https?:\/\/([a-z0-9]{1,})/", self::$endpoint) ){
			throw new Exception(__CLASS__ . ": informe uma domínio válido. Você deve informar somente o nome da empresa, ex: 'suaempresa'.");
		}

		return true;
	}

	/**
	 * Executa uma requisição simples via curl
	 * @param String $url
	 * @param mixed[] $options
	 * @return String Resposta da requisição
	 */
	protected static function _curl($url, $options = array()){
	    
	    $curl = curl_init();
	
	    $optionsDefault = array(
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_URL => $url,
	            CURLOPT_SSLVERSION => 3,
	            CURLOPT_CAINFO => dirname(__FILE__).'/../data/ca-certificates.crt',
	            CURLOPT_SSL_VERIFYPEER => true,
	            CURLOPT_SSL_VERIFYHOST => true,
	            CURLOPT_HTTPHEADER => array('Authorization: Basic '.base64_encode(self::$apiKey.":")),
	    );
	
	    foreach($options as $key => $value){
	        $optionsDefault[$key] = $value;
	    }
	
	    curl_setopt_array($curl, $optionsDefault);
	
	    $ret = curl_exec($curl);
	
	    if($ret === false){
	        throw new Exception(__CLASS__ . ': erro na execução do CURL: '.curl_error($curl));
	    }
	
	    curl_close($curl);
	
	    return $ret;
	}
}















