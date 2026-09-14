{{-- template: <head> compartido por app y auth --}}
@props(['title'])
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title }} · VentasFix</title>
<link rel="icon" href="{{ asset('images/logo.png') }}">

{{-- corre ANTES de pintar pa evitar saltos:
     - tema: lo q eligio el usuario (localStorage) > lo q tiene el sistema
     - sidebar: si lo dejo colapsado, arranca colapsado --}}
<script>
    (function () {
        var theme = localStorage.getItem('theme');
        var dark = theme ? theme === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (dark) document.documentElement.classList.add('dark');
        if (localStorage.getItem('sidebar') === 'collapsed') document.documentElement.dataset.sidebar = 'collapsed';
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
