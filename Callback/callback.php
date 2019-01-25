<?php

/* 
 * Deve fornecer р ifthen o url que permite a invocaчуo deste ficheiro bem como os parтmetros que sуo para ser invocados
 * Exemplo: https://www.exemplo.com/callback.php?chave=[CHAVE_ANTI_PHISHING]&entidade=[ENTIDADE]&referencia=[REFERENCIA]&valor=[VALOR] 
 */

//devem definir uma chave a vosso gosto e que devem comunicar р Ifthen bem como o url de invocaчуo.
$chave_anti_phishing = "s465ads4d6asd5sa4d96asfd6sa5f46d54f6ds54sa6546dsa5";

/*
 * verifica se os parametros necessсrios vъm incluidos no url de callback
 * no exemplo sѓ assumimos os obrigatѓrios, isto щ, chave, entidade, referencia e valor. Podem tambщm solicitar a data/hora de pagamento e o terminal bastando para isso acrescentar os campos.
 */
if(isset($_REQUEST['chave']) && isset($_REQUEST['entidade']) && isset($_REQUEST['referencia']) && isset($_REQUEST['valor'])){
	 
	$chave = $_REQUEST['chave'];
	$entidade = $_REQUEST['entidade'];
	$referencia = $_REQUEST['referencia'];
	$valor = $_REQUEST['valor'];
	
	//verifica se a chave anti-phishing devolvida pela ifthen corresponde р chave definida
	if($chave==$chave_anti_phishing){
		//aqui definem a(s) intruчуo(ѕes) SQL para inserir, actualizar e/ou eliminar o que desejarem numa base de dados
	}	
}

?>