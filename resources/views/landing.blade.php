<x-guest-layout>
    <div class="text-center">
        {{-- <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
            Portal Masuk Website SIAJAK
        </h1> --}}
        <p class="mt-4 text-lg text-slate-600 text-white ">
            Silakan masuk sesuai dengan peran Anda.
        </p>

        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:mx-auto lg:max-w-2xl">
            <a href="{{ route('login') }}"
               class="group relative flex flex-col items-center justify-center rounded-2xl bg-transparant p-8 shadow-sm ring-1 ring-slate-200 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl hover:ring-indigo-500">

                <div class="mb-4 rounded-lg bg-indigo-500 p-4 text-white transition-transform duration-300 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-9 w-9">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M8.25 21V3.75h7.5V21M8.25 3.75h7.5m-7.5 4.5h7.5m-7.5 4.5h7.5m-7.5 4.5h7.5M12 21v-3.75" />
                    </svg>
                </div>
                <h2 class="text-xl text-white font-semibold text-slate-800">
                    Tax Officer / Admin
                </h2>
                <p class="mt-1 text-white text-base text-slate-500">
                    Masuk untuk manajemen.
                </p>
            </a>

            <a href="{{ route('vendor.login.form') }}"
               class="group relative flex flex-col items-center justify-center rounded-2xl bg-transparant p-8 shadow-sm ring-1 ring-slate-200 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl hover:ring-teal-500">

                <div class="mb-4 rounded-lg bg-teal-500 p-4 text-white transition-transform duration-300 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-9 w-9">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 21v-7.5A.75.75 0 0114.25 12h.75c.414 0 .75.336.75.75v7.5m-4.5 0v-7.5A.75.75 0 0110.5 12h.75c.414 0 .75.336.75.75v7.5m-4.5 0v-7.5A.75.75 0 016.75 12h.75c.414 0 .75.336.75.75v7.5m6-15H5.25v7.5H18.75V6M18.75 6H19.5v15h-15V6h.75m1.5-1.5h10.5a.75.75 0 01.75.75v1.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75v-1.5a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white text-slate-800">
                    Vendor (NPWP)
                </h2>
                <p class="mt-1 text-base text-white text-slate-500">
                    Masuk dengan akun vendor.
                </p>
            </a>
        </div>
    </div>
</x-guest-layout>