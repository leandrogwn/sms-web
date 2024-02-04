<!DOCTYPE html>
<head>
    <?php
    Header('Cache-Control: no-cache');
    Header('Pragma: no-cache');
    ?>
    <meta charset="utf-8">
    <title>Inicial</title>

</head>
<body>
    <div align="center" id="tamanho"><br><br><br>
        <img src="web_tools/img/sms.jpg">
    </div>
    <script language="Javascript" type="text/javascript">
        parent.document.getElementById("altframe").height = document.getElementById("tamanho").scrollHeight + 300; //40: Margem Superior e Inferior, somadas
    </script>
</body>
</html>
