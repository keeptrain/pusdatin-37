<div>
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <x-letters.detail-layout :letterId="$letterId">
        <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
            
            <div class="text-xs text-gray-500">
                Title of letter
            </div>
        </div>

        <!-- Chat Area -->
        <div class="p-4 overflow-y-auto bg-gray-50 space-y-6">
            <!-- First message - Left side -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full bg-teal-400 flex items-center justify-center text-white shrink-0">
                    <span class="text-sm">P</span>
                </div>
                <div class="flex-1 space-y-1">
                    <div class="text-xs text-gray-500">
                        Pete Martell to Kate Walker
                    </div>
                    <div class="bg-gray-100 p-3 rounded-lg relative inline-block max-w-xs chat-bubble-left">
                        <p class="text-sm text-gray-800">
                            I do not think you could find a mammoth. They have long been extinct.
                        </p>
                    </div>
                    <div class="text-xs text-gray-500">
                        12:09 AM / 2017-04-13
                    </div>
                </div>
            </div>

            <!-- Reply - Right side -->
            <div class="flex justify-end space-x-2">
                <div class="flex-1 flex flex-col items-end space-y-1">
                    <div class="text-xs text-gray-500">
                        Misha Kazancev to Pete Martell
                    </div>
                    <div class="bg-green-200 p-3 rounded-lg relative inline-block max-w-xs chat-bubble-right">
                        <p class="text-sm text-gray-800">
                            I think she can.
                        </p>
                    </div>
                    <div class="flex items-center text-xs text-gray-500">
                        <span>12:09 AM / 2017-04-13</span>
                        <span class="ml-2 flex items-center">
                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                            </svg>
                            Delivered
                        </span>
                        <div class="ml-1 w-5 h-5 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">
                            <img src="/api/placeholder/20/20" alt="User" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second message - Left side -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full bg-teal-400 flex items-center justify-center text-white shrink-0">
                    <span class="text-sm">P</span>
                </div>
                <div class="flex-1 space-y-1">
                    <div class="text-xs text-gray-500">
                        Pete Martell to Kate Walker
                    </div>
                    <div class="bg-gray-100 p-3 rounded-lg relative inline-block max-w-xs chat-bubble-left">
                        <p class="text-sm text-gray-800">
                            I do not think you could find a mammoth. They have long been extinct.
                        </p>
                    </div>
                    <div class="text-xs text-gray-500">
                        12:09 AM / 2017-04-13
                    </div>
                </div>
            </div>

            <!-- Second reply - Right side -->
            <div class="flex justify-end space-x-2">
                <div class="flex-1 flex flex-col items-end space-y-1">
                    <div class="text-xs text-gray-500">
                        Misha Kazancev to Pete Martell
                    </div>
                    <div class="bg-green-200 p-3 rounded-lg relative inline-block max-w-xs chat-bubble-right">
                        <p class="text-sm text-gray-800">
                            I think she can.
                        </p>
                    </div>
                    <div class="flex items-center text-xs text-gray-500">
                        <span>12:09 AM / 2017-04-13</span>
                        <span class="ml-2 flex items-center">
                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                            </svg>
                            Delivered
                        </span>
                      
                    </div>
                </div>
            </div>
        </div>

        <!-- Input area (optional) -->
        <div class="bg-white border-t">
            <div class="flex items-center border  px-3 py-2">
                <input type="text" placeholder="Type a message..." class="flex-1 outline-none text-sm">
                <button class="ml-2 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </div>
        </div
    </x-letters.detail-layout>
</div>
