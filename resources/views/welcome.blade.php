<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-100">

<div
    x-data="{ section: localStorage.getItem('section') || 'equipos' }"
    x-init="$watch('section', value => localStorage.setItem('section', value))"
    class="flex min-h-screen"
>

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl">

        <div class="p-6 text-2xl font-bold border-b border-slate-700">
            Inventario
        </div>

        <nav class="flex-1 p-4 space-y-2">

            <button
                @click="section = 'equipos'"
                :class="section === 'equipos' ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800'"
                class="w-full text-left px-4 py-2 rounded-lg transition-all duration-200"
            >
                Equipos
            </button>

            <button
                @click="section = 'categorias'"
                :class="section === 'categorias' ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800'"
                class="w-full text-left px-4 py-2 rounded-lg transition-all duration-200"
            >
                Categorías
            </button>

            <button
                @click="section = 'subcategorias'"
                :class="section === 'subcategorias' ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800'"
                class="w-full text-left px-4 py-2 rounded-lg transition-all duration-200"
            >
                Subcategorías
            </button>

        </nav>

        <div class="p-4 border-t border-slate-700 text-sm text-slate-400">
            Sistema v1.0
        </div>

    </aside>

    {{-- CONTENIDO --}}
    <main class="flex-1 p-10">

        <div
            class="bg-white shadow-md rounded-2xl p-8 min-h-[500px]"
            x-transition
        >

            {{-- Equipos --}}
            <div
                x-show="section === 'equipos'"
                x-transition.opacity.duration.300ms
            >
                <livewire:equipos />
            </div>

            {{-- Categorías --}}
            <div
                x-show="section === 'categorias'"
                x-transition.opacity.duration.300ms
            >
                <livewire:categoria-padre />
                <div class="mt-6">
                    <livewire:categoria-hijo />
                </div>
            </div>

            {{-- Subcategorías --}}
            <div
                x-show="section === 'subcategorias'"
                x-transition.opacity.duration.300ms
            >
                <livewire:subcategoria />
            </div>

        </div>

    </main>

</div>

@livewireScripts
</body>
</html>
