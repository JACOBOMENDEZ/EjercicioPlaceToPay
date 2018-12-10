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
            
        <H1>Formulario Transacción</H1>
        
        <?php 
        if ($errors->any()) {
            ?>
            <div class="alert alert-danger" >
            <?php
            echo "Error de Validación<ul>";
            foreach ($errors->all() as $error){
                echo "<li>".$error."</li>";
            }
            echo "</ul>";
            ?>
            </div>
            <?php
        }
        ?>
        </div>
        </div>
        </div>
        
        <form role="form" method="POST" action="{{url('/transaccion/form')}}">
            <div class="row">
            <div class="col-md-6">
            <div class="well well-sm">
            <div class="form-group">
            {{csrf_field()}}
            
            <H4>PERSONA</H4>
            <label>Documento Identidad: </label>
            <input type="text" class="form-control" name="document" value="{{old('document')}}" size="12" maxlength="12">
            
            <label>Tipo Documento: </label>
            <select name="documentType" class="form-control">
                <option value="0">Seleccione el Tipo de Documento</option>
                <option value="CC" {{ old("documentType") == "CC" ? "selected":"" }}>CC = Cédula de ciudanía colombiana</option>
                <option value="CE" {{ old("documentType") == "CE" ? "selected":"" }}>CE = Cédula de extranjería</option>
                <option value="TI" {{ old("documentType") == "TI" ? "selected":"" }}>TI = Tarjeta de identidad</option>
                <option value="PPN" {{ old("documentType") == "PPN" ? "selected":"" }}>PPN = Pasaporte</option>
                <option value="NIT" {{ old("documentType") == "NIT" ? "selected":"" }}>NIT = Número de identificación tributaria</option>
                <option value="SSN" {{ old("documentType") == "SSN" ? "selected":"" }}>SSN = Social Security Number</option>
            </select>
            
            <label>Nombre: </label>
            <input type="text" class="form-control" name="firstName" value="{{old('firstName')}}" size="60" maxlength="60">
            
            <label>Apellidos: </label>
            <input type="text" class="form-control" name="lastName" value="{{old('lastName')}}" size="60" maxlength="60">
            
            <label>Compañía: </label>
            <input type="text" class="form-control" name="company" value="{{old('company')}}" size="60" maxlength="60">
            
            <label>Correo electrónico: </label>
            <input type="text" class="form-control" name="emailAddress" value="{{old('emailAddress')}}" size="80" maxlength="80">
            
            <label>Dirección postal: </label>
            <input type="text" class="form-control" name="address" value="{{old('address')}}" size="100" maxlength="100">
            
            <label>Ciudad: </label>
            <input type="text" class="form-control" name="city" value="{{old('city')}}" size="50" maxlength="50">
            
            <label>Provincia/Departamento: </label>
            <input type="text" class="form-control" name="province" value="{{old('province')}}" size="50" maxlength="50">
            
            <label>País: </label>
            <select name="country" class="form-control">
                <option value="0">Seleccione el País</option>
                <option value="ES" {{ old("country") == "ES" ? "selected":"" }}>España</option>
                <option value="CO" {{ old("country") == "CO" ? "selected":"" }}>Colombia</option>
            </select>
            
            <label>Teléfono fijo: </label>
            <input type="text" class="form-control" name="phone" value="{{old('phone')}}" size="30" maxlength="30">
            
            <label>Telefono móvil: </label>
            <input type="text" class="form-control" name="mobile" value="{{old('mobile')}}" size="30" maxlength="30">
            
            <label>Código postal: </label>
            <input type="text" class="form-control" name="postalCode" value="{{old('postalCode')}}" size="10" maxlength="10">
            
            </div>
            </div>
            </div>
            <div class="col-md-6">
            <div class="well well-sm">
            <div class="form-group">
            <H4>BANCO</H4>
            
            <label>Tipo: </label>
            <select name="bankInterface" class="form-control">
                <option value="0">Seleccione el Tipo</option>
                <option value="PERSONAS" {{ old("bankInterface") == "PERSONAS" ? "selected":"" }}>PERSONAS</option>
                <option value="EMPRESAS" {{ old("bankInterface") == "EMPRESAS" ? "selected":"" }}>EMPRESAS</option>
            </select>
            
            <label>Banco: </label>
            <select name="bankCode" class="form-control">
                
                @if ($bancos!="")
                    @foreach($bancos as $val)
                        <option value={{$val['bankCode']}} {{(old('bankCode') == $val['bankCode']?'selected':'')}} >{{$val['bankName']}}</option>
                    @endforeach
                @endif
                    
            </select> <?php if ($bancos=="") { echo "No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde"; } ?>
            
            </div>
            </div>
            </div>
            <div class="col-md-6">
            <div class="well well-sm">
            <div class="form-group">
            <H4>TRANSACCIÓN</H4>
            
            <label>Concepto: </label>
            <input type="text" class="form-control" name="reference" value="{{old('reference')}}" size="32" maxlength="32">
            
            <label>Descripción: </label>
            <textarea name="description" class="form-control" value="{{old('description')}}" rows="4" cols="50"></textarea>
            
            <label>Moneda: </label>
            <select name="currency" class="form-control">
                <option value="0">Seleccione la moneda</option>
                <option value="COP" {{ old("currency") == "COP" ? "selected":"" }}>Peso Colombiano</option>
                <option value="EUR" {{ old("currency") == "EUR" ? "selected":"" }}>Euro</option>
                <option value="USD" {{ old("currency") == "USD" ? "selected":"" }}>Dólar Americano</option>
            </select>
            
            <label>Valor Total: </label>
            <input type="text" class="form-control" name="totalAmount" value="{{old('totalAmount')}}" size="20" maxlength="20">
            
            <br/><hr/>
            <button class="btn btn-success" type="submit">Enviar</button>
            <a class="btn btn-success" href="/inicio">Volver<a>
            </div>
            </div>
            </div>
        </form>

        </div>
    </body>
</html>
