<?php

header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $codMensagem = $dadosForm['mensagem_selecionada'];
    $horaEnvio = $dadosForm['hora_envio'];
    $tempoAtualizacao = $dadosForm['segundos_atualiza'];
    $telefoneInfo = $dadosForm['telefone_info'];
    $enderecoIp = $dadosForm['enderecoip'];
    $porta = $dadosForm['porta'];
    $registroPagina = $dadosForm['registrospagina'];

    include '../db_tools/conecta_db.php';

    $alterarConfig = ("UPDATE config SET mensagem_aniversario = '$codMensagem', hora_envio_aniversario = '$horaEnvio', tempo_atualizacao = '$tempoAtualizacao', telefone_info = '$telefoneInfo', endereco_ip = '$enderecoIp', porta = '$porta', registro_pagina = '$registroPagina'");

    mysql_select_db($db, $con);
    $resultado = mysql_query($alterarConfig, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script>
            alert("Configurações atualizadas com sucesso!");
            location.replace("config.php");
        </script>
        <?php

    } else {
        ?>
        <script>
            alert("Não foi possível atualizar as configurações!");
            location.replace("config.php");
        </script>
        <?php

    }
    mysql_close($con);
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        window.open('../../index.php', '_top');
    </script>
    <?php

}
?>