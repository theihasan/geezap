<div class="flex flex-col gap-3 font-ubuntu-medium">
    @php
        session(['url.intended' => url()->previous()]);
    @endphp
    
    <!-- Google Login -->
    <a href="{{route('social.redirect', ['provider' => \App\Enums\SocialProvider::GOOGLE->value])}}"
       class="flex items-center gap-3 bg-red-500/50 text-white px-8 py-3 rounded-full transition-all font-medium justify-center hover:bg-red-500/70 hover:shadow-lg">
        <i class="lab la-google text-xl"></i> 
        <span>Continue with Google</span>
    </a>

    <!-- GitHub Login -->
    <a href="{{route('social.redirect', ['provider' => \App\Enums\SocialProvider::GITHUB->value])}}"
       class="flex items-center gap-3 bg-gray-700/50 dark:bg-gray-800/50 text-white px-8 py-3 rounded-full transition-all font-medium justify-center hover:bg-gray-700/70 dark:hover:bg-gray-800/70 hover:shadow-lg">
        <i class="lab la-github text-xl"></i> 
        <span>Continue with GitHub</span>
    </a>
</div>
