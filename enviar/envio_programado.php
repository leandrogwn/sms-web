<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();

include("../db_tools/conecta_db.php");

//Mensagem automatica
$buscaConfig = mysql_query("SELECT * FROM config") or die("Não foi possivel acessar as configurações. " . mysql_error());
$regConfig = mysql_fetch_assoc($buscaConfig);

$dataAtual = date('Y-m-d');
$horaAtual = date('H:i');
$horaEnvio = $regConfig['hora_envio_aniversario'];
$tempoAtualizacao = $regConfig['tempo_atualizacao'];

//Mensagem Programada
$buscaEnvioProgramado = mysql_query("SELECT data_envio, hora_envio FROM envio_prog WHERE data_envio = '$dataAtual' AND hora_envio = '$horaAtual' AND situacao = 1") or die("Erro ao buscar envios programados. " . mysql_error());
$regEnvioProgramado = mysql_num_rows($buscaEnvioProgramado);

if ($regEnvioProgramado != 0) {
    header("location:enviar_sms_agendado.php");
}

if ($horaEnvio == $horaAtual) {
    header("location:enviar_sms_auto.php");
} else {
    header("refresh:$tempoAtualizacao; url=envio_programado.php");
}
?>
<html>
    <head>
        <style>
            #imagem{
                margin-top: 100px;
            }
        </style>
    </head>
    <body>
        <div id="imagem" align="center">
            <img src="../icons/icon-256x256.png">
        </div>
    </body>
</html>
