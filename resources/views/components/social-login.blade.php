<div class="flex flex-col gap-4 font-ubuntu-medium">
    <div class="relative">
        <a href="{{route('social.redirect', ['provider' => \App\Enums\SocialProvider::FACEBOOK->value])}}"
           class="pointer-events-none flex items-center gap-3 bg-blue-600/50 text-white px-6 py-3 rounded-xl transition-all font-medium justify-center">
            <i class="lab la-facebook-f text-xl"></i> Continue with Facebook
        </a>
        <span class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs px-2 py-1 rounded-full shadow-sm">Coming Soon</span>
    </div>

    <div class="relative">
        <a href="{{route('social.redirect', ['provider' => \App\Enums\SocialProvider::GOOGLE->value])}}"
           class="flex items-center gap-3 bg-red-500/50 text-white px-6 py-3 rounded-xl transition-all font-medium justify-center">
            <i class="lab la-google text-xl"></i> Continue with Google
        </a>
        <span class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs px-2 py-1 rounded-full shadow-sm">Coming Soon</span>
    </div>

    <div class="relative">
        <a href="{{route('social.redirect', ['provider' => \App\Enums\SocialProvider::GITHUB->value])}}"
           class="flex items-center gap-3 bg-gray-800/50 text-white px-6 py-3 rounded-xl transition-all font-medium justify-center">
            <i class="lab la-github text-xl"></i> Continue with GitHub
        </a>
    </div>
</div>
