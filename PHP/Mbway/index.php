<?php

require_once('./MbWay.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mbway = new MbWay();
    $mbway->setPedido(true, $_POST['referencia'], $_POST['valor'], $_POST['nrtlm'], $_POST['email']);
    $estadoPedido = $mbway->getEstadoPedido(true, $mbway->getIdPedido());
}
// Para simular a evocação do callback pode utilizar
// http://localhost/mbway_test/index.php?chave=f261aebfa55138c20caf18556e17cd29&referencia=0021&idpedido=M78m6PdniKR5_9QJXuYL&valor=5.00&estado=pago

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    $mbway = new MbWay();
    $mbway->callback($_GET);
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Pagamento</title>
</head>

<body>
	<form action='<?=$_SERVER['PHP_SELF']?>' method="POST">                      
		<table>
			<tr>
				<td>MbWayKey: <input type="text" name="MbWayKey">AAA-123456</td>
				<td>Canal: <input type="text" name="canal">03</td>
				<td>Referencia: <input type="text" name="referencia">0021</td>
				<td>valor: <input type="text" name="valor">5.00</td>
				<td>nrtlm: <input type="text" name="nrtlm">931231234</td>
				<td>email: <input type="text" name="email">email@dominio.com</td>
				<td>descricao: <input type="text" name="descricao">Factura 123</td>
			</tr>
			<tr>
				<td><input type="submit" value="Invoke" class="button"></td>
			</tr>
		</table>
	</form>
</body>