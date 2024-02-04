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
        $this->chave = md5(date("d/m/Y"));
        if (isset($_SESSION['logado']) == $this->chave) {
            ?> 
            <!DOCTYPE html>

            <?php
            header("Content-Type: text/html; charset=utf-8", true);
            Header('Cache-Control: no-cache');
            Header('Pragma: no-cache');
            ?>

            <title>Agenda</title>
            <link rel="icon" sizes="192x192" href="../icons/icon-128x128.png">
            <link rel="stylesheet" href="../web_tools/css/reset.css">
            <link rel="stylesheet" href="../web_tools/css/style.css">
            <link rel="stylesheet" href="../web_tools/css/menu.css">
            <script language="javascript" type="text/javascript" src="../web_tools/js/sessionStorangeMunicipe.js"></script>
            <script>
                function master() {
                    alert('Função habilitada somente para usuário com permissão!');
                }
            </script>

            <body onload="zeraSessao();">
                <div>
                    <ul id="nav">
                        <li><a href="#"><img src="../web_tools/img/key.png"></a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['master'] == TRUE) {
                                        echo '<a href="..\cadastrar\usuario\cad_usuario.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>Cadastrar Usuário</a></li>
                                <li><a href="../editar/senha/alterar_senha.php" target="meio">Alterar Senha</a></li>
                                <li><a href="../logout.php">Sair</a></li>
                            </ul>       
                        </li>
                        <li><a href="#">MUNÍCIPE</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['ic'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\pesquisar\municipe\pesquisar_municipe.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>PESQUISAR</a>
                                </li>
                                <li><?php
                                    if ($_SESSION['ic'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\cadastrar\municipe\cad_municipe.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INCLUIR</a></li>
                            </ul>
                        </li>

                        <li><a href="#">BAIRROS</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['ic'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\cadastrar\bairro\cad_bairro.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INCLUIR</a>
                                </li>
                                <li><?php
                                    if ($_SESSION['ic'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\listar\bairro\listar_bairro.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>LISTAR TODOS</a></li>
                            </ul>
                        </li>

                        <li><a href="#">GRUPOS DE SMS</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['ig'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\cadastrar\grupo\cad_grupo.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INCLUIR</a></li>
                                <li><?php
                                    if ($_SESSION['ig'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\listar\grupo\listar_grupo.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>LISTAR TODOS</a></li>
                            </ul>
                        </li>
                        <li><a href="#">MENSAGENS</a>
                            <ul>
                                <li><?php
                                    if ($_SESSION['im'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\cadastrar\mensagem\cad_mensagem.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>CADASTRAR NOVA</a> </li>
                                <li><?php
                                    if ($_SESSION['im'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="..\listar\mensagem\listar_mensagem.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>LISTAR MENSAGENS</a> </li>
                            </ul>
                        </li>
                        </li>
                        <li><a href="#">OPÇÕES</a>
                            <ul>
                                <li>
                                    <?php
                                    if ($_SESSION['c'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="../config/config.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>CONFIGURAÇÕES</a>
                                </li>
                                <li>
                                    <?php
                                    if ($_SESSION['es'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="../log/mensagem.php?load=1" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>LOG DE ENVIO</a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#">ENVIAR SMS</a>
                            <ul>
                                <li>
                                    <?php
                                    if ($_SESSION['es'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="../enviar/sms_individual.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>INDIVIDUAL</a>
                                </li>
                                <li>
                                    <?php
                                    if ($_SESSION['es'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="../enviar/sms_grupo.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>PARA GRUPO</a>
                                </li>
                                <li>
                                    <?php
                                    if ($_SESSION['es'] == TRUE || $_SESSION['master'] == TRUE) {
                                        echo '<a href="../enviar/sms_programado.php" target="meio">';
                                    } else {
                                        echo '<a href="#" onClick="master()">';
                                    }
                                    ?>PROGRAMADO</a>
                                </li>
                            </ul>
                        </li>

                        <script language = "JavaScript">
                            document.write("<div id='saudacao'>Usuário:<?php echo $_SESSION['nome']; ?></font></div>");
                        </script>
                    </ul>
                </div>
                <header>
                    <div id="relogio">
                    </div>
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
            ?>
            <script type="text/javascript">
                alert("Login ou Senha desconhecido, tente novamente!");
                window.open('../index.php', '_top');
            </script>
            <?php
        }
    }

}

$newObjDadosLogin = new RecebeDadosLogin();
//$newObjDadosLogin->validaLogin($usuario, $senha);
