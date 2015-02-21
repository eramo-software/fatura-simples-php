<?php

abstract class FaturaSimples
{
    /**
     * Constantes de operadores de filtros.
     *
     * @var string
     */
    const FILTRO_EQ = 'eq';
    const FILTRO_NEQ = 'neq';
    const FILTRO_GT = 'gt';
    const FILTRO_LT = 'lt';
    const FILTRO_BETWEEN = 'between';
    const FILTRO_ISNULL = 'isnull';
    const FILTRO_ISNOTNULL = 'isnotnull';
    const FILTRO_CONTAINS = 'contains';
    const FILTRO_NOTCONTAINS = 'notcontains';

    /**
     * Chave de API.
     *
     * @var String
     */
    protected static $apiKey = "";

    /**
     * Base do endpoint da api de uma instalação do Fatura Simples.
     *
     * @var String
     */
    protected static $endpoint = "";

    /**
     * Versão da API sendo utilizada.
     *
     * @var String
     */
    protected static $apiVersion = null;

    /**
     * Reseta as configurações da classe para permitir uso em outra instância.
     */
    public static function reset()
    {
        self::$apiKey = "";
        self::$endpoint = "";
        self::$apiVersion = "";
    }

    /**
     * Configura o objeto com a chave de API e o endpoint da instalação.
     *
     * @param String $endpoint Domínio da sua instalação
     * @param String $apiKey   Chave de api gerada dentro do Fatura Simples
     */
    public static function configure($endpoint, $apiKey, $apiVersion = null)
    {
        if (strlen($apiKey) > 10) {
            self::$apiKey = $apiKey;
        } else {
            throw new Exception(__CLASS__.': informe uma chave de api válida.');
        }

        if (strlen($endpoint) > 0) {
            // Aceita que o endpoint seja passado completo como uma URL
            if (stripos($endpoint, "http") !== false) {
                self::$endpoint = $endpoint;
            }
            // Caso padrão onde se passa somente o domínio
            else {
                self::$endpoint = "https://{$endpoint}.faturasimples.com.br";
            }
        }

        if ($apiVersion !== null) {
            self::$apiVersion = $apiVersion;
        }

        if (!strlen(self::$endpoint) || !preg_match("/^https?:\/\/([a-z0-9]{1,})/", self::$endpoint)) {
            throw new Exception(__CLASS__.": informe uma domínio válido. Você deve informar somente o nome da empresa, ex: 'suaempresa'.");
        }

        return true;
    }

    /**
     * Executa uma requisição simples via curl.
     *
     * @param String  $url
     * @param mixed[] $options
     *
     * @return String Resposta da requisição
     */
    protected static function _curl($url, $options = array())
    {
        if (!strlen(self::$apiKey)) {
            throw new Exception(__CLASS__.": Utilize o método FaturaSimples::configure(\$endpoint, \$apiKey) antes de realizar chamadas.");
        }

        $curl = curl_init();

        $headers = array('Authorization: Basic '.base64_encode(self::$apiKey.":"));

        if (self::$apiVersion !== null) {
            $headers[] = 'FaturaSimples-Versao: '.self::$apiVersion;
        }

        $optionsDefault = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_CAINFO => dirname(__FILE__).'/../data/ca-certificates.crt',
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER => $headers,
        );

        foreach ($options as $key => $value) {
            $optionsDefault[$key] = $value;
        }

        curl_setopt_array($curl, $optionsDefault);

        $ret = curl_exec($curl);

        if ($ret === false) {
            throw new Exception(__CLASS__.': erro na execução do CURL: '.curl_error($curl));
        }

        curl_close($curl);

        return $ret;
    }

    /**
     * Realiza uma requisição à API usando cURL.
     *
     * @param String   $path
     * @param String   $method Método do HTTP a ser utilizado: GET, POST, DELETE
     * @param String[] $params
     *
     * @throws Exception
     */
    protected static function _request($path, $method, $params = array())
    {
        $curlOpts = array(
                CURLOPT_CUSTOMREQUEST => $method,
        );

        if ($method === "POST") {
            if (!is_array($params) || count($params) === 0) {
                throw new Exception(__CLASS__.": não é possível realizar uma requisição sem parâmetros.");
            }

            $curlOpts[CURLOPT_POST] = 1;
        }

        // Adiciona os parâmetros na requisição convertendo arrays para JSON quando necessário
        if (is_array($params) && count($params) > 0) {
            $curlOpts[CURLOPT_POSTFIELDS] = $params;
        }

        $ret = self::_curl(self::$endpoint."/".$path, $curlOpts);

        return $ret;
    }

    /**
     * Método que deve ser implementado pelas classes dos models retornando a string para compor a URL.
     *
     * @return String
     */
    protected static function _model()
    {
        throw new Exception("A classe FaturaSimples nao pode ser usada diretamente. Utilize uma das subclasses, FaturaSimples_Cliente::, por exemplo.");
    }

    /**
     * Cria um novo registro.
     *
     * @param mixed[] $params
     *
     * @return String JSON
     */
    public static function criar($params)
    {
        return self::_request("api/".static::_model(), "POST", $params);
    }

    /**
     * Atualiza os dados de um registro.
     *
     * @param int     $id
     * @param mixed[] $params
     *
     * @return String JSON
     */
    public static function atualizar($id, $params)
    {
        return self::_request("api/".static::_model()."/{$id}", "POST", $params);
    }

    /**
     * Seleciona um registro.
     *
     * @param int $id
     *
     * @return String JSON
     */
    public static function selecionar($id)
    {
        return self::_request("api/".static::_model()."/{$id}", "GET");
    }

    /**
     * Deleta um registro do sistema.
     *
     * @param int $id
     *
     * @return String JSON
     */
    public static function deletar($id)
    {
        return self::_request("api/".static::_model()."/{$id}", "DELETE");
    }

    /**
     * Lista todos os registros.
     *
     * @param int $inicio
     * @param int $limite
     *
     * @return String JSON
     */
    public static function listar($inicio = 0, $limite = 10, $ordenarColuna = null, $ordenarDirecao = null, $filtros = null)
    {
        $params = array(
            "inicio=".$inicio,
            "limite=".$limite,
        );

        if ($ordenarColuna !== null) {
            $params[] = "ordenarColuna=".$ordenarColuna;
        }

        if ($ordenarDirecao !== null) {
            $params[] = "ordenarDirecao=".$ordenarDirecao;
        }

        if ($filtros !== null) {
            if (is_array($filtros)) {
                $filtros = json_encode($filtros);
            }
            $params[] = "filtros=".urlencode($filtros);
        }

        return self::_request("api/".static::_model()."?".implode("&", $params), "GET");
    }
}
