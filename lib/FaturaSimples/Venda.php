<?php

class FaturaSimples_Venda extends FaturaSimples
{
    /**
     * Constante para uso na requisicao.
     *
     * @var int
     */
    const SIM = 1;

    /**
     * Constante para uso na requisicao.
     *
     * @var int
     */
    const NAO = 2;

    /**
     * Emitir NFS-e agora.
     *
     * @var int
     */
    const EMISSAO_NFSE_EMITIR_AGORA = 1;

    /**
     * Aguardar Pagamento p/ Emitir NFS-e.
     *
     * @var int
     */
    const EMISSAO_NFSE_AGUARDAR_PAGAMENTO = 2;

    /**
     * Não Emitir NFS-e por enquanto.
     *
     * @var int
     */
    const EMISSAO_NFSE_NAO_EMITIR_NFSE = 3;

    /**
     * Status de parcela pendente.
     *
     * @var int
     */
    const PARCELA_STATUS_PENDENTE = 1;

    /**
     * Status de parcela quitado.
     *
     * @var int
     */
    const PARCELA_STATUS_QUITADO = 2;

    /**
     * Status de parcela parcial.
     *
     * @var int
     */
    const PARCELA_STATUS_PARCIAL = 3;

    /**
     * Status de parcela CANCELADO.
     *
     * @var int
     */
    const PARCELA_STATUS_CANCELADO = 4;

    /**
     * Status de parcela vencida.
     *
     * @var int
     */
    const PARCELA_STATUS_VENCIDO = 5;

    /**
     * NFS-e com status Emitida.
     *
     * @var int
     */
    const NFSE_STATUS_EMITIDA = 1;

    /**
     * NFS-e com status Cancelada.
     *
     * @var int
     */
    const NFSE_STATUS_CANCELADA = 2;

    /**
     * NFS-e com status Pendente.
     *
     * @var int
     */
    const NFSE_STATUS_PENDENTE = 3;

    /**
     * NFS-e com status Erro.
     *
     * @var int
     */
    const NFSE_STATUS_ERRO = 4;

    /**
     * Frequência de agendamento.
     *
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_SOMENTE_UMA_VEZ = 0;

    /**
     * Frequência de agendamento.
     *
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_MENSAL = 1;

    /**
     * Frequência de agendamento.
     *
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_TRIMENSAL = 3;

    /**
     * Frequência de agendamento.
     *
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_SEMESTRAL = 6;

    /**
     * Frequência de agendamento.
     *
     * @var int
     */
    const AGENDAMENTO_FREQUENCIA_ANUAL = 12;

    /**
     * Motivo de cancelamento de NFSE.
     *
     * @var int
     */
    const NFSE_CANCELAR_ERRO_EMISSAO = 1;

    /**
     * Motivo de cancelamento de NFSE.
     *
     * @var int
     */
    const NFSE_CANCELAR_SERVICO_NAO_PRESTADO = 2;

    /**
     * Motivo de cancelamento de NFSE.
     *
     * @var int
     */
    const NFSE_CANCELAR_DUPLICIDADE = 3;

    /**
     * Motivo de cancelamento de NFSE.
     *
     * @var int
     */
    const NFSE_CANCELAR_OUTROS = 4;

    /**
     * Lista dos códigos de cancelamento permitidos.
     *
     * @var String
     */
    protected static $_nfseCodigosCancelamento = array(
            self::NFSE_CANCELAR_ERRO_EMISSAO => 'Erro de preenchimento dos dados da NFe.',
            self::NFSE_CANCELAR_SERVICO_NAO_PRESTADO => 'Serviço não prestado. Nota emitida para tomador incorreto.',
            self::NFSE_CANCELAR_DUPLICIDADE => 'Nota emitida em duplicidade.',
            self::NFSE_CANCELAR_OUTROS => '-a descrever-',
    );

    /**
     * Retorna o nome do model do objeto atual.
     *
     * @return string
     */
    protected static function _model()
    {
        return 'venda';
    }

