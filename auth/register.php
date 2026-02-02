<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirectToDashboard();
}

$pageTitle = 'Register';
require_once '../includes/header.php';
?>

<div class="flex-grow flex items-center justify-center p-4 py-10 min-h-screen bg-gradient-to-br from-primary-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <div class="w-full max-w-xl bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up" data-aos-duration="600">

        <!-- Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Student Registration</h2>
                <p class="text-primary-100 text-sm mt-1">Join the library system</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <?php echo getFlash(); ?>

            <form action="auth_process.php" method="POST" class="space-y-5">
                <input type="hidden" name="action" value="register">
                <?php echo csrfInput(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Full Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Full Name *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="name"
                                class="block w-full pl-10 px-4 py-3 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm"
                                placeholder="John Doe"
                                required>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Email Address *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email"
                                class="block w-full pl-10 px-4 py-3 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm"
                                placeholder="student@example.com"
                                required>
                        </div>
                    </div>

                    <!-- Phone Number (Optional) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Phone Number <span class="text-gray-400">(Optional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-phone text-gray-400"></i>
                            </div>
                            <input type="tel" name="phone"
                                class="block w-full pl-10 px-4 py-3 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm"
                                placeholder="9800000000">
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Department *</label>
                        <div class="relative">
                            <select name="department"
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm appearance-none"
                                required>
                                <option value="">Select Department</option>
                                <option value="Physical Science">Physical Science</option>
                                <option value="Biology Science">Biology Science</option>
                                <option value="Business">Business</option>
                                <option value="Management">Management</option>
                                <option value="Hotel Management">Hotel Management</option>
                                <option value="Computer Science">Computer Science</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Class/Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Class *</label>
                        <div class="relative">
                            <select name="class"
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm appearance-none"
                                required>
                                <option value="">Select Class</option>
                                <option value="11">Class 11</option>
                                <option value="12">Class 12</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Password *</label>
                        <div class="relative">
                            <input type="password" name="password" id="regPassword"
                                class="block w-full px-4 py-3 pr-10 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm"
                                placeholder="Min. 6 characters"
                                required
                                minlength="6">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fa-solid fa-eye text-gray-400 cursor-pointer hover:text-gray-600 transition-colors" id="toggleRegPassword"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 ml-1">Confirm Password *</label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="regConfirmPassword"
                                class="block w-full px-4 py-3 pr-10 border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm"
                                placeholder="Repeat password"
                                required
                                minlength="6">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fa-solid fa-eye text-gray-400 cursor-pointer hover:text-gray-600 transition-colors" id="toggleRegConfirmPassword"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input type="checkbox" id="terms" required
                        class="w-4 h-4 mt-1 text-primary-600 border-gray-300 rounded focus:ring-primary-500 accent-primary-600">
                    <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        I agree to the <a href="#" class="text-primary-600 hover:underline">Terms of Service</a> and <a href="#" class="text-primary-600 hover:underline">Privacy Policy</a>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                        <i class="fa-solid fa-user-plus mr-2"></i>
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 dark:border-slate-700 pt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Already have an account?
                    <a href="../index.php" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">Login Here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Password toggle functionality
    ['regPassword', 'regConfirmPassword'].forEach((id, index) => {
        const toggleId = index === 0 ? 'toggleRegPassword' : 'toggleRegConfirmPassword';
        document.getElementById(toggleId).addEventListener('click', function() {
            const passwordInput = document.getElementById(id);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    });

    // Password match validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('regPassword').value;
        const confirmPassword = document.getElementById('regConfirmPassword').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>