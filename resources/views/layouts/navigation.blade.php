<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo / Home -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-800 font-semibold">
                        Bukti Pajak
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                @auth
                    <div class="me-3 text-sm text-gray-600">
                        Halo, <strong>{{ auth()->user()->name }}</strong>
                    </div>

                    @if(method_exists(auth()->user(), 'isVendor') && auth()->user()->isVendor())
                        <form method="POST" action="{{ route('vendor.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none transition">
                                Logout
                            </button>
                        </form>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-3 py-2 text-sm rounded-md text-gray-700 hover:text-gray-900">
                        Login Petugas/Admin
                    </a>
                    <a href="{{ route('vendor.login.form') }}"
                       class="ms-2 inline-flex items-center px-3 py-2 text-sm rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Login Vendor (NPWP)
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>
