<!DOCTYPE html>
<html>
    <head>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="py-20">
        <div id="app">
            <people-component :data="{{ $data }}"></people-component>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>