<?php

header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    //função para explodir nome
    function nome($data) {
        $str = explode(' ', $data);
        $pri_nome = $str[0] . ', ';
        return $pri_nome;
    }

    //função para explodir fone
    function fone($data) {
        $fone_s_a_p = explode('(', $data);
        $fone_s_f_p = explode(')', $fone_s_a_p[1]);
        $fone_s_p = $fone_s_f_p[0] . $fone_s_f_p[1];
        $fone_s_e = explode(' ', $fone_s_p);
        $fone_r = $fone_s_e[0] . $fone_s_e[1];
        $fone_r_i = explode('-', $fone_r);
        $fone_p = '+55' . $fone_r_i[0] . $fone_r_i[1];
        return $fone_p;
    }

    include("../db_tools/conecta_db.php");

    $buscaConfig = mysql_query("SELECT * FROM config") or die("Não foi possivel acessar as configurações. " . mysql_error());
    $regConfig = mysql_fetch_assoc($buscaConfig);

    $enderecoIp = $regConfig['endereco_ip'];
    $porta = $regConfig['porta'];

    $dadosEnvio = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $fone = $dadosEnvio['txtTELEFONE'];
    $tipo = $dadosEnvio['tipo_msgn'];
    $usuario = $_SESSION['nome'];
    $tipo_envio = $dadosEnvio['tipo_envio'];

    if ($tipo == "cadastrada") {
        $cod_msgn = $dadosEnvio['mensagem_selecionada'];
        $busca_msgn = mysql_query("SELECT mensagem FROM mensagem WHERE id_mensagem = '$cod_msgn' ") or die("Não foi possivel encontrar a mensagem para envio individual. " . mysql_error());
        $lista_msgn = mysql_fetch_assoc($busca_msgn);
        $msgn = $lista_msgn['mensagem'];
    } else {
        $msgn = $dadosEnvio['txtMENSAGEM'];
    }
    $busca_nome = mysql_query("SELECT nome FROM municipe WHERE id_municipe IN(SELECT id_municipe FROM telefone WHERE fone_sms ='$fone');") or die("Não foi possível encontrar o nome vinculado ao telefone. " . mysql_error());
    $lista = mysql_fetch_assoc($busca_nome);

    $msgem = strtoupper(nome($lista['nome'])) . $msgn;
    $telefone = fone($fone);

    function SendSMS($host, $port, $username, $password, $phoneNoRecip, $msgText) {

        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            echo "errno: $errno \n";
            echo "errstr: $errstr\n";
            return $result;
        }
        fwrite($fp, "GET /PhoneNumber=" . rawurlencode($phoneNoRecip) . "&Text=" . rawurlencode($msgText) . " HTTP/1.0\n");
        if ($username != "") {
            $auth = $username . ":" . $password;
            // echo "auth: $auth\n";
            $auth = base64_encode($auth);
            echo "auth: $auth\n";
            fwrite($fp, "Authorization: Basic " . $auth . "\n");
        }
        fwrite($fp, "\n");

        $res = "";

        while (!feof($fp)) {
            $res .= fread($fp, 1);
        }
        fclose($fp);

        return $res;
    }

    $x = SendSMS($enderecoIp, $porta, "", "", $telefone, $msgem);

    //inicio log
    $horaEnvio = date("H:i");
    $dataEnvio = date("Y-m-d");
    $user = $_SESSION['idUser'];

    if ($tipo == "cadastrada") {
        $mensagem = 1;
    } else {
        $mensagem = 2;
    }

    //Insere na tabela envio_prog
    $insereEnvio = "INSERT INTO envio_prog (tipo_destino, tipo_msgm, data_envio, hora_envio, situacao, id_admin) VALUES (1,'$mensagem','$dataEnvio','$horaEnvio',2,'$user');";
    mysql_select_db($db, $con);
    $resultadoEnvio = mysql_query($insereEnvio, $con) or die(mysql_error());

    //Busca o id da inserção anterior
    $buscaId = mysql_query("SELECT MAX(id_envio_prog) AS id FROM envio_prog;")or die("Não foi possivel acessar a ultima inserção no banco" . mysql_error());
    $resultadoBuscaId = mysql_fetch_assoc($buscaId);
    $idEnvioProgramado = $resultadoBuscaId['id'];

    //Busca a id do municipe pelo telefone
    $buscaIdMunicipe = mysql_query("SELECT id_municipe FROM municipe WHERE id_municipe IN(SELECT id_municipe FROM telefone WHERE fone_sms ='$fone');") or die("Não foi possível encontrar o nome vinculado ao telefone para o log. " . mysql_error());
    $regIdMunicipe = mysql_fetch_assoc($buscaIdMunicipe);
    $idMunicipe = $regIdMunicipe['id_municipe'];

    if ($resultadoEnvio) {
        //Insere os dados do envio e do municipe na tabela de especialização de municipe
        $insereEpMunicipe = "INSERT INTO esp_ep_municipe (id_envio_prog, id_municipe) VALUES ('$idEnvioProgramado', '$idMunicipe');";
        mysql_select_db($db, $con);
        $resultadoEpMunicipe = mysql_query($insereEpMunicipe, $con) or die(mysql_error());

        //insere os dados da mensagem nas tabelas de especialização de mensagem
        if ($mensagem == 1) {
            $insereMensagem = "INSERT INTO esp_ep_msgm_pred (id_envio_prog, id_mensagem) VALUES('$idEnvioProgramado', '$cod_msgn');";
        } else {
            $insereMensagem = "INSERT INTO esp_ep_msgm_n_pred (id_envio_prog, mensagem) VALUES ('$idEnvioProgramado', '$msgn');";
        }
        $resultadoMensagem = mysql_query($insereMensagem, $con) or die(mysql_error());
    }
    //fim log


    echo $x;

    if ($tipo == "cadastrada") {
        mysql_free_result($busca_msgn);
    }
    mysql_free_result($busca_nome);
    mysql_free_result($buscaId);
    mysql_free_result($buscaIdMunicipe);
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