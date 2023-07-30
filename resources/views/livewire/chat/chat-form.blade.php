<div>
    <div class="mb-4">
        <p class="block text-sm font-medium leading-5 text-gray-500">Generated text will display below here...</p>
        <div class="mt-1 flex w-[calc(100%-50px)] md:flex-col lg:w-[calc(100%-115px)]">
            <div class="py-4 md:px-6 px-4 text-gray-700 block w-full sm:text-sm sm:leading-5 flex flex-grow flex-col gap-3">
                <div class="min-h-[20px] flex flex-col items-start gap-4 whitespace-pre-wrap">
                    <!-- Iterate over the chat log and display each entry. -->
                    @foreach ($chatLog as $chatEntry)
                        <div class="flex items-start {{ $chatEntry['role'] === 'user' ? 'justify-end' : 'justify-start' }} my-2">
                            <p class="w-16 {{ $chatEntry['role'] === 'user' ? 'text-right pr-2' : 'text-left pl-2' }}">
                                {{$chatEntry['role'] === 'user' ? 'USER' : 'AI'}}
                            </p>
                            <div class="rounded-lg px-4 py-2 prose-sm prose-dark {{$chatEntry['role'] === 'user' ? 'bg-blue-100 text-blue-900' : 'bg-green-100 text-green-900'}}">
                                {!! \Illuminate\Mail\Markdown::parse($chatEntry['content']) !!}
                            </div>
                        </div>
                    @endforeach
                    @if($streaming)
                        <div class="flex items-start justify-start my-2">
                            <p class="w-16 text-left pl-2">
                                AI
                            </p>
                            <div class="rounded-lg px-4 py-2 prose-sm prose-dark bg-green-100 text-green-900" wire:stream="generatedText">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form wire:submit="validatePrompt">
        <label for="prompt" class="block text-sm font-medium leading-5 text-gray-500">Prompt</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <textarea id="prompt" wire:model="prompt" class="text-gray-700 block w-full sm:text-sm sm:leading-5" placeholder="Enter a prompt"></textarea>
            @error('prompt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                <button type="submit" wire:target="generateText" wire:loading.attr="disabled"
                        wire:loading.class="bg-blue-700 cursor-wait"
                        wire:loading.class.remove="bg-blue-600 hover:bg-blue-500"
                        class="inline-flex items-center justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">
                <svg wire:target="generateText" wire:loading class="w-4 h-4 mr-2 stroke-current text-blue-100"
                     viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#fff">
                    <g fill="none" fill-rule="evenodd" stroke-width="2">
                        <circle cx="22" cy="22" r="1">
                            <animate attributeName="r"
                                     begin="0s" dur="1.8s"
                                     values="1; 20"
                                     calcMode="spline"
                                     keyTimes="0; 1"
                                     keySplines="0.165, 0.84, 0.44, 1"
                                     repeatCount="indefinite"/>
                            <animate attributeName="stroke-opacity"
                                     begin="0s" dur="1.8s"
                                     values="1; 0"
                                     calcMode="spline"
                                     keyTimes="0; 1"
                                     keySplines="0.3, 0.61, 0.355, 1"
                                     repeatCount="indefinite"/>
                        </circle>
                        <circle cx="22" cy="22" r="1">
                            <animate attributeName="r"
                                     begin="-0.9s" dur="1.8s"
                                     values="1; 20"
                                     calcMode="spline"
                                     keyTimes="0; 1"
                                     keySplines="0.165, 0.84, 0.44, 1"
                                     repeatCount="indefinite"/>
                            <animate attributeName="stroke-opacity"
                                     begin="-0.9s" dur="1.8s"
                                     values="1; 0"
                                     calcMode="spline"
                                     keyTimes="0; 1"
                                     keySplines="0.3, 0.61, 0.355, 1"
                                     repeatCount="indefinite"/>
                        </circle>
                    </g>
                </svg>
                  Generate Text
              </button>
            </span>
        </div>
    </form>
<p class="text-white">{{ $streaming }}</p>

</div>
