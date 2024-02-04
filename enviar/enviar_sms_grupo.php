<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    ?>
    ﻿<!DOCTYPE html>
    <head>
    </head>
    <body>
        <div id="tamanho">
            <?php

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

            //recebe os dados do envio
            $dadosEnvio = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $grupo = $dadosEnvio['envia_grupo'];
            $tipo = $dadosEnvio['tipo_msgn'];
            $usuario = $_SESSION['nome'];
            $tipo_envio = $dadosEnvio['tipo_envio'];
            if ($tipo == "cadastrada") {
                $cod_msgn = $dadosEnvio['mensagem_selecionada'];
                $busca_msgn = mysql_query("SELECT mensagem FROM mensagem WHERE id_mensagem = '$cod_msgn' ") or die("Não foi possivel encontrar a mensagem para envio em grupo. " . mysql_error());
                $lista_msgn = mysql_fetch_assoc($busca_msgn);
                $msgn = $lista_msgn['mensagem'];
            } else {
                $msgn = "" . $dadosEnvio['txtMENSAGEM'];
            }

            if ($grupo == 1) {
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
                        . "where grupo_assoc_municipe.id_grupo = '$grupo'") or die("Não foi possivel carregar os dados associados entre grupo e municipe. " . mysql_error());

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
                $insereEnvio = "INSERT INTO envio_prog (tipo_destino, tipo_msgm, data_envio, hora_envio, situacao, id_admin) VALUES (2,'$mensagem','$dataEnvio','$horaEnvio',2,'$user');";
                mysql_select_db($db, $con);
                $resultadoEnvio = mysql_query($insereEnvio, $con) or die(mysql_error());

                //Busca o id da inserção anterior
                $buscaId = mysql_query("SELECT MAX(id_envio_prog) AS id FROM envio_prog;")or die("Não foi possivel acessar a ultima inserção no banco" . mysql_error());
                $resultadoBuscaId = mysql_fetch_assoc($buscaId);
                $idEnvioProgramado = $resultadoBuscaId['id'];

                if ($resultadoEnvio) {
                    //Insere os dados do envio e do grupo na tabela de especialização de grupo
                    $insereEpGrupo = "INSERT INTO esp_ep_grupo (id_envio_prog, id_grupo) VALUES ('$idEnvioProgramado', '$grupo');";
                    mysql_select_db($db, $con);
                    $resultadoEpGrupo = mysql_query($insereEpGrupo, $con) or die(mysql_error());

                    //insere os dados da mensagem nas tabelas de especialização de mensagem
                    if ($mensagem == 1) {
                        $insereMensagem = "INSERT INTO esp_ep_msgm_pred (id_envio_prog, id_mensagem) VALUES('$idEnvioProgramado', '$cod_msgn');";
                    } else {
                        $insereMensagem = "INSERT INTO esp_ep_msgm_n_pred (id_envio_prog, mensagem) VALUES ('$idEnvioProgramado', '$msgn');";
                    }
                    $resultadoMensagem = mysql_query($insereMensagem, $con) or die(mysql_error());
                }
                //fim log
            }

            //função envia os dados
            function SendSMS($host, $port, $username, $password, $phoneNoRecip, $msgText) {

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
            if (isset($busca)) {
                mysql_free_result($busca);
            }
            if (isset($buscaConfig)) {
                mysql_free_result($buscaConfig);
            }
            if (isset($buscaId)) {
                mysql_free_result($buscaId);
            }
            if (isset($busca_msgn)) {
                mysql_free_result($busca_msgn);
            }
            mysql_close($con);
            ?>
        </div>
        <script language="Javascript" type="text/javascript">
            parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 40; //40: Margem Superior e Inferior, somadas
        </script> 
    </body>
    </html>
    <?php
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        window.open('../../index.php', '_top');
    </script>
    <?php
}
?>