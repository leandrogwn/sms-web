<?php

header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    function validaData($data) {
        $data_v = explode("/", $data);
        $dataOk = $data_v[2] . "-" . $data_v[1] . "-" . $data_v[0];
        return $dataOk;
    }

    $dadosAgendamento = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $enviarPara = $dadosAgendamento['tipo_envio'];
    $mensagem = $dadosAgendamento['tipo_msgn'];
    $dataEnvio = validaData($dadosAgendamento['data_envio']);
    $horaEnvio = $dadosAgendamento['hora_envio'];
    $situacao = 1;
    $user = $_SESSION['idUser'];

    include("../db_tools/conecta_db.php");

    $insereEnvioProgramado = "INSERT INTO envio_prog (tipo_destino, tipo_msgm, data_envio, hora_envio, situacao, id_admin) VALUES ('$enviarPara','$mensagem','$dataEnvio','$horaEnvio','$situacao','$user');";

    mysql_select_db($db, $con);

    $resultadoEnvioProgramado = mysql_query($insereEnvioProgramado, $con) or die(mysql_error());

    $buscaId = mysql_query("SELECT MAX(id_envio_prog) AS id FROM envio_prog;")or die("Não foi possivel acessar a ultima inserção no banco" . mysql_error());
    $resultadoBuscaId = mysql_fetch_assoc($buscaId);
    $idEnvioProgramado = $resultadoBuscaId['id'];

    if ($enviarPara == "1") {
        $idMunicipe = $dadosAgendamento['txtTELEFONE'];
        if ($resultadoEnvioProgramado) {
            $insereEnviaPara = "INSERT INTO esp_ep_municipe (id_envio_prog, id_municipe) VALUES ('$idEnvioProgramado', '$idMunicipe');";
        }
    } else {
        $idGrupo = $dadosAgendamento['envia_grupo'];
        if ($resultadoEnvioProgramado) {
            $insereEnviaPara = "INSERT INTO esp_ep_grupo (id_envio_prog, id_grupo) VALUES ('$idEnvioProgramado', '$idGrupo');
";
        }
    }
    $resultadoEnviaPara = mysql_query($insereEnviaPara, $con) or die(mysql_error());

    if ($mensagem == "1") {
        $idMensagem = $dadosAgendamento['mensagem_selecionada'];
        if ($resultadoEnviaPara) {
            $insereMensagem = "INSERT INTO esp_ep_msgm_pred (id_envio_prog, id_mensagem) VALUES('$idEnvioProgramado', '$idMensagem');
";
        }
    } else {
        $msgm = $dadosAgendamento['txtMENSAGEM'];
        if ($resultadoEnviaPara) {
            $insereMensagem = "INSERT INTO esp_ep_msgm_n_pred (id_envio_prog, mensagem) VALUES ('$idEnvioProgramado', '$msgm');";
        }
    }
    $resultadoMensagem = mysql_query($insereMensagem, $con) or die(mysql_error());

    If ($resultadoEnvioProgramado == true && $resultadoEnviaPara == true && $resultadoMensagem == true) {
        ?>
        <script type="text/javascript">
            alert("Envio de mensagem agendado com sucesso!");
            location.replace("../log/mensagem.php?load=1");
        </script>
        <?php

    } else {
        ?>
        <script type="text/javascript">
            alert("Não foi possível agendar o envio da mensagem!");
            window.history.back();
        </script>
        <?php

    }

    mysql_free_result($buscaId);
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