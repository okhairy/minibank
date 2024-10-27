<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money-Transfer - @yield('title')</title>
    <link rel="icon" href="{{ asset('minibank.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Ajoutez ici d'autres liens CSS si nécessaire -->
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white" style="background: #1C627B;">
            <div class="p-4">
                <img src="{{ asset('minibank.png') }}" alt="Logo" class="h-20 w-auto mr-2">
                <h1 class="text-2xl font-bold">Money-Transfer</h1>
            </div>
            <nav class="mt-4 custom-bg">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-700 text-white no-underline">
                    <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                </a>
                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 hover:bg-gray-700 text-white no-underline">
                    <i class="fas fa-exchange-alt mr-2"></i> Transactions
                </a>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Topbar -->
            <header class="bg-white shadow">
                <div class="px-4 py-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold"><i class="fas fa-home mr-2"></i>@yield('header')</h2>
                        <div class="flex items-center">
                            <i class="fas fa-user mr-2 text-[#40AEC9]"></i>
                            @if(Auth::check())
                                <span class="mr-4">{{ Auth::user()->name }}</span>
                            @else
                                <span class="mr-4">Invité</span>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
