﻿﻿<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    include ('../../db_tools/conecta_db.php');
    $getCod = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    $cod = $getCod['inativar'];

    $inativar = ("UPDATE municipe set situacao = 0 WHERE id_municipe = '$cod';");

    mysql_select_db($db, $con);
    $resultado = mysql_query($inativar, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script>
            alert("Munícipe inativado com sucesso!");
            location.replace("../../pesquisar/municipe/pesquisar_municipe.php");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Não foi possível inativar o munícipe desejado!");
            location.replace("../../pesquisar/municipe/pesquisar_municipe.php");
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