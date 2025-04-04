<x-layouts.guest>
    <div class="flex-1 flex flex-col">
        <!-- Hero Section -->
        <div class="flex-1 relative isolate bg-white dark:bg-gray-900 overflow-hidden">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24 sm:py-32">
                <div class="grid lg:grid-cols-2 gap-x-8 gap-y-16 items-center">
                    <!-- Left Content -->
                    <div class="z-10">
                        <div class="inline-flex items-center rounded-full px-4 py-1 text-sm leading-6 text-gray-600 dark:text-gray-400 ring-1 ring-gray-900/10 dark:ring-gray-400/20">
                            Launching Soon
                        </div>
                        <h1 class="mt-10 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                            Visual Website<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB]">Monitoring</span><br>
                            Made Simple
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                            Monitor your websites for visual changes, performance issues, and SSL certificate expiration. Get instant notifications when something goes wrong.
                        </p>
                        <div class="mt-10 flex items-center gap-x-6">
                            <a href="{{ route('register') }}" class="rounded-md bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB] px-4 py-2.5 text-base font-semibold text-white shadow-sm hover:from-[#6935D9] hover:to-[#4162DE] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#7B42F6]">
                                Start Monitoring
                            </a>
                            <a href="#features" class="text-base font-semibold leading-6 text-gray-900 dark:text-white hover:text-[#7B42F6]">
                                Learn more <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                    <!-- Right Image -->
                    <div class="relative h-full">
                        <div class="absolute inset-0 flex items-center">
                            <img 
                                src="{{ Vite::asset('public/images/logo.svg') }}" 
                                alt="Visual Sentinel" 
                                class="absolute right-[-120px] top-[-30px] w-[700px] h-[700px] max-w-none"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="bg-gray-100 dark:bg-gray-900 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-[#7B42F6]">Comprehensive Monitoring</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Everything you need to monitor your websites</p>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        Get instant alerts when your websites experience visual changes, performance issues, or security concerns.
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-20 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                        <!-- Visual Change Detection -->
                        <div class="flex flex-col h-full">
                            <div class="relative flex-1 p-8 bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-sm ring-1 ring-gray-900/10 dark:ring-white/10 transition-all duration-300 hover:-translate-y-1">
                                <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB]">
                                        <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                            <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    Visual Change Detection
                                    <span class="inline-flex items-center rounded-full bg-purple-50 dark:bg-purple-400/10 px-2 py-1 text-xs font-medium text-purple-700 dark:text-purple-400 ring-1 ring-inset ring-purple-700/10 dark:ring-purple-400/30">New</span>
                                </dt>
                                <dd class="mt-4 text-base leading-7 text-gray-600 dark:text-gray-400">
                                    Detect visual changes on your websites with pixel-perfect accuracy. Get notified when layouts break or content changes unexpectedly.
                                </dd>
                            </div>
                        </div>

                        <!-- SSL Certificate Monitoring -->
                        <div class="flex flex-col h-full">
                            <div class="relative flex-1 p-8 bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-sm ring-1 ring-gray-900/10 dark:ring-white/10 transition-all duration-300 hover:-translate-y-1">
                                <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB]">
                                        <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    SSL Certificate Monitoring
                                </dt>
                                <dd class="mt-4 text-base leading-7 text-gray-600 dark:text-gray-400">
                                    Never miss an SSL certificate expiration. Get early warnings and ensure your websites remain secure and trusted.
                                </dd>
                            </div>
                        </div>

                        <!-- CDN-Aware Monitoring -->
                        <div class="flex flex-col h-full">
                            <div class="relative flex-1 p-8 bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-sm ring-1 ring-gray-900/10 dark:ring-white/10 transition-all duration-300 hover:-translate-y-1">
                                <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB]">
                                        <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm3.75-2.75a.75.75 0 001.5 0V9.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0l-3.25 3.5a.75.75 0 101.1 1.02l1.95-2.1v4.59z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    CDN-Aware Monitoring
                                </dt>
                                <dd class="mt-4 text-base leading-7 text-gray-600 dark:text-gray-400">
                                    Monitor your websites from multiple locations worldwide. Detect CDN issues and ensure consistent performance globally.
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-white dark:bg-gray-900 py-24 sm:py-32">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Start monitoring your websites today</h2>
                <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-600 dark:text-gray-400">
                    Join thousands of developers who trust Visual Sentinel to monitor their websites. Get started with our free tier today.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}" class="rounded-md bg-gradient-to-r from-[#7B42F6] to-[#4A6FFB] px-4 py-2.5 text-base font-semibold text-white shadow-sm hover:from-[#6935D9] hover:to-[#4162DE]">
                        Get started for free
                    </a>
                    <a href="{{ route('login') }}" class="text-base font-semibold leading-6 text-gray-900 dark:text-white hover:text-[#7B42F6]">
                        Sign in <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
