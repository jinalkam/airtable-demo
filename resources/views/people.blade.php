<!DOCTYPE html>
<html>
    <head>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <!-- Add icon library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="py-20">
        <div id="app">
     
            <people-component :users="{{ $users }}"></people-component>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>