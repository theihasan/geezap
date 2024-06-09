@extends('auth.app')
@section('auth-title', 'Login - Geezap')
@section('auth-content')
    <section class="h-screen flex items-center justify-center relative overflow-hidden bg-[url('../../assets/images/hero/bg3.html')] bg-no-repeat bg-center bg-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black"></div>
        <div class="container">
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-md dark:shadow-gray-800 rounded-md">
                    <div class="p-6">
                        <a href="#">
                          <h2 class="text-5xl text-center">Geezap</h2>
                        </a>
                        <h5 class="my-6 text-xl font-semibold">Login</h5>
                        <form class="text-start" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1">
                                <div class="mb-4 text-start">
                                    <label class="font-semibold" for="LoginEmail">Email Address:</label>
                                    <input id="LoginEmail" type="email" name="email" class="form-input mt-3 rounded-md" placeholder="name@example.com">
                                    @error('email')
                                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4 text-start">
                                    <label class="font-semibold" for="LoginPassword">Password:</label>
                                    <input id="LoginPassword" type="password" name="password" class="form-input mt-3 rounded-md" placeholder="Password:">
                                    @error('password')
                                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-between mb-4">
                                    <div class="inline-flex items-center mb-0">
                                        <input
                                            class="form-checkbox rounded border-gray-200
                                            dark:border-gray-800 text-emerald-600 focus:border-emerald-300
                                            focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2"
                                            type="checkbox" name="remember" id="RememberMe">
                                        <label class="form-checkbox-label text-slate-400" for="RememberMe">Remember me</label>
                                    </div>
                                    <p class="text-slate-400 mb-0"><a href="{{route('password.request')}}" class="text-slate-400">Forgot password ?</a></p>
                                </div>

                                <div class="mb-4">
                                    <button class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700
                                           text-white rounded-md w-full" type="submit">Login</button>
                                </div>

                                <div class="text-center">
                                    <span class="text-slate-400 me-2">Don't have an account ?</span> <a href="{{route('register')}}" class="text-black dark:text-white font-bold">Sign Up</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="px-6 py-2 bg-slate-50 dark:bg-slate-800 text-center">
                        <p class="mb-0 text-gray-400 font-medium">© <script>document.write(new Date().getFullYear())</script> All Right reserved by Geezap</p>
                    </div>
                </div>
            </div>
        </div>
    </section><!--end section -->

    <div class="fixed bottom-3 end-3">
        <a href="#" class="back-button btn btn-icon bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white rounded-md"><i data-feather="arrow-left" class="size-4"></i></a>
    </div>
@endsection
