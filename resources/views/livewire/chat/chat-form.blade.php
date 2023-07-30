<div>
    <div class="mb-4">
        <div class="mt-1 w-[calc(100%-50px)] md:w-[calc(100%-115px)]">
            <div class="py-4 md:px-6 px-4 text-gray-700 w-full sm:text-sm sm:leading-5 grid gap-3">
                <div class="min-h-[20px] grid gap-4">
                    <!-- Iterate over the chat log and display each entry. -->
                    @foreach ($chatLog as $chatEntry)
                        <div class="grid grid-cols-6 gap-4 my-2">
                            <p class="col-span-1 text-left pr-2 text-gray-200">
                                {{$chatEntry['role'] === 'user' ? 'User:' : 'AI:'}}
                            </p>
                            <div class="col-span-5 rounded-lg px-4 py-2 prose-sm prose-dark {{$chatEntry['role'] === 'user' ? 'bg-blue-100 text-blue-900' : 'bg-green-100 text-green-900'}}">
                                {!! \Illuminate\Mail\Markdown::parse($chatEntry['content']) !!}
                            </div>
                        </div>
                    @endforeach


                    <div x-data="{ streaming: @entangle('streaming'), initialDisplay: '' }"
                         x-init="initialDisplay = $el.style.display"
                         x-bind:style="streaming ? 'display: ' + initialDisplay : 'display: none'"
                         wire:loading=""
                         wire:target="generateText">
                        <div class="grid grid-cols-6 gap-4 my-2">
                            <p class="col-span-1 text-left pr-2 text-gray-200">
                                AI:
                            </p>
                            <div class="col-span-5 rounded-lg px-4 py-2 prose-sm prose-dark bg-green-100 text-green-900" wire:stream="generatedText">
                                <span class="transition-all animate-pulse">Generating text...</span>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
    <form wire:submit="validatePrompt">
        <label for="prompt" class="block text-sm font-medium leading-5 text-gray-500">Prompt</label>
        <div class="mt-1 relative rounded-md shadow-sm">
        <textarea id="prompt" wire:model="prompt" class="text-gray-700 block w-full sm:text-sm sm:leading-5 pr-16"
                  placeholder="How can I help?"></textarea>
            <button type="submit" wire:target="generateText" wire:loading.attr="disabled"
                    wire:loading.class="bg-blue-700 cursor-wait"
                    wire:loading.class.remove="bg-blue-600 hover:bg-blue-500"
                    class="absolute bottom-2 right-2 inline-flex items-center justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring-blue active:bg-blue-700 transition duration-150 ease-in-out">

                <!-- Show this SVG (3 pulsing dots) while loading -->
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

                <!-- Show this SVG (double chevron) when not loading -->
                <svg wire:loading.remove wire:target="generateText" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none" class="h-4 w-4 m-1 md:m-0 fill-current text-blue-100" stroke-width="2"><path d="M.5 1.163A1 1 0 0 1 1.97.28l12.868 6.837a1 1 0 0 1 0 1.766L1.969 15.72A1 1 0 0 1 .5 14.836V10.33a1 1 0 0 1 .816-.983L8.5 8 1.316 6.653A1 1 0 0 1 .5 5.67V1.163Z" fill="currentColor"></path></svg>
            </button>
            @error('prompt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </form>
</div>
