
</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('scripts')
    </head>
    <title>Document</title>
</head>
<body>
    <div class="app font-sans min-w-screen min-h-screen bg-grey-lighter py-8 px-4">

        <div class="mail__wrapper max-w-md mx-auto">

          <div class="mail__content bg-white p-8 shadow-md">

            <div class="content__header h-64 flex flex-col items-center justify-center text-center tracking-wide leading-normal bg-black -mx-8 -mt-8">
                <h1 class="text-red text-5xl">Atualização no seu pedido de devolução</h1>
            </div>

            <div class="content__body py-8 border-b">
              <p>
                Olá<br><br>
                Você recebeu um novo pedido de Devolução
              </p>
              <a href=" http://www.3idistribuidora.com.br/devolucoes" class="text-white text-sm tracking-wide bg-red rounded w-full my-8 p-4 " >ACOMPANHAR PEDIDO</a>
              <p class="text-sm">
                Atenciosamente<br>
                3i Distribuidora
              </p>
            </div>

            <div class="content__footer mt-8 text-center text-grey-darker">
              <h3 class="text-base sm:text-lg mb-4">Agradecemento a preferencia!</h3>
              <p>www.3idistribuidora.com.br</p>
            </div>

          </div>

          </div>


        </div>

</body>
</html>
