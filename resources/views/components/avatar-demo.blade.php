<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Chat Avatar Component Demo</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- User with Profile Image -->
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-4">User with Profile Image</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">XS:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'John Doe', 'member' => ['profile_image' => 'jil-logo.png'], 'online' => true, 'type' => 'user']" 
                                        size="xs" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">SM:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'John Doe', 'member' => ['profile_image' => 'jil-logo.png'], 'online' => true, 'type' => 'user']" 
                                        size="sm" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">MD:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'John Doe', 'member' => ['profile_image' => 'jil-logo.png'], 'online' => true, 'type' => 'user']" 
                                        size="md" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">LG:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'John Doe', 'member' => ['profile_image' => 'jil-logo.png'], 'online' => true, 'type' => 'user']" 
                                        size="lg" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">XL:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'John Doe', 'member' => ['profile_image' => 'jil-logo.png'], 'online' => true, 'type' => 'user']" 
                                        size="xl" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- User with Initials (No Profile Image) -->
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-4">User with Initials (No Profile Image)</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">XS:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Jane Smith', 'member' => null, 'online' => false, 'type' => 'user']" 
                                        size="xs" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">SM:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Jane Smith', 'member' => null, 'online' => false, 'type' => 'user']" 
                                        size="sm" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">MD:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Jane Smith', 'member' => null, 'online' => false, 'type' => 'user']" 
                                        size="md" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">LG:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Jane Smith', 'member' => null, 'online' => false, 'type' => 'user']" 
                                        size="lg" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">XL:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Jane Smith', 'member' => null, 'online' => false, 'type' => 'user']" 
                                        size="xl" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Member without User Account -->
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-4">Member without User Account</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">XS:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Bob Wilson', 'member' => null, 'online' => false, 'type' => 'member']" 
                                        size="xs" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">SM:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Bob Wilson', 'member' => null, 'online' => false, 'type' => 'member']" 
                                        size="sm" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">MD:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Bob Wilson', 'member' => null, 'online' => false, 'type' => 'member']" 
                                        size="md" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">LG:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Bob Wilson', 'member' => null, 'online' => false, 'type' => 'member']" 
                                        size="lg" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">XL:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Bob Wilson', 'member' => null, 'online' => false, 'type' => 'member']" 
                                        size="xl" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Selected State -->
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-4">Selected State (Active Chat)</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">MD:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Alice Johnson', 'member' => null, 'online' => true, 'type' => 'user']" 
                                        size="md" 
                                        :show-online-status="true"
                                        :selected="true"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">LG:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Alice Johnson', 'member' => null, 'online' => true, 'type' => 'user']" 
                                        size="lg" 
                                        :show-online-status="true"
                                        :selected="true"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Without Online Status -->
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-4">Without Online Status</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">MD:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Charlie Brown', 'member' => null, 'online' => true, 'type' => 'user']" 
                                        size="md" 
                                        :show-online-status="false"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">LG:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'Charlie Brown', 'member' => null, 'online' => true, 'type' => 'user']" 
                                        size="lg" 
                                        :show-online-status="false"
                                        :selected="false"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Online vs Offline Comparison -->
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-4">Online vs Offline</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">Online:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'David Lee', 'member' => null, 'online' => true, 'type' => 'user']" 
                                        size="md" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">Offline:</span>
                                    <x-chat-avatar 
                                        :user="['name' => 'David Lee', 'member' => null, 'online' => false, 'type' => 'user']" 
                                        size="md" 
                                        :show-online-status="true"
                                        :selected="false"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold mb-2">Features:</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                            <li>✅ Online/offline indicators with colored dots (green for online, gray for offline)</li>
                            <li>✅ Profile image support with fallback to colored initials</li>
                            <li>✅ Member type badges for members without user accounts</li>
                            <li>✅ Selected state highlighting with soft gold/amber colors</li>
                            <li>✅ Multiple sizes: xs, sm, md, lg, xl</li>
                            <li>✅ Tailwind CSS styling with glassmorphism effects</li>
                            <li>✅ Dark mode support</li>
                            <li>✅ High contrast mode support</li>
                            <li>✅ Reduced motion support for accessibility</li>
                            <li>✅ ARIA labels and semantic HTML</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

