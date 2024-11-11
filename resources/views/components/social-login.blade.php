<div class="flex flex-col gap-4 font-ubuntu-medium">
    <a href="" class="flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-all font-medium justify-center">
        <i class="lab la-facebook-f text-xl"></i> Continue with Facebook
    </a>
    <button type="button" class="flex items-center gap-3 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl transition-all font-medium justify-center">
        <i class="lab la-google text-xl"></i> Continue with Google
    </button>
    <a href="{{route('social.redirect', ['provider' => \App\Enums\SocialProvider::GITHUB->value])}}" class="flex items-center gap-3 bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-xl transition-all font-medium justify-center">
        <i class="lab la-github text-xl"></i> Continue with GitHub
    </a>
</div>
