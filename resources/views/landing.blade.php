<x-guest-layout>
    <div class="text-center">
        <h1 class="text-3xl font-bold mb-3 text-gray-800">Portal Bukti Pajak</h1>
        <p class="text-gray-600 mb-8">Silakan pilih tipe login Anda</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-xl mx-auto">
            <!-- Login Petugas/Admin -->
            <a href="{{ route('login') }}"
               class="group block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-lg hover:border-blue-600 transition duration-300">
                <div class="flex flex-col items-center">
                    <div class="p-4 rounded-full bg-blue-600 text-white mb-4 group-hover:scale-110 transition">
                        <!-- Icon Petugas/Admin -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                   d="M5.121 17.804A4 4 0 0112 15h0a4 4 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Login Petugas/Admin</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk sebagai petugas atau admin</p>
                </div>
            </a>

            <!-- Login Vendor -->
            <a href="{{ route('vendor.login.form') }}"
               class="group block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-lg hover:border-green-600 transition duration-300">
                <div class="flex flex-col items-center">
                    <div class="p-4 rounded-full bg-green-600 text-white mb-4 group-hover:scale-110 transition">
                        <!-- Icon Vendor -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                   d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Login Vendor (NPWP)</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk dengan akun vendor/NPWP</p>
                </div>
            </a>
        </div>
    </div>
</x-guest-layout>
