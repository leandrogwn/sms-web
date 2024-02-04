function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function () {
            if (oldonload) {
                oldonload();
            }
            func();
        }
    }
}

function zera_grupo() {
    document.getElementById('inc_grupo').value = "";
}

//Salvar sessão
function salvaDados() {
    //Exibe tela de cadastro de grupo
    document.getElementById('pop').style.display = 'block';

    //limpa campo
    document.getElementById('inc_grupo').value = "";

    // Cria os itens da tela
    var nome = document.getElementById('nome').value;
    var data = document.getElementById('data').value;
    var email = document.getElementById('email').value;
    var bairro = document.getElementById('bairro').value;
    var rua = document.getElementById('rua').value;
    var numero = document.getElementById('numero').value;
    var fonesms = document.getElementById('fonesms').value;
    var fixo = document.getElementById('fone1').value;
    var recado = document.getElementById('fone2').value;

    //salva os itens em sessão
    window.sessionStorage.setItem('nome', nome);
    window.sessionStorage.setItem('data', data);
    window.sessionStorage.setItem('email', email);
    window.sessionStorage.setItem('bairro', bairro);
    window.sessionStorage.setItem('rua', rua);
    window.sessionStorage.setItem('numero', numero);
    window.sessionStorage.setItem('fonesms', fonesms);
    window.sessionStorage.setItem('fixo', fixo);
    window.sessionStorage.setItem('recado', recado);
}
function carregaSessao() {
    document.getElementById('nome').setAttribute('value', window.sessionStorage.getItem('nome'));
    document.getElementById('data').setAttribute('value', window.sessionStorage.getItem('data'));
    document.getElementById('email').setAttribute('value', window.sessionStorage.getItem('email'));
    document.getElementById('bairro').value = window.sessionStorage.getItem('bairro');
    document.getElementById('rua').setAttribute('value', window.sessionStorage.getItem('rua'));
    document.getElementById('numero').setAttribute('value', window.sessionStorage.getItem('numero'));
    document.getElementById('fonesms').setAttribute('value', window.sessionStorage.getItem('fonesms'));
    document.getElementById('fixo').setAttribute('value', window.sessionStorage.getItem('fixo'));
    document.getElementById('recado').setAttribute('value', window.sessionStorage.getItem('recado'));
}
function zeraSessao() {
    window.sessionStorage.setItem('nome', '');
    window.sessionStorage.setItem('data', '');
    window.sessionStorage.setItem('email', '');
    window.sessionStorage.setItem('bairro', '');
    window.sessionStorage.setItem('rua', '');
    window.sessionStorage.setItem('numero', '');
    window.sessionStorage.setItem('fonesms', '');
    window.sessionStorage.setItem('fixo', '');
    window.sessionStorage.setItem('recado', '');
}