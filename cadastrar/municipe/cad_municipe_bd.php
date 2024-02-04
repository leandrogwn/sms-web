<?php

function validaData($data) {
    $data_v = explode("/", $data);
    $dataOk = $data_v[2] . "-" . $data_v[1] . "-" . $data_v[0];
    return $dataOk;
}

session_start();
$sessao = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $sessao) {
    $id_user = $_SESSION['idUser'];
//recebe os dados do formulário
    $dadosFormMunicipe = filter_input_array(INPUT_POST, FILTER_DEFAULT);
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
    $busca_fone = mysql_query("SELECT * FROM telefone WHERE fone_sms LIKE '$fonesms' AND fone_sms != ''") or die("Não foi possivel acessar o telefone no banco" . mysql_error());
//insere dados no banco
    $registro_fone = mysql_num_rows($busca_fone);
    if ($registro_fone == 0) {
        $insere_municipe = "INSERT INTO municipe (nome, data_nasc, sexo, email, situacao, id_admin) VALUES ('$nome','$nasc','$sexo','$email','1','$id_user');";

        mysql_select_db($db, $con);

        $resultado_municipe = mysql_query($insere_municipe, $con) or die(mysql_error());

        $busca_id = mysql_query("SELECT MAX(id_municipe) AS id FROM municipe;")or die("Não foi possivel acessar a ultima inserção no banco" . mysql_error());
        $resultado_busca_id = mysql_fetch_assoc($busca_id);
        $id_municipe = $resultado_busca_id['id'];
        if ($resultado_municipe) {
            $insere_endereco = "INSERT INTO endereco (rua, numero, id_municipe, id_bairro) VALUES ('$rua','$numero','$id_municipe','$bairro');";
            $insere_fone = "INSERT INTO telefone (fone_sms, id_municipe, fone_fixo, fone_recado) VALUES ('$fonesms','$id_municipe','$fone_fixo','$fone_recado');";
            for ($cont = 0; $cont < count($grupo); $cont++) {
                $insere_grupo = "INSERT INTO grupo_assoc_municipe (id_grupo, id_municipe) VALUES ('$grupo[$cont]','$id_municipe');";
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

    $resultado_endereco = mysql_query($insere_endereco, $con) or die(mysql_error());
    $resultado_fone = mysql_query($insere_fone, $con) or die(mysql_error());

    if ($resultado_municipe == true && $resultado_endereco == true && $resultado_fone == true && $resultado_grupo == true) {
        ?>
        <script type="text/javascript">
            alert("Municipe Cadastrado com Sucesso.");
            location.replace("cad_municipe.php");

        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            alert("Erro ao gravar Municipe.");
            window.history.back();
        </script>
        <?php
    }

    mysql_free_result($busca_fone);
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