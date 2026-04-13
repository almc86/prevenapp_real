<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PrevenApp') }} - Iniciar Sesión</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Boxicons -->
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            {{-- Panel izquierdo - Branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-primary-700 via-primary-800 to-primary-950">
                {{-- Patrón decorativo de fondo --}}
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                {{-- Círculos decorativos --}}
                <div class="absolute -top-20 -left-20 w-80 h-80 bg-primary-500 rounded-full opacity-10"></div>
                <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-primary-400 rounded-full opacity-10"></div>
                <div class="absolute top-1/3 right-10 w-40 h-40 bg-warning-400 rounded-full opacity-10"></div>

                {{-- Contenido del panel --}}
                <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 xl:px-20">
                    {{-- Logo / Icono de seguridad --}}
                    <div class="mb-8">
                        <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20">
                            <svg class="w-14 h-14 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.83-3.23 9.36-7 10.57-3.77-1.21-7-5.74-7-10.57V6.3l7-3.12zm-1 5.82v2h2v-2h-2zm0 4v4h2v-4h-2z"/>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-4xl xl:text-5xl font-bold text-white text-center leading-tight mb-4">
                        PrevenApp
                    </h1>
                    <p class="text-primary-200 text-lg xl:text-xl text-center max-w-md leading-relaxed mb-12">
                        Sistema de Administración y Prevención de Riesgos Laborales
                    </p>

                    {{-- Características --}}
                    <div class="space-y-5 w-full max-w-sm">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-11 h-11 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/10">
                                <i class='bx bx-file text-xl text-warning-300'></i>
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">Gestión Documental</p>
                                <p class="text-primary-300 text-xs">Control y validación de documentos</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-11 h-11 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/10">
                                <i class='bx bx-building-house text-xl text-success-300'></i>
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">Multi-Empresa</p>
                                <p class="text-primary-300 text-xs">Principales, contratistas y subcontratistas</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-11 h-11 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/10">
                                <i class='bx bx-shield-quarter text-xl text-primary-300'></i>
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">Prevención de Riesgos</p>
                                <p class="text-primary-300 text-xs">Cumplimiento normativo y seguridad</p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer del panel --}}
                    <div class="absolute bottom-8 left-0 right-0 text-center">
                        <p class="text-primary-400 text-xs">&copy; {{ date('Y') }} PrevenApp. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>

            {{-- Panel derecho - Formulario --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-gray-50 px-6 sm:px-12 lg:px-16 xl:px-24">
                {{-- Logo móvil (visible solo en pantallas pequeñas) --}}
                <div class="lg:hidden mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-600 rounded-2xl mb-4">
                        <svg class="w-9 h-9 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.83-3.23 9.36-7 10.57-3.77-1.21-7-5.74-7-10.57V6.3l7-3.12z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">PrevenApp</h2>
                    <p class="text-gray-500 text-sm mt-1">Prevención de Riesgos Laborales</p>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                {{-- Footer móvil --}}
                <div class="lg:hidden mt-8 text-center">
                    <p class="text-gray-400 text-xs">&copy; {{ date('Y') }} PrevenApp. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </body>
</html>
