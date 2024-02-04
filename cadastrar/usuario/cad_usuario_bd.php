<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

    $dadosFormBairro = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $login = $dadosFormBairro["login"];
    $senha = sha1($dadosFormBairro["senha"]);
    $nome = $dadosFormBairro["nome"];
    $fone = $dadosFormBairro["fone"];

    if (isset($dadosFormBairro["ic"])) {
        $ic = 1;
    } else {
        $ic = 0;
    }
    if (isset($dadosFormBairro["ig"])) {
        $ig = 1;
    } else {
        $ig = 0;
    }
    if (isset($dadosFormBairro["im"])) {
        $im = 1;
    } else {
        $im = 0;
    }
    if (isset($dadosFormBairro["c"])) {
        $c = 1;
    } else {
        $c = 0;
    }
    if (isset($dadosFormBairro["es"])) {
        $es = 1;
    } else {
        $es = 0;
    }
    if (isset($dadosFormBairro["um"])) {
        $um = 1;
    } else {
        $um = 0;
    }

    include("../../db_tools/conecta_db.php");

    $busca = mysql_query("SELECT login FROM admin WHERE login = '$login' ") or die("Não foi possivel buscar os nomes no Banco de Dados" . mysql_error());
    $registro = mysql_num_rows($busca);
    if ($registro == 0) {
        $insere_permissao = "INSERT INTO permissao (incluir_contato, incluir_grupo, incluir_sms, alterar_config, enviar_sms, master) values('$ic','$ig','$im','$c','$es','$um');";

        mysql_select_db($db, $con);

        $resultado_permissao = mysql_query($insere_permissao, $con) or die(mysql_error());

        $busca_id = mysql_query("SELECT MAX(id_permissao) AS id FROM permissao;")or die("Não foi possivel acessar a ultima inserção no banco" . mysql_error());
        $resultado_busca_id = mysql_fetch_assoc($busca_id);
        $id_permissao = $resultado_busca_id['id'];
        if ($resultado_permissao) {
            $insere_usuario = "INSERT INTO admin(login, senha, nome, fone, situacao, id_permissao ) VALUES ('$login','$senha','$nome','$fone',1,'$id_permissao');";
        } else {
            ?>
            <script type="text/javascript">
                alert("Não foi possivel inserir as permissões do usuário no banco! Inserção abortada.");
                location.replace("cad_usuario.php");
            </script>
            <?php
        }
    } else {
        ?>
        <script type="text/javascript">
            alert("Já existe um usuário com este nome no Banco de Dados. Especifique outro!");
            window.history.back();
        </script>
        <?php
    }
//confirmar
    $resultado_usuario = mysql_query($insere_usuario, $con) or die(mysql_error());
    if ($resultado_permissao == true && $resultado_usuario == true) {
        ?>
        <script type="text/javascript">
            alert("Usuário incluido com sucesso!");
            location.replace("cad_usuario.php");

        </script>
        <?php
    }
    mysql_free_result($busca);
    mysql_free_result($busca_id);
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