<?php

$con = mysql_connect('localhost', 'smswebuser', 'smspass');

if (!$con) {
    die("<h1>Falha na conexão com o Banco de Dados!</h1>");
}

// Caso a conexão seja aprovada, então conecta o Banco de Dados.	
$db = mysql_select_db("smsweb", $con);

// para a conexão com o MySQL
mysql_set_charset('utf8', $con);