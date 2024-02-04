// JavaScript Document
function mascara_data(data) {
    var mydata = '';
    ev = event.keyCode;
    if (ev >= 47 && ev < 58 || ev >= 95 && ev <= 105) {
        mydata = mydata + data;
        if (mydata.length == 2) {
            mydata = mydata + '/';
            document.getElementById('data').value = mydata;
        }
        if (mydata.length == 5) {
            mydata = mydata + '/';
            document.getElementById('data').value = mydata;
        }
        if (mydata.length == 10) {
            verifica_data();
        }
    } else {
        document.getElementById('data').value = mydata;
    }


}



function verifica_data() {

    if (document.getElementById('data').value != "") {
        dia = (document.getElementById('data').value.substring(0, 2));
        mes = (document.getElementById('data').value.substring(3, 5));
        ano = (document.getElementById('data').value.substring(6, 10));

        situacao = "";
        // verifica o dia valido para cada mes 
        if ((dia < 01) || (dia < 01 || dia > 30) && (mes == 04 || mes == 06 || mes == 09 || mes == 11) || dia > 31) {
            situacao = "falsa";
        }

        // verifica se o mes e valido 
        if (mes < 01 || mes > 12) {
            situacao = "falsa";
        }

        // verifica se e ano bissexto 
        if (mes == 2 && (dia < 01 || dia > 29 || (dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
            situacao = "falsa";
        }

        if (document.getElementById('data').value == "") {
            situacao = "falsa";
        }

        if (situacao == "falsa") {
            alert("Data inválida!");
            document.getElementById('data').focus();
        }
    }
}