    /**
     * Faz o cancelamento da NFS-e de uma venda.
     *
     * @param int $id     Id da venda no sistema
     * @param int $codigo Uma das constantes NFSE_CANCELAR_*
     * @param int $motivo Descrição do motivo do cancelamento com no mínimo 15 caracteres
     *
     * @return String JSON
     */
    public static function nfseCancelar($id, $codigo, $motivo = null)
    {
        if (!in_array($codigo, array(1, 2, 3, 4))) {
            throw new Exception(__CLASS__.': Código do Cancelamento deve estar entre [1,2,3,4].');
        }

        if ($motivo === null) {
            $motivo = self::$_nfseCodigosCancelamento[ $codigo ];
        }

        if (strlen($motivo) < 15) {
            throw new Exception(__CLASS__.': Motivo do cancelamento deve conter no mínimo 15 caracteres.');
        }

        return self::_request('api/'.static::_model()."/{$id}/nfse-cancelar", 'POST', array('cancelamento_codigo' => $codigo, 'cancelamento_motivo' => $motivo));
    }

    /**
     * Faz a confirmação de recebimento de uma venda.
     *
     * @param int    $id              Id da venda no sistema
     * @param String $dataRecebimento Data de recebimento no formato ISO8601, ex: 2015-12-15
     * @param float  $valorRecebido   Valor recebido, opcional. Será considerado o Valor da Venda caso não informado
     *
     * @return String JSON
     */
    public static function recebimentoConfirmar($id, $dataRecebimento, $valorRecebido = null)
    {
        $dados = array();

        if (!preg_match("/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/", $dataRecebimento)) {
            throw new Exception(__CLASS__.': Data de Recebimento precisa ser informada e estar no padrão ISO8601: YYYY-MM-DD');
        }

        $dados['data_recebimento'] = $dataRecebimento;

        if ($valorRecebido !== null) {
            if (floatval($valorRecebido) > 0) {
                $dados['valor_recebido'] = $valorRecebido;
            } else {
                throw new Exception(__CLASS__.': Caso definido, o Valor Recebido precisa ser um inteiro ou float');
            }
        }

        return self::_request('api/'.static::_model()."/{$id}/recebimento-confirmar", 'POST', $dados);
    }

    /**
     * Faz a confirmação de recebimento de uma venda.
     *
     * @param int    $id               Id da venda no sistema
     * @param int    $parcela          Sequencial da parcela na venda: ex: 1, 2, 3
     * @param String $dataVencimento   Nova data de vencimento no formato ISO8601, ex: 2015-12-15
     * @param float  $valor            Novo valor do boleto
     * @param float  $multa            Percentual de multa aplicado
     * @param float  $multaTotal       Valor total da multa aplicada na atualização
     * @param float  $jurosMensal      Percentual de juros mensal aplicado
     * @param float  $jurosMensalTotal Valor total do juros aplicado na atualização
     *
     * @return String JSON
     */
    public static function boletoAtualizar($id, $parcela, $dataVencimento, $valor, $multa = null, $multaTotal = null, $jurosMensal = null, $jurosMensalTotal = null)
    {
        $dados = array();

        if (!preg_match("/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/", $dataVencimento)) {
            throw new Exception(__CLASS__.': Data de Recebimento precisa ser informada e estar no padrão ISO8601: YYYY-MM-DD');
        }

        $dados['data_vencimento_novo'] = $dataVencimento;

        if (floatval($parcela) > 0) {
            $dados['parcela'] = $parcela;
        } else {
            throw new Exception(__CLASS__.': A parcela precisa ser um inteiro maior que zero');
        }

        if (floatval($valor) > 0) {
            $dados['valor_novo'] = $valor;
        } else {
            throw new Exception(__CLASS__.': O Valor precisa ser um inteiro ou float');
        }

        if (floatval($multa) > 0) {
            $dados['multa'] = $multa;
        } else {
            throw new Exception(__CLASS__.': A Multa precisa ser um inteiro ou float');
        }

        if (floatval($multaTotal) > 0) {
            $dados['multa_total'] = $multaTotal;
        } else {
            throw new Exception(__CLASS__.': A Multa Total precisa ser um inteiro ou float');
        }

        if (floatval($jurosMensal) > 0) {
            $dados['juros_mensal'] = $jurosMensal;
        } else {
            throw new Exception(__CLASS__.': O Juros Mensal precisa ser um inteiro ou float');
        }

        if (floatval($jurosMensalTotal) > 0) {
            $dados['juros_mensal_total'] = $jurosMensalTotal;
        } else {
            throw new Exception(__CLASS__.': O Juros Mensal Total precisa ser um inteiro ou float');
        }

        return self::_request('api/'.static::_model()."/{$id}/boleto-atualizar", 'POST', $dados);
    }
}
