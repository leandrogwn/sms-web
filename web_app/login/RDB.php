<?php
include './VerificarLogin.php';

class RecebeDadosLogin {

    private $dadosFormulario;
    private $usuario;
    private $senha;
    private $instanciaVerificaLogin;
    private $chave;

    public function __construct() {
        $this->recebeDados();
        $this->recebeVerificacao();
        $this->validaVerificacao();
    }

    private function recebeDados() {
        $this->dadosFormulario = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->usuario = $this->dadosFormulario["login_digitado"];
        $this->senha = sha1($this->dadosFormulario["senha_digitada"]);
    }

    private function recebeVerificacao() {
        $instanciaVereficarLogin = new VerificarLogin();
        $instanciaVereficarLogin->verifiLogin($this->usuario, $this->senha);
    }

    private function validaVerificacao() {
        session_start();
        $this->chave = md5(date("d/m/Y"));
        if (isset($_SESSION['logado']) == $this->chave) {
            ?> 
            <!DOCTYPE html>

            <?php header("Content-Type: text/html; charset=utf-8", true); ?>
            <?php
            Header('Cache-Control: no-cache');
            Header('Pragma: no-cache');
            ?>

            <title>Agenda</title>
            <link rel="stylesheet" href="../web_tools/css/reset.css">
            <link rel="stylesheet" href="../web_tools/css/style.css">
            <link rel="stylesheet" href="../web_tools/css/menu.css">
            <script>
                function master() {
                    alert('Função habilitada somente para usuário principal!');
                }
            </script>

            <body  onload="UR_Start()">
                <div>
                    <ul id="nav">
                        <li><a href="#"><img src="../web_tools/img/key.png"></a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['master'] == TRUE) {
                                        echo '<a href="cadastrar_usuario.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>Cadastrar Usuário</a></li>
                                <li><a href="editar/senha/alterar_senha.php?cod=<?php echo $_SESSION['idUser']; ?>" target="meio">Alterar Senha</a></li>
                                <li><a href="../logout.php">Sair</a></li>
                            </ul>       
                        </li>
                        <li><a href="#">AGENDA</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['ic'] == TRUE) {
                                        echo '<a href="filtro.php?nome=' . $_SESSION['nome'] . '" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>PESQUISAR</a>
                                </li>
                                <li><?php
                                    if ($_SESSION['ic'] == TRUE) {
                                        echo '<a href="incluir_agenda.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INCLUIR</a></li>
                            </ul>
                        </li>
                        <li><a href="#">GRUPOS DE SMS</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['ig'] == TRUE) {
                                        echo '<a href="..\cadastrar\grupo\cad_grupo.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INCLUIR</a></li>
                                <li><?php
                                    if ($_SESSION['ig'] == TRUE) {
                                        echo '<a href="listar_grupo.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>LISTAR TODOS</a></li>
                            </ul>
                        </li>
                        <li><a href="#">MENSAGENS</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['im'] == TRUE) {
                                        echo '<a href="incluir_mensagem.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>CADASTRAR NOVA</a> </li>
                                <li><?php
                                    if ($_SESSION['im'] == TRUE) {
                                        echo '<a href="listar_mensagem.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>LISTAR MENSAGENS</a> </li>
                            </ul>
                        </li>
                        </li>
                        <li><?php
                            if ($_SESSION['c'] == TRUE) {
                                echo '<a href="config.php" target="meio">';
                            } else {
                                echo '<a href="#" onClick="master()">';
                            }
                            ?>CONFIGURAÇÕES</a></li>
                        <li><a href="#">ENVIAR SMS</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['es'] == TRUE) {
                                        echo '<a href="send_sms_ind.php?nome=' . $_SESSION['nome'] . '" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INDIVIDUAL</a></li>
                                <li><?php
                                    if ($_SESSION['es'] == TRUE) {
                                        echo '<a href="send_sms_grupo.php?nome=' . $_SESSION['nome'] . '" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>PARA GRUPO</a></li>
                            </ul>
                        </li>
                        <script language = "JavaScript">
                            var dataHora, xHora, xDia, dia, mes, ano, txtSaudacao;
                            dataHora = new Date();
                            xHora = dataHora.getHours();
                            if (xHora >= 0 && xHora < 12) {
                                txtSaudacao = " bom dia! ";
                            }
                            if (xHora >= 12 && xHora < 18) {
                                txtSaudacao = " boa tarde! ";
                            }
                            if (xHora >= 18 && xHora <= 23) {
                                txtSaudacao = " boa noite! ";
                            }
                            xDia = dataHora.getDay();
                            diaSemana = new Array(7);
                            diaSemana[0] = "Domingo";
                            diaSemana[1] = "Segunda-feira";
                            diaSemana[2] = "Terça-feira";
                            diaSemana[3] = "Quarta-feira";
                            diaSemana[4] = "Quinta-Feira";
                            diaSemana[5] = "Sexta-Feira";
                            diaSemana[6] = "Sábado";
                            dia = dataHora.getDate();
                            mes = dataHora.getMonth();
                            mesDoAno = new Array(12);
                            mesDoAno[0] = "janeiro";
                            mesDoAno[1] = "fevereiro";
                            mesDoAno[2] = "março";
                            mesDoAno[3] = "abril";
                            mesDoAno[4] = "maio";
                            mesDoAno[5] = "junho";
                            mesDoAno[6] = "julho";
                            mesDoAno[7] = "agosto";
                            mesDoAno[8] = "setembro";
                            mesDoAno[9] = "outubro";
                            mesDoAno[10] = "novembro";
                            mesDoAno[11] = "dezembro";
                            ano = dataHora.getFullYear();
                            document.write("<div id='saudacao'><font face='verdana' color='#CCCCCC'>" + "Olá <?php echo $_SESSION['nome']; ?>, " + txtSaudacao + "" +
                                    diaSemana[xDia] + ", " + dia + " de " + mesDoAno[mes] + " de " + ano +
                                    "</font></div>");
                        </script>
                    </ul>
                </div>
                <script>
                    function UR_Start() {
                        UR_Nu = new Date;
                        UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes()) + ":" + showFilled(UR_Nu.getSeconds());
                        document.getElementById("ur").innerHTML = UR_Indhold;
                        setTimeout("UR_Start()", 1000);
                    }
                    function showFilled(Value) {
                        return (Value > 9) ? "" + Value : "0" + Value;
                    }
                </script>
                <header>
                    <div id="relogio"><br><br><br><br><font id="ur" size="10" face="Trebuchet MS, Verdana, Arial, sans-serif" color="#DAD3B7" ></font></div>
                </header>
                <img id="figura" src="../web_tools/img/smsystem10.png">
                <div>
                    <iframe name="meio" id="altframe" src="../meio.php">

                    </iframe>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "<center><h2>Usuário ou senha desconhecida, tente novamente!</h2></center>";
            echo "<center><a href=\"..\index.php\">Digitar seus dados novamente</a></center>";
        }
    }

}

$newObjDadosLogin = new RecebeDadosLogin();
//$newObjDadosLogin->validaLogin($usuario, $senha);
