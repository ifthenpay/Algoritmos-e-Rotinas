<?php
	/***************************************************************************
	 *                                                                         *
	 * Função que faz a chamada ao webservice e retorna um array com os dados. *
	 * Para ver como invocar e tratar dados ver mais abaixo.                   *
	 *                                                                         *
	 ***************************************************************************/
	function getPayments($chavebackoffice, $entidade, $subentidade, $dtHrInicio, $dtHrFim,$referencia,$valor, $sandbox){
	
		$resultado ="";
		$resultadoAux ="";
	
		try {
			$parametros = array(
								"chavebackoffice" => $chavebackoffice,
								"entidade" => $entidade,
								"subentidade" => $subentidade,
								"dtHrInicio" => $dtHrInicio,
								"dtHrFim" => $dtHrFim,
								"referencia" => $referencia,
								"valor" => $valor,
								"sandbox" => $sandbox
							);
			
			 $ifmbWsUrl = "http://www.ifthensoftware.com/IfmbWS/IfmbWS.asmx?WSDL"; 
			/**************************************************************************
			 *                                                                        *
			 * Para utilizar o HTTPS tem de ter a extensão do OpenSSL do PHP ativa    *
			 *                                                                        *
			 * $ifmbWsUrl = "https://www.ifthensoftware.com/IfmbWS/IfmbWS.asmx?WSDL"; *
			 *                                                                        *
			 **************************************************************************/
		
			$chamarWs = new SoapClient($ifmbWsUrl,array("trace"=>1));

			$resultado = $chamarWs -> getPaymentsXmlWithSandBox($parametros);
		

			$resultadoAux = (array)$resultado->getPaymentsXmlWithSandBoxResult;
			

			$rv = array_filter($resultadoAux,'is_array');

			if(count($rv, COUNT_RECURSIVE)>0)
				$resultado = (array)$resultado->getPaymentsXmlWithSandBoxResult->Ifmb;
			else
				$resultado = (array)$resultado->getPaymentsXmlWithSandBoxResult;
				

		} catch ( SoapFault $fault ) {
			trigger_error ( "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR );
		}

	
	
		return $resultado;
	}
	
	
	
	
	/********************************************
	 *                                          *
	 * Exemplo de como utilizar a função acima. *
	 *                                          *
	 ********************************************/
	
	//variáveis com informação de teste
	$ifmb_chaveBO = '0000-0000-0000-0000'; //chave que utilizou no momento do registo no nosso site para consultar os pagamentos. Se não sabe entre em contato com a Ifthen.
	$ifmb_entidade = '11202'; //Entidade fornecida pela Ifthen. Ex.: 10559, 11202, 11473, 11604
	$ifmb_subentidade = '000'; //Sub-Entidade fornecida pela Ifthen
	$ifmb_data_hora_inicio = '';
	$ifmb_data_hora_fim = '';
	$ifmb_referencia = '';
	$ifmb_valor = '';
	$ifmb_sandbox = 0; //variável que permite alternar entre o ambiente de produção e o ambiente de testes. 0 - Ambiente de Produção 1 - Ambiente de Testes
	
	//Invocação da função de chamada ao Webservice
	$resultado =getPayments($ifmb_chaveBO, $ifmb_entidade, $ifmb_subentidade, $ifmb_data_hora_inicio, $ifmb_data_hora_fim, $ifmb_referencia, $ifmb_valor, $ifmb_sandbox);

	
	//Forma exemplo de tratar a informação.
	$res="";
	
	foreach($resultado as $item)
	{
		if($item->CodigoErro=="0"){
		$res .= '<b>Entidade:</b> ' . $item->Entidade . '<br />';
		$res .= '<b>Referencia:</b> ' . $item->Referencia . '<br />';
		$res .= '<b>Valor:</b> ' . $item->Valor . '<br />';
		$res .= '<b>Id:</b> ' . $item->Id . '<br />';
		$res .= '<b>Data/Hora do Pagamento:</b> ' . $item->DtHrPagamento . '<br />';
		$res .= '<b>Processamento:</b> ' . $item->Processamento . '<br />';
		$res .= '<b>Terminal:</b> ' . $item->Terminal . '<br />';
		$res .= '<b>Tarifa:</b> ' . $item->Tarifa . '<br />';
		$res .= '<b>ValorLiquido:</b> ' . $item->ValorLiquido . '<br />';
		}else{
			$res .= '<b>Código Erro:</b> ' . $item->CodigoErro . '<br />';
			$res .= '<b>Mensagem Erro:</b> ' . $item->MensagemErro . '<br />';
		}
		$res .= '<br />';
	}

	echo $res;

?>