<?php

/* 
 * Exemplo pagamentos por Mbway
 * 
 */

class MbWay 
{
    // webservice MBWay 
    private $mbwayApi = 'https://www.ifthenpay.com/mbwayws/IfthenPayMBW.asmx/';
    // A sua Mbway key fornecida pela ifthepay
    private $mbwaykey = 'AAA-123456';
    // chave anti-phishing definida
    private $chaveAntiPhishing = 'f261aebfa55138c20caf18556e17cd29';
    //  no caso desta API terá de ter sempre o valor constante “03”
    private $canal = '03';
    // se pretende que o formato devolvido seja em JSON 
    private $json;
    //  id gerado automaticamente (SetPedido/SetPedidoJSON), poderá ser guardado para consulta posterior do estado do pedido
    private $idsPagamento;
    //  Identificador do pagamento a definir pelo cliente (ex. número da fatura, encomenda, etc…); Máximo 25 caracteres. 
    private $referencia;
    private $telemovel;
    // tipo de pagamento, que poderá ser guardado na base de dados para identificar o tipo de pagamento.
    private $paymentType = 'ifthenpaymbway';

	// método para construir a query para os métodos SetPedido/SetPedidoJSON
    private function buildQuery(array $config)
    {
        return http_build_query($config);
    }
    // método para obter o id de pagamento
    public function getIdPedido()
    {
        return $this->idsPagamento;
    }
    // método para efectuar o envio de pedidos de pagamentos MBWay
    public function setPedido(bool $json, string $referencia, string $valor, string $telemovel, string $email)
    {
        $this->json = $json;
        $this->referencia = $referencia;
        $this->telemovel = $telemovel;
        
		$opts = array('http' =>
			array(
				'method'  => 'POST', // pode ser GET || POST
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $this->buildQuery( 
                    array(
                        'MbWayKey' => $this->mbwaykey, // parametro  (Obrigatório)
                        'Canal' =>  $this->canal, // parametro  (Obrigatório)
                        'Referencia' => $referencia, // parametro  (Obrigatório)
                        'valor' => $valor, // parametro  (Obrigatório)
                        'nrtlm' => $telemovel, // parametro  (Obrigatório)
                        'email' => $email,
                        'descricao' => $referencia // parametro  (Obrigatório)
                    )
                ),
			)
		);
        // criar um stream context
        $context  = stream_context_create($opts);
        // construção do url, se $json = true SetPedidoJSON caso contrário SetPedido
		$url = $json ? $this->mbwayApi . 'SetPedidoJSON' : $this->mbwayApi . 'SetPedido';
        // obter o conteúdo do webservice
        $result = file_get_contents($url, false, $context);
        // conversão do resultado
        $result = $json ? json_decode($result) : new SimpleXMLElement($result);
        $this->idsPagamento = $json ? $result->IdPedido : (string) $result->IdPedido;
        // output do resultado no browser
        $this->render($result);
    }
    // método para consultar o estado do(s) pedido(s)
    public function getEstadoPedido(bool $json, string $idsPagamento)
    {
        $this->json = $json;

        $opts = array('http' =>
			array(
				'method'  => 'POST', // pode ser GET || POST
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $this->buildQuery( 
                    array(
                        'MbWayKey' => $this->mbwaykey,
                        'Canal' =>  $this->canal,
                        'idspagamento' => $idsPagamento,
                    )
                ),
			)
		);
        // criar um stream context
        $context  = stream_context_create($opts);
        // construção do url, se $json = true EstadoPedidosJSON caso contrário EstadoPedido
		$url = $json ? $this->mbwayApi . 'EstadoPedidosJSON' : $this->mbwayApi . 'EstadoPedido';
        // obter o conteúdo do webservice
        $result = file_get_contents($url, false, $context);
        // conversão do resultado
        $result = $json ? json_decode($result) : new SimpleXMLElement($result);
        // condição que testa se existem erros
        if (empty($result->EstadoPedidos) && $result->Estado === '901') {
            echo $result->MsgDescricao;
        }
        return $result;
    }
    // método que permite apresentar o resultado do método setPedido
    private function render($result)
    {
        if ($result->Estado == '000') {
            // apenas esconde o formulário
            echo '<script type="text/javascript">
                    document.getElementsByTagName("form")[0].style.display = "none";
                </script>';

            echo '<div><table style="width: auto;min-width: 280px;max-width: 320px;padding: 5px;font-size: 11px;color: #374953;border: 1px solid #dddddd; margin-top: 10px;"><tbody><tr><td style="padding: 5px;" colspan="2"><div align="left"><img src="https://ifthenpay.com/img/mbway.png" alt="mbway"></div></td></tr><tr><td align="left" style=" padding:10px; font-weight:bold; text-align:left">Telem&oacute;vel:</td><td align="left" style=" padding:10px; text-align:left">' . $this->telemovel . '</td></tr><tr><td align="left" style=" padding:10px; padding-top:10px; font-weight:bold; text-align:left">Encomenda:</td><td align="left" style=" padding:10px; padding-top:10px; text-align:left">#' . $this->referencia . '</td></tr><tr><td align="left" style="padding:10px; padding-bottom:15px; padding-top:10px; font-weight:bold; text-align:left">Valor:</td><td style="padding:10px; padding-bottom:15px; padding-top:10px; text-align:left">' . number_format($this->json ?$result->Valor : (string) $result->Valor, 2) . ' EUR</td></tr><tr><td style="font-size: x-small; padding:0; border: 0px; text-align:center;" colspan="2">Por favor verifique na App MBWAY e proceda ao pagamento da sua encomenda. <br>Processado por <a href="https://www.ifthenpay.com" target="_blanck">Ifthenpay</a></td></tr></tbody></table></div>';
        } else {
            echo 'Ocorreu um erro: ' . $result->MsgDescricao . '. <br/>Não foi possível concluir o pagamento.';
        }
    }
    // método para o callback
    public function callback(array $http_get)
    {
		//chave=[CHAVE_ANTI_PHISHING]&referencia=[REFERENCIA]&idpedido=[ID_TRANSACAO]&valor=[VALOR]&estado=[ESTADO]
        $chave_ap_ext = $http_get['chave'];
        $idpedido = $http_get['idpedido'];
		$order_id = $http_get['referencia'];
		$valor = $http_get['valor'];
        $estado = $http_get['estado'];
        $orderTotal = '5.00'; // simula o valor da encomenda, este valor noemalmente é retirado da base de dados.

        //verifica se a chave anti-phishing devolvida pela ifthen corresponde � chave definida e se o pagamento é do tipo mbway
		if($this->chaveAntiPhishing === $chave_ap_ext && $this->paymentType == 'ifthenpaymbway') {
            // esconder formulário e tabela do resultado do método setPedido
            echo '<script type="text/javascript">
                    document.getElementsByTagName("table")[0].style.display = "none";
                    document.getElementsByTagName("form")[0].style.display = "none";
                </script>';
            // verifica se o valor no callback corresponde ao valor da encomenda
			if ($valor === $orderTotal) {
                echo "Encomenda PAGA";
                http_response_code(200);
			} else {
                echo "Valor inválido";
                http_response_code(200);
            }
		} else {
            echo "Chave inválida";
            http_response_code(200);
        }
        exit();
	}
}
