﻿<?php
header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    include ("../../db_tools/conecta_db.php");
    $getCod = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    $dadosGrupo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $cod = $getCod['cod'];
    $editar = $dadosGrupo['edita_grupo'];

    $atualiza = ("UPDATE grupo SET grupo = '$editar' WHERE id_grupo ='$cod' ");

    mysql_select_db($db, $con);
    $resultado = mysql_query($atualiza, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script>
            alert("Grupo atualizado com sucesso!");
            location.replace("../../listar/grupo/listar_grupo.php");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Não foi possível atualizar o grupo desejado!");
            location.replace("../../listar/grupo/listar_grupo.php");
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