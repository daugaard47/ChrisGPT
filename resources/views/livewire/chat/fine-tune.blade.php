<div>
    <div>
        @if($error)
            <p class="text-red-500">{{ $error }}</p>
        @elseif($response)
            <pre>
            {{ json_encode($response, JSON_PRETTY_PRINT) }}
        </pre>
        @endif
    </div>
    <form wire:submit.prevent="fineTune">
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="prompt">
                Prompt:
            </label>
            <textarea
                wire:model="prompt"
                class="form-input py-2 px-3 rounded-md text-gray-700 leading-5 focus:outline-none focus:shadow-outline-blue focus:border-blue-300"
                id="prompt"
            ></textarea>
            @error('prompt')
            <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="maxTokens">
                Max Tokens:
            </label>
            <input
                wire:model="maxTokens"
                class="form-input py-2 px-3 rounded-md text-gray-700 leading-5 focus:outline-none focus:shadow-outline-blue focus:border-blue-300"
                id="maxTokens"
                type="number"
            >
            @error('maxTokens')
            <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="file">
                File:
            </label>
            <input
                wire:model="file"
                class="form-input py-2 px-3 rounded-md text-gray-700 leading-5 focus:outline-none focus:shadow-outline-blue focus:border-blue-300"
                id="file"
                type="file"
            >
            @error('file')
            <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md mr-2">
            Fine-tune
        </button>
        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md" wire:click="resetProperties">
            Reset
        </button>
    </form>
</div>
