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
                    <div class="col-sm-6 mx-auto">
                        <div class="title m-b-md card text-center border p-2">
                            <h1 class="text-center">Estado de Las transacciones</h1>
                            @forelse ($transactions as $transaction)
                                @if ($loop->first)
                                <ul style="list-style:none">
                                @endif
                                <li> id: <br>{{ $transaction->transaction_id }}
                                    <br>  status:  <br> <strong>{{ $transaction->status }}</strong> </li>
                                    <hr>
                                @if ($loop->last)
                                </ul>
                                @endif
                                
                            @empty
                                
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
