<?php

class FaturaSimples_Venda extends FaturaSimples {

    /**
     * Constante para uso na requisicao
     * @var int
     */
    const SIM = 1;
    
    /**
     * Constante para uso na requisicao
     * @var int
     */
    const NAO = 2;
    
    /**
     * Emitir NFS-e agora
     * @var int
     */
    const EMISSAO_NFSE_EMITIR_AGORA = 1;
    
    /**
     * Aguardar Pagamento p/ Emitir NFS-e
     * @var int
     */
    const EMISSAO_NFSE_AGUARDAR_PAGAMENTO = 2;
    
    /**
     * Não Emitir NFS-e por enquanto
     * @var int
     */
    const EMISSAO_NFSE_NAO_EMITIR_NFSE = 3;
    
    /**
     * Status de parcela pendente
     * @var int
     */
    const PARCELA_STATUS_PENDENTE = 1;
    
    /**
     * Status de parcela quitado
     * @var int
     */
    const PARCELA_STATUS_QUITADO = 2;
    
    /**
     * Status de parcela parcial
     * @var int
     */
    const PARCELA_STATUS_PARCIAL = 3;
    
    /**
     * Status de parcela CANCELADO
     * @var int
     */
    const PARCELA_STATUS_CANCELADO = 4;
    
    /**
     * Status de parcela vencida
     * @var int
     */
    const PARCELA_STATUS_VENCIDO = 5;
    
    /**
     * Frequência de agendamento
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_SOMENTE_UMA_VEZ = 0;
    
    /**
     * Frequência de agendamento
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_MENSAL = 1;
    
    /**
     * Frequência de agendamento
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_TRIMENSAL = 3;
    
    /**
     * Frequência de agendamento
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_SEMESTRAL = 6;
    
    /**
     * Frequência de agendamento
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_ANUAL = 12;
    
    /**
     * Motivo de cancelamento de NFSE
     * @var int
     */
    const NFSE_CANCELAR_ERRO_EMISSAO = 1;
    
    /**
     * Motivo de cancelamento de NFSE
     * @var int
     */
    const NFSE_CANCELAR_SERVICO_NAO_PRESTADO = 2;
    
    /**
     * Motivo de cancelamento de NFSE
     * @var int
     */
    const NFSE_CANCELAR_DUPLICIDADE = 3;
    
    /**
     * Motivo de cancelamento de NFSE
     * @var int
     */
    const NFSE_CANCELAR_OUTROS = 4;
    
	public static function criar( $params ){
	    return self::_request( "api/venda", "POST" , $params );
	}
    
	public static function atualizar( $id, $params ){
	    return self::_request( "api/venda/{$id}", "POST", $params );
	}
    
	public static function selecionar( $id ){
	    return self::_request( "api/venda/{$id}", "GET" );
	}
    
	public static function deletar( $id ){
	    return self::_request( "api/venda/{$id}", "DELETE" );
	}
    
	public static function listar( $offset = 0, $limit = 10 ){
	    return self::_request( "api/venda?offset={$offset}&limit={$limit}", "GET" );
	}
    
	/**
	 * 
	 * @param int $id Id da venda no sistema
	 * @param int $motivo Uma das constantes NFSE_CANCELAR_*
	 * @return Ambigous <string, mixed>
	 */
	public static function nfseCancelar( $id, $codigo ){
	    
	    if( !in_array($codigo, array(1,2,3,4)) ){
	        throw new Exception(__CLASS__.": Código do Cancelamento deve estar entre [1,2,3,4].");
	    }
	    
	    return self::_request( "api/venda/{$id}/nfse-cancelar", "POST", array('cancelamento_codigo' => $codigo) );
	}
	
	/**
	 * Realiza uma requisição à API usando cURL
	 * @param String $path
	 * @param String $method Método do HTTP a ser utilizado: GET, POST, DELETE
	 * @param String[] $params
	 * @throws Exception
	 */
	protected static function _request( $path, $method, $params = array() ){
	    
	    $curlOpts = array(
            CURLOPT_CUSTOMREQUEST => $method
	    );
	    
	    if( $method === "POST" ){
	        
	        if( !is_array($params) || count($params) === 0 ){
	            throw new Exception(__CLASS__ . ": não é possível realizar uma requisição sem parâmetros.");
	        }
	        
	        $curlOpts[CURLOPT_POST] = 1;
	    }
	    
	    // Adiciona os parâmetros na requisição convertendo arrays para JSON quando necessário
	    if( is_array($params) && count($params) > 0 ){
	        $curlOpts[CURLOPT_POSTFIELDS] = $params;
	    }
	    
	    $ret = self::_curl( self::$endpoint."/".$path, $curlOpts );
	    
	    return $ret;
	}

}















