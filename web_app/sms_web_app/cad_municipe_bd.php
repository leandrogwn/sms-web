<?php

function validaData($data) {
    $data_v = explode("/", $data);
    $dataOk = $data_v[2] . "-" . $data_v[1] . "-" . $data_v[0];
    return $dataOk;
}

function formataFone($foneSemFormatacao) {
    $codigoArea = substr($foneSemFormatacao, 0, 2);
    $sequencia1 = substr($foneSemFormatacao, 2, 4);
    $sequencia2 = substr($foneSemFormatacao, 6, 4);
    $dataFormatada = "(" . $codigoArea . ") " . $sequencia1 . "-" . $sequencia2;
    return $dataFormatada;
}

function formataFone1($foneSemFormatacao) {
    $codigoArea = substr($foneSemFormatacao, 0, 2);
    $sequencia1 = substr($foneSemFormatacao, 2, 4);
    $sequencia2 = substr($foneSemFormatacao, 6, 4);
    $dataFormatada = "(" . $codigoArea . ") " . $sequencia1 . "-" . $sequencia2;
    return $dataFormatada;
}

session_start();
$sessao = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $sessao) {
    $id_user = $_SESSION['idUser'];
//recebe os dados do formulário
    $dadosFormMunicipe = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $nome = $dadosFormMunicipe["nome"];
    $nasc = $dadosFormMunicipe["data_n"];
    $email = $dadosFormMunicipe["email"];
    $bairro = $dadosFormMunicipe["bairro"];
    $rua = $dadosFormMunicipe["rua"];
    $numero = $dadosFormMunicipe["numero"];
    $sexo = $dadosFormMunicipe["sexo"];
    $fonesms = $dadosFormMunicipe["fone_sms"];
    $fonefixo = $dadosFormMunicipe["fone_fixo"];
    $fonerecado = $dadosFormMunicipe["fone_recado"];
    extract($dadosFormMunicipe);
    foreach (array($box)as $grupo) {
        
    }
    if ($nome == "" || $nasc == "" || $bairro == "" || $rua == "" || $numero == "" || $sexo == "" || $fonesms == "") {
        ?>
        <script>
            alert("Ops... Campo obrigatório não preenchido.");
            location.replace("RecebeDadosLogin.php");
        </script>
        <?php
    } else {

//inicia conexão com o banco
        include("conecta_db.php");
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
                $insere_fone = "INSERT INTO telefone (fone_sms, id_municipe, fone_fixo, fone_recado) VALUES ('$fonesms','$id_municipe','$fonefixo','$fonerecado');";
                for ($cont = 0; $cont < count($grupo); $cont++) {
                    $insere_grupo = "INSERT INTO grupo_assoc_municipe (id_grupo, id_municipe) VALUES ('$grupo[$cont]','$id_municipe');";
                    $resultado_grupo = mysql_query($insere_grupo, $con) or die(mysql_error());
                }
            }
            $resultado_endereco = mysql_query($insere_endereco, $con) or die(mysql_error());
            $resultado_fone = mysql_query($insere_fone, $con) or die(mysql_error());

            if ($resultado_municipe == true && $resultado_endereco == true && $resultado_fone == true && $resultado_grupo == true) {
                ?>
                <script type="text/javascript">
                    alert("Municipe Cadastrado com Sucesso.");
                    location.replace("RecebeDadosLogin.php");</script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    alert("Erro ao gravar Municipe.");
                    window.history.back();</script>
                <?php
            }
        } else {
            //busca a id do usuário já cadastrado com o telefone informado
            $regIdMunicipeAtual = mysql_fetch_assoc($busca_fone);
            $idMunicipeAtual = $regIdMunicipeAtual['id_municipe'];

            //busca o nome do municipe com a id encontrada
            $buscaNomeMunicipe = mysql_query("SELECT UPPER(nome) AS nome FROM municipe WHERE id_municipe = '$idMunicipeAtual';") or die("Não encontramos o nome do municipel atual que deseja alterar. " . mysql_error());
            $regNomeMunicipe = mysql_fetch_assoc($buscaNomeMunicipe);
            $nomeMunicipe = $regNomeMunicipe['nome'];
            ?>
            <script type="text/javascript">
                alert("Número de telefone SMS já cadastrado para o municipe <?php echo $nomeMunicipe; ?>.");
                location.replace("RecebeDadosLogin.php");
            </script>
            <?php
        }

        mysql_free_result($busca_fone);
        mysql_free_result($busca_id);
        mysql_close($con);
    }
} else {
    ?>
    <script type="text/javascript">
        alert("Realize o Login para acessar as funcionalidades do sistema!");
        location.replace("index.php");
    //        window.open('index.php', '_top');
    </script>
    <?php
}
?>