<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

$pageTitle = 'Register';
require_once '../includes/header.php';
?>

<div class="flex-grow flex items-center justify-center p-4 py-10">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up" data-aos-duration="600">
        
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Full Name</label>
                        <input type="text" name="name" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm" placeholder="John Doe" required>
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Roll No / Student ID</label>
                        <input type="text" name="roll_no" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm" placeholder="e.g. 2023005" required>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Email Address</label>
                        <input type="email" name="email" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm" placeholder="student@example.com" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Department</label>
                        <div class="relative">
                            <select name="department" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm appearance-none">
                                <option value="Physical Science">Physical Science</option>
                                <option value="Biology Science">Biology Science</option>
                                <option value="Business">Business</option>
                                <option value="Management">Management</option>
                                <option value="Hotel Management">Hotel Management</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Class</label>
                        <div class="relative">
                            <select name="year" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm appearance-none">
                                <option value="11">Class 11</option>
                                <option value="12">Class 12</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="regPassword" class="block w-full px-4 py-3 pr-10 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all sm:text-sm" placeholder="Create a strong password" required minlength="6">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fa-solid fa-eye text-gray-400 cursor-pointer hover:text-gray-600 transition-colors" id="toggleRegPassword"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 ml-1">Must be at least 6 characters long</p>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="../index.php" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">Login Here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleRegPassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('regPassword');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });
</script>

<?php require_once '../includes/footer.php'; ?>
