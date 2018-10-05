<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PTP Simple Pay Integration</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content p-5">
                <div class="row">
                    <div class="col-sm-12 mx-auto p-3">
                        <div class="links text-center mx-auto">
                            <a class="link btn" href="{{route('welcome')}} ">Home</a>
                            <a class="link btn" href="{{route('listar')}} ">Listar Transacciones</a>
                        </div>
                    </div>
                    @foreach ($errors as $error)
                    @if ($loop->first)
                        <div class="col-sm-12 mx-auto p-3">
                            <div class=" text-center mx-auto">
                    @endif
                        <p>{{ $error }}</p>                    
                    @if ($loop->last)
                            </div>
                        </div>
                    @endif
                    
                    @endforeach
                    <div class="col-sm-6 mx-auto">
                <div class="title m-b-md">
                    Bienvenido
                </div>
                <form action="{{route('store')}}" method="POST">
                    @csrf
                    <label for="medio"> Medio de pago</label>
                    <input required class="form-control" type="text" name="medio" id="medio" value="PSE" readonly> <br>
                    <label for="documentType"> Tipo de documento</label> <br>
                    <select class="form-control" name="documentType" id="documentType">
                        <option value="CC" selected> Cedula Colombiana</option>
                        <option value="CE"> Cedula Extranjeria</option>
                        <option value="TI"> Tarjeta de Identidad</option>
                        <option value="PPN" > Pasaporte</option>
                        <option value="NIT"> Numero de identificacion tributaria</option>
                        <option value="SSN"> Social Security Number</option>
                    </select><br>
                    <label for="document"> Documento </label> <br>
                    <input required class="form-control" type="text" name="document" id="document" value="8798328776"> <br>
                    <label for="firstName">  Nombres </label> <br>
                    <input required class="form-control" type="text" name="firstName" id="firstName" value="Javier"> <br>
                    <label for="lastName">  Apellidos </label> <br>
                    <input required class="form-control" type="text" name="lastName" id="lastName" value="Cabello"> <br>
                    <label for="email">  Email </label> <br>
                    <input required class="form-control" type="email" name="email" id="email" value="jaacu.97@gmail.com"> <br>
                    <label for="description">  Descripcion </label> <br>
                    <input required class="form-control" type="text" name="description" id="description" value="Esta es una descripcion valida"> <br>
                    <label for="company">  Company </label> <br>
                    <input required class="form-control" type="text" name="company" id="company" value="Company"> <br>
                    <label for="address">  address </label> <br>
                    <input required class="form-control" type="text" name="address" id="address" value="Totally not a fake address"> <br>
                    <label for="city">  city </label> <br>
                    <input required class="form-control" type="text" name="city" id="city" value="Bogota"> <br>
                    <label for="province">  province </label> <br>
                    <input required class="form-control" type="text" name="province" id="province" value="Province"> <br>
                    <label for="country">  country </label> <br>
                    <input required class="form-control" type="text" name="country" id="country" value="Colombia"> <br>
                    <label for="phone">  phone </label> <br>
                    <input required class="form-control" type="text" name="phone" id="phone" value="587276905"> <br>
                    <label for="mobile">  mobile </label> <br>
                    <input required class="form-control" type="text" name="mobile" id="mobile" value="587276905"> <br>
                    <label for="">   Cuenta Corriente
                    <input required  type="radio" name="cuenta" id="cuenta1" value="0" checked></label> 
                   <label for=""> Cuenta Ahorro
                    <input required  type="radio" name="cuenta" id="cuenta2" value="1"></label> <br>
                    <label for="">   Persona
                    <input required  type="radio" name="bankType" id="bankType1" value="0" checked></label> 
                    <label for=""> Empresa
                    <input required  type="radio" name="bankType" id="bankType2" value="1"></label> <br>
                <label for="bank"> <br> Por favor, escoga un banco </label>
                @forelse ($bankList as $bank)
                    @if ($loop->first)
                        <select class="form-control" name="bank" id="bank">
                    @endif
                        <option value="{{ $bank->bankCode }}"> {{ $bank->bankName }} </option>
                    @if ($loop->last)
                        </select>
                    @endif
                    
                @empty
                No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde
                @endforelse
                <br>
                <label for="monto"> Monto </label> <br>
                <input class="form-control" type="number"  id="monto" name="monto" step="100" min="1" value="1000">
                <button type="submit">Pagar</button>
            </form>

                </div>
            </div>
        </div>
        </div>
    </body>
</html>
