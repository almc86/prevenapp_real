<x-guest-layout>
    {{-- Encabezado --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Bienvenido de vuelta</h2>
        <p class="text-gray-500 mt-2 text-sm">Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Errores generales --}}
    @if ($errors->any())
        <div class="mb-5 p-4 bg-danger-50 border border-danger-200 rounded-xl">
            <div class="flex items-center gap-2">
                <i class='bx bx-error-circle text-danger-500 text-lg'></i>
                <p class="text-danger-700 text-sm font-medium">
                    {{ $errors->first('email') ?: 'Error al iniciar sesión.' }}
                </p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Correo electrónico
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-envelope text-gray-400 text-lg'></i>
                </div>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="tu@correo.com"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-danger-400 focus:ring-danger-500 focus:border-danger-500 @enderror"
                />
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Contraseña
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-primary-600 hover:text-primary-700 font-medium transition-colors" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <div class="relative" x-data="{ showPassword: false }">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                </div>
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full pl-11 pr-12 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                />
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <i class='bx text-lg' :class="showPassword ? 'bx-hide' : 'bx-show'"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-colors"
            >
            <label for="remember_me" class="ml-2.5 text-sm text-gray-600 select-none cursor-pointer">
                Mantener sesión iniciada
            </label>
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200"
        >
            <i class='bx bx-log-in text-lg'></i>
            Iniciar Sesión
        </button>
    </form>

    {{-- Separador --}}
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
            <i class='bx bx-shield-quarter text-sm'></i>
            <span>Conexión segura y encriptada</span>
        </div>
    </div>
</x-guest-layout>
