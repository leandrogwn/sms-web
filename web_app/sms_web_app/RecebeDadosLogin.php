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
            <html lang="pt-br" Xmanifest="appcache.manifest">
                <head>
                    <meta charset="utf-8">
                    <meta http-equiv="x-ua-compatible" content="ie=edge">
                    <title>smsWeb</title>
                    <script type="text/javascript" src="../web_tools/js/validamascara.js"></script>

                    <meta name="description" content="smsWeb">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link rel="stylesheet" href="css/main.css">
                    <link rel="manifest" href="manifest.json">

                    <!-- Add to homescreen for Chrome on Android -->
                    <meta name="mobile-web-app-capable" content="yes">
                    <link rel="icon" sizes="192x192" href="icons/icon-128x128.png">
                    <meta name="theme-color" content="#F77F00">

                    <!-- Add to homescreen for Safari on iOS -->
                    <meta name="apple-mobile-web-app-capable" content="yes">
                    <meta name="apple-mobile-web-app-title" content="smsWeb">
                    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
                    <link rel="apple-touch-icon-precomposed" href="img/icon.png">

                    <!-- Tile icon for Win8 (144x144 + tile color) -->
                    <meta name="msapplication-TileImage" content="icons/icon-144x144.png">
                    <meta name="msapplication-TileColor" content="#F77F00">
                    <link href="css/materialize_1.css" rel="stylesheet" type="text/css"/>

                    <style>
                        #cadastrar{
                            text-align: right;
                            margin-top: 5px;
                            margin-right: 10px;
                        }
                        #municipe, #grupo, #bairro{
                            /*margin-top: -110px;*/
                        }
                        #obs{
                            font-size: 10px;
                        }
                    </style>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
                    <script type="text/javascript" src="../web_tools/js/validamascara.js"></script>
                    <script language="javascript" type="text/javascript" src="../web_tools/js/mascara.js"></script>
                </head>
                <body>
                    <div class="current-page">
                        <main class="smsweb">

                            <div class="topo-fixo z-depth-1">
                                <div class="valign-wrapper amber darken-2 white-text">
                                    <div>
                                    </div>

                                    <h5 class="titulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php echo $_SESSION['nome'] ?>)smsWeb</h5>

                                    <div>
                                        <i class="material-icons waves-effect waves-light
                                           waves-circle dropdown-button"
                                           data-activates="submenu" data-gutter="5"
                                           data-constrainwidth="false">
                                            more_vert
                                        </i>

                                        <ul id="submenu" class="dropdown-content">

                                            <li><a class="black-text" href="logout.php">Sair</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <ul class="tabs amber darken-2">

                                    <li class="tab">
                                        <a href="#municipe"
                                           class="white-text waves-effect waves-light">
                                            Munícipe
                                        </a>
                                    </li>

                                    <li class="tab">
                                        <a href="#bairro"
                                           class="white-text waves-effect waves-light">
                                            Bairro
                                        </a>
                                    </li>
                                    <li class="tab">
                                        <a href="#grupo"
                                           class="white-text waves-effect waves-light">
                                            Grupo
                                        </a>
                                    </li>

                                    <div class="indicator"></div>
                                </ul>
                            </div>


                            <div id="municipe" class="section">
                                <?php
                                include("./conecta_db.php");
                                ?>
                                <div id="inc_agenda" align="left">

                                    <form action="cad_municipe_bd.php" method="POST">
                                        <div class="input-field col s6">
                                            <input type="text" name="nome" required="true" id="nome">
                                            <label for="nome">Nome Completo*</label>
                                        </div>
                                        <label id="lbl_data_nasc">Data de Nascimento*</label>
                                        <input type="date" name="data_n" id="data" required>
                                        <div class="input-field col s12">
                                            <input type="email" name="email" id="email" class="validate">
                                            <label for="email">E-mail</label>
                                        </div>

                                        <label>Selecione o Bairro*</label>
                                        <div class="input-field col s12">
                                            <select name="bairro" class="browser-default">
                                                <option value="" disabled selected>Escolha uma opção</option>
                                                <?php
                                                $buscar_bairro = mysql_query("SELECT *, upper(bairro) as bairroM FROM bairro WHERE situacao = 1;") or die("Nâo foi possível carregar os dados da tabela de Bairro" . mysql_error());
                                                while ($registro_bairro = mysql_fetch_assoc($buscar_bairro)) {
                                                    ?>
                                                    <option value="<?php echo $registro_bairro['id_bairro']; ?>"><?php echo $registro_bairro['bairroM']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="input-field col s6">
                                            <input name="rua" type="text" required id="rua">
                                            <label for="rua">Endereço*</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input name="numero" type="text" required id="numero">
                                            <label for="numero">Número*</label>
                                        </div>

                                        <fieldset>
                                            <legend>Selecione os grupos do Contato</legend>
                                            <div id="checkboxes">
                                                <?php
                                                $listaGrupo = mysql_query("SELECT *, upper(grupo) as grupoM FROM grupo WHERE situacao = 1 and id_grupo <> 1 ORDER BY grupo ASC") or die("Não foi possivel carregar os dados da tabela" . mysql_error());
                                                while ($regGrupos = mysql_fetch_array($listaGrupo)) {
                                                    ?>
                                                    <input type="checkbox" class="check_grupo" id="<?php echo $regGrupos['id_grupo']; ?>" value="<?php echo $regGrupos['id_grupo']; ?>" name="box[]" />
                                                    <label for="<?php echo $regGrupos['id_grupo']; ?>"><?php echo $regGrupos['grupoM']; ?></label>
                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </fieldset>

                                        <fieldset id="f_sexo">
                                            <legend>Sexo</legend>
                                            <input type="radio" name="sexo" id="1" value="m" class="radio" checked><label for="1">Masculino</label><input type="radio" name="sexo" id="0" value="f" class="radio"><label for="0">Feminino</label>
                                        </fieldset>

                                        <fieldset>
                                            <legend>Telefones</legend>
                                            <div class="input-field col s6">
                                                <i class="material-icons prefix">phone</i>
                                                <input type="tel" class="validate" name="fone_sms" id="fonesms" required>
                                                <label for="fone_sms">Celular SMS*</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <i class="material-icons prefix">phone</i>
                                                <input type="tel" class="validate" name="fone_fixo" id="fone1" >
                                                <label for="fone_fixo">Telefone Fixo</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <i class="material-icons prefix">phone</i>
                                                <input type="tel"  class="validate" name="fone_recado" id="fone2" >
                                                <label for="fone_recado">Telefone Recado</label>
                                            </div>
                                            <div id="obs">Obs: (*)Campos obrigatórios</div>
                                        </fieldset>
                                        <div id="cadastrar">
                                            <button class="btn waves-effect waves-light" type="submit" name="action">Cadastrar munícipe&nbsp;&nbsp;
                                                <i class="material-icons right">send</i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                mysql_free_result($buscar_bairro);
                                mysql_free_result($listaGrupo);
                                mysql_close($con);
                                ?>
                            </div>

                            <div id="bairro" class="section">
                                <div id="inc_agenda">
                                    <form action="cad_bairro_bd.php" method="post">
                                        <fieldset>
                                            <legend>Inclusão de Bairros</legend>
                                            <div id="lbl">Informe o nome do bairro no campo abaixo para incluir</div><br>
                                            <div class="input-field col s6">
                                                <input type="text" name="inc_bairro" required id="inc_bairro">
                                                <label for="inc_bairro">Nome do bairro*</label>
                                            </div>
                                            <div id="obs">Obs: (*)Campos obrigatórios</div>
                                        </fieldset>
                                        <br>
                                        <div id="cadastrar">
                                            <button class="btn waves-effect waves-light" type="submit" name="action">Cadastrar bairro&nbsp;&nbsp;
                                                <i class="material-icons right">send</i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="grupo" class="section">
                                <div id="inc_agenda">
                                    <form action="cad_grupo_bd.php" method="post">
                                        <fieldset>
                                            <legend>Inclusão de Grupos</legend>
                                            <div id="lbl">Informe o nome do grupo no campo abaixo para incluir</div><br>
                                            <div class="input-field col s6">
                                                <input type="text" name="inc_grupo" required id="inc_grupo">
                                                <label for="inc_grupo">Nome do grupo*</label>
                                            </div>
                                            <div id="obs">Obs: (*)Campos obrigatórios</div>
                                        </fieldset>
                                        <br>

                                        <div id="cadastrar">
                                            <button class="btn waves-effect waves-light" type="submit" name="action">Cadastrar grupo&nbsp;&nbsp;
                                                <i class="material-icons right">send</i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </main>

                    </div><!--/.current-page-->

                    <script src="js/vendor/jquery.min.js"></script>
                    <script src="js/vendor/materialize-0.97.0.min.js"></script>
                    <script src="js/spa.js"></script>
                    <script src="js/main.js"></script>
                    <script src="js/install.js"></script>
                </body>
            </html>


            <?php
        } else {
            ?>
            <script type="text/javascript">
                alert("Login ou Senha desconhecido, tente novamente!");
                window.history.back();
            </script>
            <?php
        }
    }

}

$newObjDadosLogin = new RecebeDadosLogin();
