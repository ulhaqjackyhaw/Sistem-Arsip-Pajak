{{-- layouts/navigation.blade.php --}}
<nav class="bg-white shadow-sm border-b border-sky-200" x-data="{ open: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 justify-between">
        {{-- Kiri: Logo & Menu Utama (Desktop) --}}
        <div class="flex">
            {{-- Logo --}}
            <div class="flex flex-shrink-0 items-center">
                <a href="{{ route('home') }}">
                    <svg class="h-8 w-auto text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                    </svg>
                </a>
            </div>

            {{-- Menu Navigasi (Desktop) dengan gaya "Pill" yang lebih jelas --}}
            <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                @auth
                    @php $role = auth()->user()->role ?? null; @endphp
                    @if(in_array($role, ['officer','admin']))
                        <a href="{{ route('officer.vendors.index') }}" class="rounded-md px-4 py-2 text-base font-semibold transition-colors {{ request()->routeIs('officer.vendors.*') ? 'bg-sky-100 text-sky-700' : 'text-slate-600 hover:bg-slate-100' }}">
                            Tax Officer
                        </a>
                         <a href="{{ route('officer.bulk.form') }}" class="rounded-md px-4 py-2 text-base font-semibold transition-colors {{ request()->routeIs('officer.bulk.form') ? 'bg-sky-100 text-sky-700' : 'text-slate-600 hover:bg-slate-100' }}">
                            Bulk Upload
                        </a>
                    @endif
                    @if($role === 'admin')
                         <a href="{{ route('admin.vendors.index') }}" class="rounded-md px-4 py-2 text-base font-semibold transition-colors {{ request()->routeIs('admin.vendors.*') ? 'bg-sky-100 text-sky-700' : 'text-slate-600 hover:bg-slate-100' }}">
                            Admin Vendor
                        </a>
                    @endif
                    @if($role === 'vendor')
                         <a href="{{ route('vendor.documents.index') }}" class="rounded-md px-4 py-2 text-base font-semibold transition-colors {{ request()->routeIs('vendor.documents.*') ? 'bg-sky-100 text-sky-700' : 'text-slate-600 hover:bg-slate-100' }}">
                            Dokumen Saya
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Kanan: Pengguna & Auth (Desktop) dengan tombol dan font lebih besar --}}
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
            @auth
                <span class="text-base font-semibold text-slate-700 mr-4">
                    Halo, {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-base font-semibold text-slate-700 shadow-sm hover:bg-red-50 hover:border-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        Keluar
                    </button>
                </form>
            @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="rounded-md px-4 py-2 text-base font-semibold text-slate-600 hover:bg-slate-100 transition-colors">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-sky-600 px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-colors">
                            Daftar
                        </a>
                    @endif
                </div>
            @endguest
        </div>

        {{-- Tombol hamburger menu (Mobile) --}}
        <div class="-mr-2 flex items-center sm:hidden">
            <button type="button" @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sky-500" aria-controls="mobile-menu" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

    {{-- Menu mobile, tampilkan/sembunyikan berdasarkan status menu --}}
<div class="sm:hidden" x-show="open" x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-150 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">
    <div class="space-y-2 px-2 pt-2 pb-3">
        {{-- Link Navigasi Mobile dengan font dan padding lebih besar --}}
        @auth
            @php $role = auth()->user()->role ?? null; @endphp
            @if(in_array($role, ['officer','admin']))
                {{-- Gaya link aktif dibuat sangat kontras --}}
                <a href="{{ route('officer.vendors.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold {{ request()->routeIs('officer.vendors.*') ? 'bg-sky-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    Officer Vendor
                </a>
                <a href="{{ route('officer.bulk.form') }}" class="block rounded-md px-4 py-3 text-lg font-semibold {{ request()->routeIs('officer.bulk.form') ? 'bg-sky-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    Bulk Upload
                </a>
            @endif
            @if($role === 'admin')
                <a href="{{ route('admin.vendors.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold {{ request()->routeIs('admin.vendors.*') ? 'bg-sky-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    Admin
                </a>
            @endif
            @if($role === 'vendor')
                <a href="{{ route('vendor.documents.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold {{ request()->routeIs('vendor.documents.*') ? 'bg-sky-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    Dokumen Saya
                </a>
            @endif
        @endauth
    </div>

    {{-- Bagian Auth Mobile dengan tombol yang jelas --}}
    <div class="border-t border-slate-200 pt-4 pb-3">
        @auth
            <div class="px-4 mb-3">
                <div class="text-base font-medium text-slate-800">Halo, {{ auth()->user()->name }}</div>
                <div class="text-sm font-medium text-slate-500">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    {{-- Tombol Logout dibuat jelas dengan warna merah --}}
                    <button type="submit" class="block w-full rounded-md bg-red-500 px-4 py-3 text-left text-base font-semibold text-white hover:bg-red-600">
                        Keluar
                    </button>
                </form>
            </div>
        @else
            {{-- Tombol Masuk dan Daftar dibuat berdampingan dan jelas --}}
            <div class="grid grid-cols-2 gap-3 px-4">
                <a href="{{ route('login') }}" class="block w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-center text-base font-semibold text-slate-700 hover:bg-slate-50">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block w-full rounded-md bg-sky-500 px-4 py-3 text-center text-base font-semibold text-white hover:bg-sky-600">Daftar</a>
                @endif
            </div>
        @endguest
    </div>
</div>
</nav>