@extends('auth.app')
@section('auth-title', 'Reset Password - Geezap')
@section('auth-content')
        <section class="h-screen flex items-center justify-center relative overflow-hidden bg-[url('../../assets/images/hero/bg3.html')] bg-no-repeat bg-left-bottom bg-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black"></div>
            <div class="container">
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                    <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-md dark:shadow-gray-800 rounded-md">
                        <div class="p-6">
                            <a href="#">
                               <h2 class="text-5xl text-center">Geezap</h2>
                            </a>
                            <h5 class="my-6 text-xl font-semibold">Reset Your Password</h5>
                            <div class="grid grid-cols-1">
                                <p class="text-slate-400 mb-6">Please enter your email address. You will receive a link to create a new password via email.</p>
                                <form class="text-start" action="{{ route('password.email') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1">
                                        <div class="mb-4 text-start">
                                            <label class="font-semibold" for="LoginEmail">Email Address:</label>
                                            <input id="LoginEmail" type="email" name="email" value="{{ old('email') }}" class="form-input mt-3 rounded-md @error('email') border-red-500 @enderror" placeholder="name@example.com">
                                            @error('email')
                                            <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <input type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white rounded-md w-full" value="Send">
                                        </div>

                                        <div class="text-center">
                                            <span class="text-slate-400 me-2">Remember your password ? </span><a href="{{route('login')}}" class="text-black dark:text-white font-bold">Sign in</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="px-6 py-2 bg-slate-50 dark:bg-slate-800 text-center">
                            <p class="mb-0 text-gray-400 font-medium">© <script>document.write(new Date().getFullYear())</script>
                                All right reserved by Geezap</p>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--end section -->

        <div class="fixed bottom-3 end-3">
            <a href="#" class="back-button btn btn-icon bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white rounded-md"><i data-feather="arrow-left" class="size-4"></i></a>
        </div>

@endsection
