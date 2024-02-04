<?php

session_start();

function validaData($data) {
    $data_v = explode("/", $data);
    $dataOk = $data_v[2] . "-" . $data_v[1] . "-" . $data_v[0];
    return $dataOk;
}

Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {

//recebe código do contato a ser atualizado
    $cod = $_SESSION['codMunicipe'];
    $dadosFormMunicipe = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//recebe os dados do formulário

    $nome = $dadosFormMunicipe["nome"];
    $nasc = validaData($dadosFormMunicipe["data_n"]);
    $email = $dadosFormMunicipe["email"];
    $bairro = $dadosFormMunicipe["bairro"];
    $rua = $dadosFormMunicipe["rua"];
    $numero = $dadosFormMunicipe["numero"];
    $sexo = $dadosFormMunicipe["sexo"];
    $fonesms = $dadosFormMunicipe["fone_sms"];
    $fone_fixo = $dadosFormMunicipe["fone_fixo"];
    $fone_recado = $dadosFormMunicipe["fone_recado"];
    extract($dadosFormMunicipe);
    foreach (array($box)as $grupo) {
        
    }

//inicia conexão com o banco
    include("../../db_tools/conecta_db.php");

//pesquisa por duplicidades no dados no banco
    $busca_fone = mysql_query("SELECT * FROM telefone WHERE fone_sms LIKE '$fonesms' AND fone_sms != '' AND id_municipe != '$cod'") or die("Não foi possivel acessar o telefone no banco" . mysql_error());
    $registro_fone = mysql_num_rows($busca_fone);
    if ($registro_fone == 0) {

//atualiza o contato atual
        $atualiza_municipe = ("UPDATE municipe SET nome='$nome', data_nasc='$nasc', sexo='$sexo', email='$email' WHERE id_municipe = '$cod'");

//confirma se foi gravado
        mysql_select_db($db, $con);
        $resultado_municipe = mysql_query($atualiza_municipe, $con) or die(mysql_error());

        if ($resultado_municipe) {
            $atualiza_endereco = ("UPDATE endereco SET rua='$rua', numero='$numero', id_bairro='$bairro' WHERE id_municipe = '$cod';");
            $atualiza_fone = ("UPDATE telefone SET fone_sms='$fonesms', fone_fixo='$fone_fixo', fone_recado='$fone_recado' WHERE id_municipe = '$cod';");
            //deleta todos os registro do municipe e inclui os atualizados
            mysql_query("DELETE FROM grupo_assoc_municipe WHERE id_municipe = '$cod' ");
            for ($cont = 0; $cont < count($grupo); $cont++) {
                $insere_grupo = "INSERT INTO grupo_assoc_municipe (id_grupo, id_municipe) VALUES ('$grupo[$cont]','$cod');";
                $resultado_grupo = mysql_query($insere_grupo, $con) or die(mysql_error());
            }
        }
    } else {
        ?>
        <script type="text/javascript">
            alert("Telefone SMS já em uso por outro munícipe.");
            window.history.back();
        </script>
        <?php

    }

    $resultado_endereco = mysql_query($atualiza_endereco, $con) or die(mysql_error());
    $resultado_fone = mysql_query($atualiza_fone, $con) or die(mysql_error());


    if ($resultado_municipe == true && $resultado_endereco == true && $resultado_fone == true && $resultado_grupo == true) {
        ?>
        <script type="text/javascript">
            alert("Munícipe atualizado com sucesso.");
            location.replace("../../pesquisar/municipe/pesquisar_municipe.php");

        </script>
        <?php

    } else {
        ?>
        <script type="text/javascript">
            alert("Erro ao atualizar munícipe.");
            window.history.back();
        </script>
        <?php

    }

    mysql_free_result($busca_fone);
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