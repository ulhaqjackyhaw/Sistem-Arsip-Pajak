{{-- layouts/navigation.blade.php --}}
<nav class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            {{-- Kiri: Logo & Menu Utama --}}
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

            {{-- Kanan: Pengguna & Auth --}}
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
        </div>
    </div>
</nav>