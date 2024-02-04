<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
header("refresh:60; url=envio_programado.php");

function nome($data) {
    $str = explode(' ', $data);
    $pri_nome = $str[0] . ', ';
    $pri_nome = strtoupper($pri_nome);
    return $pri_nome;
}

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

function SendSMS($host, $port, $username, $password, $phoneNoRecip, $msgText) {
    echo $host . '-' . $port . '-' . $username . '-' . $password . '-' . $phoneNoRecip . '-' . $msgText . '<br>';
    $fp = fsockopen($host, $port, $errno, $errstr);
    if (!$fp) {
        //echo "errno: $errno, errstr: $errstr";
        //echo "\n";
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

$dataAtual = date('Y-m-d');
$horaAtual = date('H:i');

//Mensagem Programada
$buscaEnvioProgramado = mysql_query("SELECT * FROM envio_prog WHERE data_envio = '$dataAtual' AND hora_envio = '$horaAtual' AND situacao =1") or die("Erro ao buscar envios programados. " . mysql_error());
$regEnvioProgramado = mysql_fetch_assoc($buscaEnvioProgramado);

$idEnvio = $regEnvioProgramado['id_envio_prog'];
$tipoEnvio = $regEnvioProgramado['tipo_destino'];
$tipoMensagem = $regEnvioProgramado['tipo_msgm'];

//Tipo mensagem 1 = Cadastrada
//Tipo mensagem 2 = Editada na hora
if ($tipoMensagem == 1) {
    $buscarMensagem = mysql_query("SELECT mensagem.mensagem FROM mensagem INNER JOIN esp_ep_msgm_pred ON(mensagem.id_mensagem = esp_ep_msgm_pred.id_mensagem) WHERE esp_ep_msgm_pred.id_envio_prog = '$idEnvio';") or die("Não foi possível encontrar a mensagem. " . mysql_error());
    $regMensagem = mysql_fetch_assoc($buscarMensagem);
    $msgn = $regMensagem['mensagem'];
} else {
    $buscaMensagemNP = mysql_query("SELECT mensagem FROM esp_ep_msgm_n_pred WHERE id_envio_prog = '$idEnvio';") or die("Não foi possível localizar a mensagem não predefinida. " . mysql_error());
    $regMensagemNP = mysql_fetch_assoc($buscaMensagemNP);
    $msgn = $regMensagemNP['mensagem'];
}

//Tipo Envio 1 = Municipe
//Tipo Envio 2 = Grupo
if ($tipoEnvio == 1) {
    $buscarMunicipe = mysql_query("SELECT municipe.nome, telefone.fone_sms FROM municipe INNER JOIN telefone ON(municipe.id_municipe = telefone.id_municipe) WHERE municipe.id_municipe IN(SELECT id_municipe FROM esp_ep_municipe WHERE id_envio_prog = '$idEnvio');") or die("Não foi possível encontrar dados do munícipe. " . mysql_error());
    $regBuscaMunicipe = mysql_fetch_assoc($buscarMunicipe);

    //chama a funções que padroniza telefone
    $telefone = fone($regBuscaMunicipe['fone_sms']);

    //chama a função e concatena o nome a mensagem
    $nome_pessoa = strtoupper(nome($regBuscaMunicipe['nome']));
    $mensagem = $nome_pessoa . $msgn;

    //carrega os dados de cada envio	
    $x = SendSMS($enderecoIp, $porta, "", "", $telefone, $mensagem);

    echo $x;
} else {
    $buscarGrupo = mysql_query("SELECT id_grupo FROM esp_ep_grupo WHERE id_envio_grupo = '$idEnvio'") or die("Não foi possível buscar id do grupo. " . mysql_error());
    $regBuscarGrupo = mysql_fetch_assoc($buscarGrupo);

    $idGrupo = $regBuscarGrupo['id_grupo'];
    if ($idGrupo == 1) {
        //faz a busca por nome e telefone em tabelas distintas
        $busca = mysql_query("SELECT municipe.nome AS nome, telefone.fone_sms AS fone FROM municipe "
                . "INNER JOIN telefone on(municipe.id_municipe = telefone.id_municipe) "
                . "WHERE municipe.id_municipe IN(select grupo_assoc_municipe.id_municipe FROM grupo_assoc_municipe) "
                . "ORDER BY municipe.nome;") or die("Não foi possivel carregar os dados da tabela munícipe, fone ou grupo. " . mysql_error());
    } else {
        //faz a busca no banco de dados pelo grupo
        $busca = mysql_query("SELECT grupo_assoc_municipe.id_municipe, municipe.nome AS nome, telefone.fone_sms AS fone FROM grupo_assoc_municipe "
                . "INNER JOIN municipe ON(municipe.id_municipe = grupo_assoc_municipe.id_municipe) "
                . "INNER JOIN telefone ON(telefone.id_municipe = grupo_assoc_municipe.id_municipe) "
                . "where grupo_assoc_municipe.id_grupo = '$idGrupo'") or die("Não foi possivel carregar os dados associados entre grupo e municipe. " . mysql_error());
    }

    //lista os contatos do grupo selecionado
    while ($lista = mysql_fetch_assoc($busca)) {

        //chama a funções que padroniza telefone
        $telefone = fone($lista['fone']);

        //chama a função e concatena o nome a mensagem
        $nome_pessoa = strtoupper(nome($lista['nome']));
        $mensagem = $nome_pessoa . $msgn;

        //carrega os dados de cada envio	
        $x = SendSMS($enderecoIp, $porta, "", "", $telefone, $mensagem);

        echo $x;
    }
}

$alteraSituacao = ("UPDATE envio_prog set situacao = 2 WHERE id_envio_prog = '$idEnvio';");

mysql_select_db($db, $con);
mysql_query($alteraSituacao, $con) or die(mysql_error());

if (isset($buscaMensagemNP)) {
    mysql_free_result($buscaMensagemNP);
}
if (isset($buscarGrupo)) {
    mysql_free_result($buscarGrupo);
}
if (isset($buscarMensagem)) {
    mysql_free_result($buscarMensagem);
}
if (isset($buscarMunicipe)) {
    mysql_free_result($buscarMunicipe);
}
if (isset($busca)) {
    mysql_free_result($busca);
}
if (isset($buscaEnvioProgramado)) {
    mysql_free_result($buscaEnvioProgramado);
}
mysql_close($con);
?>
