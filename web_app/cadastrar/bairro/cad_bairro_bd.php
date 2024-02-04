<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$sessao = md5(date("d/m/Y"));
if ($_SESSION['logado'] === $sessao) {
    $dadosFormBairro = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $id_user = $_SESSION['idUser'];
    $bairro = $dadosFormBairro["inc_bairro"];
    include("../../db_tools/conecta_db.php");

    $busca_bairro = mysql_query("SELECT bairro FROM bairro WHERE bairro = '$bairro' ") or die("Não foi possivel buscar o Bairro no Banco de Dados" . mysql_error());
    $registro_bairro = mysql_num_rows($busca_bairro);
    if ($registro_bairro == 0) {
        $insere_bairro = "INSERT INTO bairro(bairro, situacao, id_admin) VALUES ('$bairro',1,'$id_user')";
    } else {
        ?>
        <script type="text/javascript">
            alert("Bairro já cadastrado. Especifique outro!");
            window.history.back();
        </script>
        <?php
    }
    //confirmar
    mysql_select_db($db, $con);
    $resultado = mysql_query($insere_bairro, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script type="text/javascript">
            alert("Bairro incluido com sucesso!");
            location.replace("cad_bairro.php");
        </script>
        <?php
    }
    mysql_free_result($busca_bairro);
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