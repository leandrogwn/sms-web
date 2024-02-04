<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$sessao = md5(date("d/m/Y"));
if ($_SESSION['logado'] === $sessao) {
//recebe os dados do formulário
    $dadosFormBairro = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $titulo = $dadosFormBairro["titulo_msgn"];
    $msgn = $dadosFormBairro["msgn"];
    $id_user = $_SESSION['idUser'];

//inicia conexão com o banco
    include("../../db_tools/conecta_db.php");
    $busca = mysql_query("SELECT * FROM mensagem WHERE titulo LIKE '$titulo' ") or die("Não foi possivel acessar o banco. " . mysql_error());

//insere dados no banco
    $registro = mysql_num_rows($busca);
    if ($registro == 0) {
        $insere = "INSERT INTO mensagem (titulo, mensagem, temporaria, situacao, id_admin) VALUES ('$titulo','$msgn','n',1,'$id_user')";
    } else {
        ?>
        <script type="text/javascript">
            alert("Titulo já em uso por outra mensagem, escolha outro.");
            window.history.back();
        </script>
        <?php
    }
//confirma se foi gravado
    mysql_select_db($db, $con);
    $resultado = mysql_query($insere, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script>
            alert("Mensagem Cadastrada com Sucesso.");
            location.replace("cad_mensagem.php");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Erro ao gravar mensagem.");
            location.replace("cad_mensagem.php");
        </script>
        <?php
    }
    mysql_free_result($busca);
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