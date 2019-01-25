<?php

/* 
 * Deve fornecer � ifthen o url que permite a invoca��o deste ficheiro bem como os par�metros que s�o para ser invocados
 * Exemplo: https://www.exemplo.com/callback.php?chave=[CHAVE_ANTI_PHISHING]&entidade=[ENTIDADE]&referencia=[REFERENCIA]&valor=[VALOR] 
 */

//devem definir uma chave a vosso gosto e que devem comunicar � Ifthen bem como o url de invoca��o.
$chave_anti_phishing = "s465ads4d6asd5sa4d96asfd6sa5f46d54f6ds54sa6546dsa5";

/*
 * verifica se os parametros necess�rios v�m incluidos no url de callback
 * no exemplo s� assumimos os obrigat�rios, isto �, chave, entidade, referencia e valor. Podem tamb�m solicitar a data/hora de pagamento e o terminal bastando para isso acrescentar os campos.
 */
if(isset($_REQUEST['chave']) && isset($_REQUEST['entidade']) && isset($_REQUEST['referencia']) && isset($_REQUEST['valor'])){
	 
	$chave = $_REQUEST['chave'];
	$entidade = $_REQUEST['entidade'];
	$referencia = $_REQUEST['referencia'];
	$valor = $_REQUEST['valor'];
	
	//verifica se a chave anti-phishing devolvida pela ifthen corresponde � chave definida
	if($chave==$chave_anti_phishing){
		//aqui definem a(s) intru��o(�es) SQL para inserir, actualizar e/ou eliminar o que desejarem numa base de dados
	}	
}

?>