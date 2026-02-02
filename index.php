<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/dashboard.php');
    } else {
        redirect('student/dashboard.php');
    }
}

$pageTitle = 'Login';
require_once 'includes/header.php';
?>

<div class="flex-grow flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="zoom-in" data-aos-duration="600">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-8 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="bg-white/20 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm border border-white/20 shadow-inner">
                    <i class="fa-solid fa-book-open text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Welcome Back</h1>
                <p class="text-primary-100 mt-2 text-sm font-light">Sign in to access the library portal</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-8 pt-6">
            <?php echo getFlash(); ?>

            <form action="auth/auth_process.php" method="POST" class="space-y-5">
                <input type="hidden" name="action" value="login">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Email or Roll No</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400 group-focus-within:text-primary-500 transition-colors"></i>
                        </div>
                        <input type="text" name="identifier" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 sm:text-sm" placeholder="e.g. 2023001 or admin@library.com" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-primary-500 transition-colors"></i>
                        </div>
                        <input type="password" name="password" id="loginPassword" class="block w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 sm:text-sm" placeholder="••••••••" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fa-solid fa-eye text-gray-400 cursor-pointer hover:text-gray-600 transition-colors" id="toggleLoginPassword"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 rounded accent-primary-600">
                        <span class="ml-2">Remember me</span>
                    </label>
                    <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">Forgot password?</a>
                </div>

                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                    Sign In
                    <i class="fa-solid fa-arrow-right ml-2 mt-0.5"></i>
                </button>
            </form>

            <div class="mt-8 relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Don't have an account?</span>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="auth/register.php" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">Create Student Account</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleLoginPassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('loginPassword');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });
</script>

<?php require_once 'includes/footer.php'; ?>
