﻿﻿<?php
session_start();
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    //recebe os dados do formulário
    $getCod = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    $dadosMensagem = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $cod = $getCod['editar'];
    $titulo = $dadosMensagem["titulo_msgn"];
    $msgn = $dadosMensagem["msgn"];

    //inicia conexão com o banco
    include("../../db_tools/conecta_db.php");
    $atualiza = ("UPDATE mensagem SET titulo ='$titulo', mensagem ='$msgn' WHERE id_mensagem = '$cod' ");
    //confirma se foi gravado
    mysql_select_db($db, $con);
    $resultado = mysql_query($atualiza, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script>
            alert("Mensagem alterada com sucesso!");
            location.replace("../../listar/mensagem/listar_mensagem.php");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Erro ao gravar mensagem atualizada.");
            window.replace("../../listar/mensagem/listar_mensagem.php");
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