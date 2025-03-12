<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager API</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-semibold text-indigo-600">Task Manager API</span>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <a href="https://laravel.com/docs"
                            class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Documentation</a>
                        <a href="{{ url('/api/documentation') }}"
                            class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">API
                            Docs</a>
                        <a href="https://github.com"
                            class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">GitHub</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block">Task Manager REST API</span>
                    </h1>
                    <p
                        class="mt-3 max-w-md mx-auto text-base text-indigo-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                        A modern RESTful API for managing tasks, tags, and user authentication.
                    </p>
                    <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                        <div class="rounded-md shadow">
                            <a href="{{ url('/api/documentation') }}"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                API Documentation
                            </a>
                        </div>
                        <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="https://github.com"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-700 hover:bg-indigo-800 md:py-4 md:text-lg md:px-10">
                                GitHub Repository
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Features</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Everything you need to manage tasks
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        A comprehensive API solution with authentication, task management, tagging, and filtering.
                    </p>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1 -->
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-5 h-32 flex items-center justify-center bg-indigo-500">
                                <span class="text-white font-bold text-xl">Authentication</span>
                            </div>
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Secure Authentication</h3>
                                <p class="mt-1 text-gray-500">Complete user authentication with registration, login, and
                                    email verification using Laravel Sanctum.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-5 h-32 flex items-center justify-center bg-indigo-500">
                                <span class="text-white font-bold text-xl">Task Management</span>
                            </div>
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Task Management</h3>
                                <p class="mt-1 text-gray-500">Create, read, update, and delete tasks with support for
                                    priorities, statuses, due dates, and descriptions.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-5 h-32 flex items-center justify-center bg-indigo-500">
                                <span class="text-white font-bold text-xl">Tagging System</span>
                            </div>
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Tagging System</h3>
                                <p class="mt-1 text-gray-500">Categorize tasks with a flexible tagging system, including
                                    color-coding for visual organization.</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-5 h-32 flex items-center justify-center bg-indigo-500">
                                <span class="text-white font-bold text-xl">Filtering</span>
                            </div>
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Advanced Filtering</h3>
                                <p class="mt-1 text-gray-500">Filter tasks by status, priority, due date, tags, and
                                    search for specific content within tasks.</p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-5 h-32 flex items-center justify-center bg-indigo-500">
                                <span class="text-white font-bold text-xl">Documentation</span>
                            </div>
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Comprehensive Docs</h3>
                                <p class="mt-1 text-gray-500">API documentation with Swagger UI, making it easy to
                                    understand and test endpoints.</p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-5 h-32 flex items-center justify-center bg-indigo-500">
                                <span class="text-white font-bold text-xl">RESTful Design</span>
                            </div>
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">RESTful Design</h3>
                                <p class="mt-1 text-gray-500">Built with RESTful principles, providing consistent and
                                    predictable API patterns.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Endpoints Section -->
        <div class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">API Reference</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Explore the endpoints
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        Get started with our API quickly with these key endpoints.
                    </p>
                </div>

                <div class="mt-12 bg-white shadow overflow-hidden rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Method</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Endpoint</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">POST</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">/api/auth/register
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Register a new user
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">POST</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">/api/auth/login</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Authenticate user and
                                        get token</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">GET</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">/api/auth/user</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Get authenticated user
                                        information</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">POST</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">/api/auth/logout</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Logout and invalidate
                                        token</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ url('/api/documentation') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        View Complete API Documentation
                    </a>
                </div>
            </div>
        </div>

        <!-- Getting Started Section -->
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Getting Started</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Quick Installation Guide
                    </p>
                </div>

                <div class="mt-12 max-w-4xl mx-auto bg-gray-50 shadow overflow-hidden rounded-lg">
                    <div class="px-6 py-8">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">1. Clone the repository</h3>
                                <div class="mt-2 bg-gray-800 rounded-md p-4">
                                    <pre class="text-sm text-gray-300">git clone https://github.com/your-username/task-manager-api.git
cd task-manager-api</pre>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">2. Install dependencies</h3>
                                <div class="mt-2 bg-gray-800 rounded-md p-4">
                                    <pre class="text-sm text-gray-300">composer install</pre>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">3. Configure environment</h3>
                                <div class="mt-2 bg-gray-800 rounded-md p-4">
                                    <pre class="text-sm text-gray-300">cp .env.example .env
php artisan key:generate</pre>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">4. Run migrations and seeders</h3>
                                <div class="mt-2 bg-gray-800 rounded-md p-4">
                                    <pre class="text-sm text-gray-300">php artisan migrate --seed</pre>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">5. Start the development server</h3>
                                <div class="mt-2 bg-gray-800 rounded-md p-4">
                                    <pre class="text-sm text-gray-300">php artisan serve</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">
                        &copy; {{ date('Y') }} Task Manager API. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
