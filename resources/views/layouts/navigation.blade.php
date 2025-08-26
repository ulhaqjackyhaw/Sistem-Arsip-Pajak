{{-- layouts/navigation.blade.php --}}
<nav class="bg-white shadow-sm" x-data="{ open: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            {{-- Kiri: Logo & Menu Utama (Desktop) --}}
            <div class="flex">
                {{-- Logo --}}
                <div class="flex flex-shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        {{-- Ganti dengan logo SVG atau gambar Anda --}}
                        <svg class="h-8 w-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                    </a>
                </div>

                {{-- Menu Navigasi (Desktop) --}}
                <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                    @auth
                        @php $role = auth()->user()->role ?? null; @endphp
                        @if(in_array($role, ['officer','admin']))
                            <a href="{{ route('officer.vendors.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('officer.vendors.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} text-sm font-medium">
                                Officer Vendor
                            </a>
                             <a href="{{ route('officer.bulk.form') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('officer.bulk.form') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} text-sm font-medium">
                                Bulk Upload
                            </a>
                        @endif
                        @if($role === 'admin')
                             <a href="{{ route('admin.vendors.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.vendors.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} text-sm font-medium">
                                Admin Vendor
                            </a>
                        @endif
                        @if($role === 'vendor')
                             <a href="{{ route('vendor.documents.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vendor.documents.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} text-sm font-medium">
                                Dokumen Saya
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Kanan: Pengguna & Auth (Desktop) --}}
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @auth
                    <span class="text-sm font-medium text-slate-700 mr-4">
                        Halo, {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Daftar
                            </a>
                        @endif
                    </div>
                @endguest
            </div>

            {{-- Tombol hamburger menu (Mobile) --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button type="button" @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    {{-- Ikon ketika menu tertutup --}}
                    <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    {{-- Ikon ketika menu terbuka --}}
                    <svg x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menu mobile, tampilkan/sembunyikan berdasarkan status menu --}}
    <div class="sm:hidden" x-show="open" x-transition:enter="duration-150 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="space-y-1 pt-2 pb-3">
            {{-- Link Navigasi Mobile --}}
            @auth
                @php $role = auth()->user()->role ?? null; @endphp
                @if(in_array($role, ['officer','admin']))
                    <a href="{{ route('officer.vendors.index') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('officer.vendors.*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                        Officer Vendor
                    </a>
                    <a href="{{ route('officer.bulk.form') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('officer.bulk.form') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                        Bulk Upload
                    </a>
                @endif
                @if($role === 'admin')
                    <a href="{{ route('admin.vendors.index') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.vendors.*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                        Admin Vendor
                    </a>
                @endif
                @if($role === 'vendor')
                    <a href="{{ route('vendor.documents.index') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('vendor.documents.*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                        Dokumen Saya
                    </a>
                @endif
            @endauth
        </div>

        {{-- Bagian Auth Mobile --}}
        <div class="border-t border-slate-200 pt-4 pb-3">
            @auth
                <div class="flex items-center px-4">
                    <span class="text-base font-medium text-slate-800">
                        Halo, {{ auth()->user()->name }}
                    </span>
                </div>
                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-base font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="block w-full px-4 py-2 text-base font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full px-4 py-2 text-base font-medium text-blue-600 hover:bg-slate-100 hover:text-blue-700">Daftar</a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>