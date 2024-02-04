<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    include ("../../db_tools/conecta_db.php");
    $dadosFormBairro = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $id_user = $_SESSION['idUser'];
    $sa = sha1($dadosFormBairro["senha_atual"]);
    $ns = sha1($dadosFormBairro["repete_senha"]);

    $busca = mysql_query("SELECT senha FROM admin WHERE id_admin = '$id_user'") or die("Não foi possível localizar o código desejado. " . mysql_error());

    $reg = mysql_fetch_assoc($busca);
    $senha_banco = $reg['senha'];

    if ($sa == $senha_banco) {

        $atualiza = ("UPDATE admin SET senha = '$ns' WHERE id_admin ='$id_user' ");

        mysql_select_db($db, $con);
        $resultado = mysql_query($atualiza, $con) or die(mysql_error());
        if ($resultado) {
            ?>
            <script>
                alert("Senha alterada com sucesso!");
                location.replace("../../meio.php");
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Não foi possível alterar a senha!");
                location.replace("alterar_senha.php");
            </script>
            <?php
        }
        mysql_free_result($busca);
        mysql_close($con);
    } else {
        ?>
        <script>
            alert("Sua senha atual não confere. Tente novamente!");
            location.replace("alterar_senha.php");
        </script>
        <?php
    }
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        window.open('../../index.php', '_top');
    </script>
    <?php
}
?>