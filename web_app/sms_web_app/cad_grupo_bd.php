﻿<?php
session_start();
$sessao = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $sessao) {
    $dadosFormGrupo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $id_user = $_SESSION['idUser'];
    $grupo = $dadosFormGrupo["inc_grupo"];

    if ($grupo == "") {
        ?>
        <script>
            alert("Ops... Campo obrigatório não preenchido.");
            location.replace("RecebeDadosLogin.php#bairro");
        </script>
        <?php
    } else {

        include("../../db_tools/conecta_db.php");

        $busca_grupo = mysql_query("SELECT * FROM grupo WHERE grupo = '$grupo' ") or die("Não foi possivel buscar os grupos no Banco de Dados" . mysql_error());
        $registro_grupo = mysql_num_rows($busca_grupo);
        if ($registro_grupo == 0) {
            $insere_grupo = "INSERT INTO grupo(grupo, situacao, id_admin) VALUES ('$grupo',1,'$id_user')";
        } else {
            ?>
            <script type="text/javascript">
                alert("Grupo já cadastrado. Especifique outro!");
                window.history.back();
            </script>
            <?php
        }
//confirmar
        mysql_select_db($db, $con);
        $resultado = mysql_query($insere_grupo, $con) or die(mysql_error());
        if ($resultado) {
            ?>
            <script>
                alert("Grupo incluido com sucesso!");
                location.replace("RecebeDadosLogin.php");
            </script>
            <?php
        }
        mysql_free_result($busca_grupo);
        mysql_close($con);
    }
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        location.replace("RecebeDadosLogin.php");
    </script>
    <?php
}
?>