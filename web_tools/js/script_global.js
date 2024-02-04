// JavaScript Document
function reset_form() {
    document.getElementById('nome').value = "";
    document.getElementById('data_n').value = "";
    document.getElementById('email').value = "";
    document.getElementById('ende').value = "";
    document.getElementById('fonesms').value = "";
    document.getElementById('grupo').value = "Selecione o Grupo";
    document.getElementById('fone1').value = "";
    document.getElementById('fone2').value = "";

    document.getElementById('lbl_n_1').style.display = "block";
    document.getElementById('lbl_n_2').style.display = "none";

    document.getElementById('lbl_dn_1').style.display = "block";
    document.getElementById('lbl_dn_2').style.display = "none";
    document.getElementById('data').setAttribute('required');

    document.getElementById('ende').setAttribute('required');

    document.getElementById('f_sexo').style.display = "block";
    document.getElementById('0').disabled = false;
    document.getElementById('1').disabled = false;

    document.getElementById('fonesms').style.display = "block";
}