<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chris GPT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:chat.chat-form/>
                </div>
            </div>
        </div>
    </div>
{{--    @push('highlight-css')--}}
{{--        <link rel="stylesheet"--}}
{{--              href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">--}}
{{--    @endpush--}}
{{--    @push('highlight-js')--}}
{{--        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>--}}
{{--        <script>--}}
{{--            window.addEventListener('DOMContentLoaded', (event) => {--}}
{{--                hljs.highlightAll();--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endpush--}}
</x-app-layout>
