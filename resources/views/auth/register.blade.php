<x-guest-layout>
    {{-- Encabezado --}}
    <div class="mb-6">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success-50 border border-success-200 text-success-700 text-xs font-semibold mb-3">
            <i class='bx bx-gift'></i> 14 días gratis · sin tarjeta
        </span>
        <h2 class="text-2xl font-bold text-gray-900">Crea tu cuenta</h2>
        <p class="text-gray-500 mt-2 text-sm">Elige tu plan y comienza a controlar tu documentación hoy.</p>
    </div>

    {{-- Errores generales --}}
    @if ($errors->any())
        <div class="mb-5 p-4 bg-danger-50 border border-danger-200 rounded-xl">
            <div class="flex items-start gap-2">
                <i class='bx bx-error-circle text-danger-500 text-lg'></i>
                <ul class="text-danger-700 text-sm font-medium list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ plan: '{{ old('plan', $planSeleccionado) }}' }">
        @csrf

        {{-- Selección de plan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tu plan</label>
            <div class="space-y-2.5">
                @foreach ($planes as $p)
                    <label class="block cursor-pointer">
                        <input type="radio" name="plan" value="{{ $p->codigo }}" x-model="plan" class="sr-only">
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl border-2 transition-all"
                            :class="plan === '{{ $p->codigo }}' ? 'border-primary-500 bg-primary-50 ring-1 ring-primary-200' : 'border-gray-200 bg-white hover:border-gray-300'"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-5 h-5 rounded-full border-2 transition-colors"
                                    :class="plan === '{{ $p->codigo }}' ? 'border-primary-500' : 'border-gray-300'"
                                >
                                    <span class="w-2.5 h-2.5 rounded-full bg-primary-500" x-show="plan === '{{ $p->codigo }}'"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $p->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->storage_gb }} GB · {{ $p->max_trabajadores ? $p->max_trabajadores.' trabajadores' : 'Trabajadores ilimitados' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if ($p->precio_clp == 0)
                                    <p class="text-sm font-bold text-success-600">Gratis</p>
                                @else
                                    <p class="text-sm font-bold text-gray-900">${{ number_format($p->precio_clp, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-400">/mes</p>
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-2">El plan <strong>Gratis</strong> no caduca. Los planes pagos incluyen <strong>14 días gratis</strong> — al final decides si continúas.</p>
        </div>

        {{-- Nombre --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre completo</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-user text-gray-400 text-lg'></i>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    placeholder="Tu nombre"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" />
            </div>
        </div>

        {{-- Empresa --}}
        <div>
            <label for="empresa" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre de tu empresa u organización</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-building-house text-gray-400 text-lg'></i>
                </div>
                <input id="empresa" type="text" name="empresa" value="{{ old('empresa') }}" required autocomplete="organization"
                    placeholder="Ej: Constructora Andes"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" />
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-envelope text-gray-400 text-lg'></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    placeholder="tu@correo.com"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" />
            </div>
        </div>

        {{-- Password --}}
        <div x-data="{ showPassword: false }">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Contraseña</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                </div>
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full pl-11 pr-12 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" />
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i class='bx text-lg' :class="showPassword ? 'bx-hide' : 'bx-show'"></i>
                </button>
            </div>
        </div>

        {{-- Confirmar Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Repetir contraseña</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" />
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
            <i class='bx bx-rocket text-lg'></i>
            Crear mi cuenta
        </button>

        <p class="text-center text-sm text-gray-500">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>
