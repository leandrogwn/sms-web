<?php

header("Content-Type: text/html; charset=utf-8", true);
header('Cache-Control: no-cache');
header('Pragma: no-cache');
session_start();
$chave = md5(date("d/m/Y"));
if ($_SESSION['logado'] == $chave) {
    include ('../db_tools/conecta_db.php');
    
    $dadosAgendamento = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    $envio = $dadosAgendamento['cancelar'];
    
    $cancelarAgendamento = ("UPDATE envio_prog set situacao = 0 WHERE id_envio_prog = '$envio';");

    mysql_select_db($db, $con);
    $resultado = mysql_query($cancelarAgendamento, $con) or die(mysql_error());
    if ($resultado) {
        ?>
        <script>
            alert("Agendamento cancelado!");
            location.replace("../log/mensagem.php?load=1");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Não foi possível cancelar o agendamento desejado!");
            location.replace("../log/mensagem.php?load=1");
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