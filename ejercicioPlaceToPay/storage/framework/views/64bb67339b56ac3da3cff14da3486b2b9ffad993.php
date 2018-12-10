<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
        <div class="row">
        <div class="col-md-12">
        <div class="well well-sm">
        <H1>Listado Transacciones</H1>
        <p>(Las información de transacciones expira a los 30 min)</p>
        
        <?php
        if ($transacciones!="") {
            echo "<table class=\"table table-striped\">";
            echo "<thead><tr><th scope=\"col\">Id Transacción</th><th scope=\"col\">Concepto</th><th scope=\"col\">Estado solicitud</th><th scope=\"col\">Estado transacción</th><th scope=\"col\">Mensaje</th></tr></thead><tbody>";
            foreach ($transacciones as $val) {
                
                echo "<tr><td>".$val["estado"]["transactionID"]."</td><td>".$val["estado"]["reference"]."</td><td>".$val["estado"]["returnCode"]."</td><td>".$val["estado"]["transactionState"]."</td><td>".$val["estado"]["responseReasonText"]."</td></tr>";
                
            }
            echo"</tbody></table>";
        }
        else {
            echo "<p>No hay transacciones que mostrar.</p>";
        }
        ?> 
            <p><a class="btn btn-success" href="/transaccion/listar">Actualizar<a></p>
                        <p><a class="btn btn-success" href="/inicio">Volver<a></p>
        </div>
        </div>
        </div>
        </div>
    </body>
</html>
