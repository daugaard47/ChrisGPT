<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chris GPT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 prose w-full max-w-none break-words dark:prose-invert light">
                    <p>This is a test of the Chat gpt-3.5-turbo API using Laravel Livewire.</p>
                    <p>It is a work in progress. Fine-tuning is needed.</p>
                    <p>You can learn more here <a class="prose-p:underline" href="https://platform.openai.com/docs/api-reference/chat">Open AI: Chat</a></p>
                    <p>I'm using the following:</p>
                    <ol>
                        <li><a class="prose-p:underline" href="https://laravel.com/">Laravel</a></li>
                        <li><a class="prose-p:underline" href="https://laravel-livewire.com/">Livewire</a></li>
                        <li><a class="prose-p:underline" href="https://tailwindcss.com/">Tailwind CSS</a></li>
                        <li><a class="prose-p:underline" href="https://tailwindcss.com/docs/typography-plugin/">@tailwindcss/typography (Prose)</a></li>
                        <li><a class="prose-p:underline" href="https://github.com/spatie/commonmark-highlighter">Spatie Commonmark Highlighter</a></li>
                    </ol>
                    <p>Note you will need an Open AI API key to use this.</p>
                    <p>See <a class="prose-p:underline" href="https://platform.openai.com/">Open AI: Platform</a></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
