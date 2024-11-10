<div x-data="{
        show: false,
        message: '',
        type: 'success',
        types: {
            success: 'bg-green-100 border-green-400 text-green-700',
            error: 'bg-red-100 border-red-400 text-red-700',
            warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700'
        }
    }"
    x-on:notify.window="
        show = true;
        message = $event.detail[0].message;
        type = $event.detail[0].type || 'success';
        setTimeout(() => show = false, 3000)
    "
>
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-8"
         :class="types[type]"
         class="fixed top-4 right-4 px-4 py-3 rounded border flex items-center z-50"
    >
        <span x-text="message"></span>
    </div>
</div>
