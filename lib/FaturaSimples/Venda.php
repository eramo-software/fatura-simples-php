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
    
    /**
     * Retorna o nome do model do objeto atual
     * @return string
     */
    protected static function _model(){
        return "venda";
    }
    
	/**
	 * Faz o cancelamento da NFS-e de uma venda
	 * @param int $id Id da venda no sistema
	 * @param int $codigo Uma das constantes NFSE_CANCELAR_*
	 * @return String JSON
	 */
	public static function nfseCancelar( $id, $codigo ){
	    
	    if( !in_array($codigo, array(1,2,3,4)) ){
	        throw new Exception(__CLASS__.": Código do Cancelamento deve estar entre [1,2,3,4].");
	    }
	    
	    return self::_request( "api/".static::_model()."/{$id}/nfse-cancelar", "POST", array('cancelamento_codigo' => $codigo) );
	}
	
}















