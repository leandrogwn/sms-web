<?php
session_start();
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
//recebe código do contato a ser atualizado
    $cod = $_SESSION['codMunicipe'];
    $dadosFormMunicipe = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//recebe os dados do formulário
    extract($dadosFormMunicipe);
    foreach (array($box)as $grupo) {
        
    }

//inicia conexão com o banco
    include("../../db_tools/conecta_db.php");

//deleta todos os registro do municipe e inclui os atualizados
    mysql_select_db($db, $con);
    mysql_query("DELETE FROM grupo_assoc_municipe WHERE id_municipe = '$cod';");
    for ($cont = 0; $cont < count($grupo); $cont++) {
        $insere_grupo = "INSERT INTO grupo_assoc_municipe (id_grupo, id_municipe) VALUES ('$grupo[$cont]','$cod');";
        $resultado_grupo = mysql_query($insere_grupo, $con) or die(mysql_error());
    }
//confirma se foi gravado
    if ($resultado_grupo) {
        ?>
        <script type="text/javascript">
            alert("Grupos do munícipe atualizado com sucesso.");
            location.replace("../../pesquisar/municipe/pesquisar_municipe.php");
        </script>
        <?php

    } else {
        ?>
        <script type="text/javascript">
            alert("Erro ao atualizar os grupos do munícipe.");
            window.history.back();
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