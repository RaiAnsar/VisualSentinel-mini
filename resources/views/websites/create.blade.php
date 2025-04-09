<x-layouts.app>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <a href="{{ route('websites.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Add Website</h1>
            </div>
            
            <!-- Form -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <form action="{{ route('websites.store') }}" method="POST">
                    @csrf
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Website Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website URL -->
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website URL</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 bg-gray-50 text-gray-500 dark:text-gray-400 text-sm">
                                        https://
                                    </span>
                                    <input type="text" name="url" id="url" value="{{ old('url') }}" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800" placeholder="example.com" required>
                                </div>
                                @error('url')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The full URL of the website you want to monitor.</p>
                            </div>

                            <!-- Check Interval -->
                            <div>
                                <label for="check_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval (minutes)</label>
                                <input type="number" name="check_interval" id="check_interval" value="{{ old('check_interval', 30) }}" min="5" max="1440" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md" required>
                                @error('check_interval')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Monitoring Options -->
                            <div>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monitoring Options</span>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="check_ssl" name="check_ssl" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="check_ssl" class="font-medium text-gray-700 dark:text-gray-300">Check SSL Certificate</label>
                                            <p class="text-gray-500 dark:text-gray-400">Monitor SSL certificate expiration and validity.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="check_content" name="check_content" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="check_content" class="font-medium text-gray-700 dark:text-gray-300">Check Content Changes</label>
                                            <p class="text-gray-500 dark:text-gray-400">Detect changes in website content.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="take_screenshots" name="take_screenshots" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="take_screenshots" class="font-medium text-gray-700 dark:text-gray-300">Take Screenshots</label>
                                            <p class="text-gray-500 dark:text-gray-400">Capture screenshots for visual comparison.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
                                <select name="tags[]" id="tags" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 bg-white dark:bg-gray-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hold down Ctrl (Windows) or Command (Mac) to select multiple tags.</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Website
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app> 