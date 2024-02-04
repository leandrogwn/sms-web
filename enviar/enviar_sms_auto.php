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

$telefonInfo = fone($regConfig['telefone_info']);
$enderecoIp = $regConfig['endereco_ip'];
$porta = $regConfig['porta'];


//busca a mesagem pre-configurada.
$busca_msgm = mysql_query("SELECT mensagem FROM mensagem WHERE id_mensagem IN(SELECT mensagem_aniversario FROM config) ") or die("Não foi possivel encontrar a mensagem com o código configurado. " . mysql_error());
$lista_msgn = mysql_fetch_assoc($busca_msgm);
$msgn = $lista_msgn['mensagem'];

//busca os dados do munícipe.
$buscaAniversariante = mysql_query("SELECT municipe.nome, telefone.fone_sms FROM municipe INNER JOIN telefone ON(municipe.id_municipe = telefone.id_municipe) WHERE DAY(data_nasc) = DAY(CURDATE()) AND MONTH(data_nasc)= MONTH(CURDATE()) AND situacao = 1;") or die("Não foi possivel carregar os aniversáriantes do dia, entre em contato com Orssatto Soluções pelo fone (45) 3238-1323 " . mysql_error());

//função envia os dados
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

while ($listaAniversariante = mysql_fetch_assoc($buscaAniversariante)) {

    //chama a funções que padroniza telefone
    $telefone = fone($listaAniversariante['fone_sms']);

    //chama a função e concatena o nome a mensagem
    $mensagem = nome($listaAniversariante['nome']) . $msgn;

    //carrega os dados de cada envio	
    $x = SendSMS($enderecoIp, $porta, "", "", $telefone, $mensagem);

    echo $x;
}

$aniversariantes = mysql_num_rows($buscaAniversariante);

if ($aniversariantes == 0) {

    $telefone = $telefonInfo;

    $mensagem = 'Nao teve aniversariantes hoje';

    //carrega os dados de cada envio	
    $x = SendSMS($enderecoIp, $porta, "", "", $telefone, $mensagem);

    echo $x;
} else {
    $telefone = $telefonInfo;
    $nomes = "";

    while ($lista_nomes = mysql_fetch_assoc($buscaAniversariante)) {
        //concatena os nomes dos aniversáriantes
        $nomes = $nomes . ' - ' . $lista_nomes['nome'];
    }

    $nomes = strtoupper($nomes);

    //concatena a string com a variavel nomes
    $mensagem = 'Aniversáriantes do dia: ' . $nomes;


    //carrega os dados de cada envio	
    $x = SendSMS($enderecoIp, $porta, "", "", $telefone, $mensagem);

    echo $x;
}

mysql_free_result($buscaAniversariante);
mysql_free_result($busca_msgm);
mysql_close($con);
?>
