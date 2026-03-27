<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | System Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <div class="hidden lg:flex w-1/2 bg-blue-900 justify-center items-center relative overflow-hidden">
            <div class="z-10 text-center px-12">
                <h1 class="text-5-xl font-bold text-white mb-4">Admin Management</h1>
                <p class="text-blue-200 text-lg">Secure access to the system's core functionalities and administrative tools.</p>
            </div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-800 rounded-full opacity-50"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-700 rounded-full opacity-50"></div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-500 mt-2">Please enter your credentials to login</p>
                </div>

                <form action="login_process.php" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin Username</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="username" required 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150" 
                                placeholder="e.g. admin_vin">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" required 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                            SIGN IN
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-widest">Authorized Access Only</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>