<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vue Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="/css/app.css">

        <style>
            .nav-link{
                cursor: pointer;
            }
        </style>
    </head>
    <body class="layout-top-nav" data-gr-c-s-loaded="true" style="height: auto;">
        <div id="app" class="wrapper">
            {{-- <app></app> --}}
            {{-- <router-view></router-view> --}}
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>
