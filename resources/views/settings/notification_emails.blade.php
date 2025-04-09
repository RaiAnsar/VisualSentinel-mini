<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Notification Emails') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage email addresses for receiving notifications
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <button type="button" onclick="openAddEmailModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Email
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Session Status -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-900/30 dark:border-green-600 dark:text-green-400" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-900/30 dark:border-red-600 dark:text-red-400" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Master Emails -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Master Email Addresses</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        These email addresses will receive notifications for all websites.
                    </p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($masterEmails as $email)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $email->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $email->name ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($email->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <button type="button" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3" onclick="openEditEmailModal({{ $email->id }}, '{{ addslashes($email->email) }}', '{{ addslashes($email->name) }}', '{{ $email->type }}', {{ $email->website_id ?? 'null' }}, {{ $email->is_active ? 'true' : 'false' }})">
                                                Edit
                                            </button>
                                            
                                            <form method="POST" action="{{ route('settings.notification_emails.destroy', $email) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this email?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No master emails added yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Website-Specific Emails -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Website-Specific Email Addresses</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        These email addresses will only receive notifications for specific websites.
                    </p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Website</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($websiteEmails as $email)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $email->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $email->name ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $email->website->name ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($email->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <button type="button" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3" onclick="openEditEmailModal({{ $email->id }}, '{{ addslashes($email->email) }}', '{{ addslashes($email->name) }}', '{{ $email->type }}', {{ $email->website_id ?? 'null' }}, {{ $email->is_active ? 'true' : 'false' }})">
                                                Edit
                                            </button>
                                            
                                            <form method="POST" action="{{ route('settings.notification_emails.destroy', $email) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this email?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No website-specific emails added yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Email Modal -->
    <div id="addEmailModal" class="fixed inset-0 hidden overflow-y-auto bg-gray-800 bg-opacity-75 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add Notification Email</h3>
                    <button type="button" onclick="closeAddEmailModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="addEmailForm" method="POST" action="{{ route('settings.notification_emails.store') }}">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                    </div>
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name (Optional)')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" />
                    </div>
                    
                    <!-- Email Type -->
                    <div class="mb-4">
                        <x-input-label for="type" :value="__('Email Type')" />
                        <select id="type" name="type" onchange="toggleWebsiteSelect()" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                            <option value="master">Master Email (All Websites)</option>
                            <option value="website_specific">Website-Specific Email</option>
                        </select>
                    </div>
                    
                    <!-- Website Select -->
                    <div id="websiteSelectDiv" class="mb-4 hidden">
                        <x-input-label for="website_id" :value="__('Website')" />
                        <select id="website_id" name="website_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                            <option value="">Select a website</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}">{{ $website->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Notes -->
                    <div class="mb-4">
                        <x-input-label for="notes" :value="__('Notes (Optional)')" />
                        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300"></textarea>
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="closeAddEmailModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 mr-2">
                            Cancel
                        </button>
                        <x-primary-button>
                            {{ __('Add Email') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Email Modal -->
    <div id="editEmailModal" class="fixed inset-0 hidden overflow-y-auto bg-gray-800 bg-opacity-75 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Notification Email</h3>
                    <button type="button" onclick="closeEditEmailModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="editEmailForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="edit_email" :value="__('Email Address')" />
                        <x-text-input id="edit_email" class="block mt-1 w-full" type="email" name="email" required />
                    </div>
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <x-input-label for="edit_name" :value="__('Name (Optional)')" />
                        <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name" />
                    </div>
                    
                    <!-- Email Type -->
                    <div class="mb-4">
                        <x-input-label for="edit_type" :value="__('Email Type')" />
                        <select id="edit_type" name="type" onchange="toggleEditWebsiteSelect()" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                            <option value="master">Master Email (All Websites)</option>
                            <option value="website_specific">Website-Specific Email</option>
                        </select>
                    </div>
                    
                    <!-- Website Select -->
                    <div id="editWebsiteSelectDiv" class="mb-4 hidden">
                        <x-input-label for="edit_website_id" :value="__('Website')" />
                        <select id="edit_website_id" name="website_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                            <option value="">Select a website</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}">{{ $website->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Active Status -->
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input id="edit_is_active" type="checkbox" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                        </label>
                    </div>
                    
                    <!-- Notes -->
                    <div class="mb-4">
                        <x-input-label for="edit_notes" :value="__('Notes (Optional)')" />
                        <textarea id="edit_notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300"></textarea>
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="closeEditEmailModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 mr-2">
                            Cancel
                        </button>
                        <x-primary-button>
                            {{ __('Update Email') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function toggleWebsiteSelect() {
            const typeSelect = document.getElementById('type');
            const websiteSelectDiv = document.getElementById('websiteSelectDiv');
            const websiteSelect = document.getElementById('website_id');
            
            if (typeSelect.value === 'website_specific') {
                websiteSelectDiv.classList.remove('hidden');
                websiteSelect.setAttribute('required', 'required');
            } else {
                websiteSelectDiv.classList.add('hidden');
                websiteSelect.removeAttribute('required');
            }
        }
        
        function toggleEditWebsiteSelect() {
            const typeSelect = document.getElementById('edit_type');
            const websiteSelectDiv = document.getElementById('editWebsiteSelectDiv');
            const websiteSelect = document.getElementById('edit_website_id');
            
            if (typeSelect.value === 'website_specific') {
                websiteSelectDiv.classList.remove('hidden');
                websiteSelect.setAttribute('required', 'required');
            } else {
                websiteSelectDiv.classList.add('hidden');
                websiteSelect.removeAttribute('required');
            }
        }
        
        function openAddEmailModal() {
            document.getElementById('addEmailModal').classList.remove('hidden');
        }
        
        function closeAddEmailModal() {
            document.getElementById('addEmailModal').classList.add('hidden');
            document.getElementById('addEmailForm').reset();
        }
        
        function openEditEmailModal(id, email, name, type, websiteId, isActive) {
            const form = document.getElementById('editEmailForm');
            form.action = "{{ url('settings/notification-emails') }}/" + id;
            
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_name').value = name || '';
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_is_active').checked = isActive;
            
            if (type === 'website_specific') {
                document.getElementById('editWebsiteSelectDiv').classList.remove('hidden');
                document.getElementById('edit_website_id').value = websiteId;
            } else {
                document.getElementById('editWebsiteSelectDiv').classList.add('hidden');
            }
            
            document.getElementById('editEmailModal').classList.remove('hidden');
        }
        
        function closeEditEmailModal() {
            document.getElementById('editEmailModal').classList.add('hidden');
        }
    </script>
</x-app-layout> 