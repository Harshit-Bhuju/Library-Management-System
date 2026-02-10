<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
requireLogin();

$pageTitle = 'About Us';
require_once 'includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <div class="relative rounded-3xl overflow-hidden mb-10 shadow-2xl mx-4 lg:mx-8 mt-6">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900 to-indigo-900"></div>
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1507842217153-e21f40d0ecce?auto=format&fit=crop&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>

            <div class="relative z-10 p-10 lg:p-16 text-center">
                <span class="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-semibold tracking-wider uppercase mb-4" data-aos="fade-down">
                    Est. 2012
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight drop-shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    Empowering Minds,<br>Shaping Futures
                </h1>
                <p class="text-primary-100 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                    Welcome to the NIST Banepa Library. A state-of-the-art digital and physical knowledge hub designed to fuel your academic journey.
                </p>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        </div>

        <div class="px-4 lg:px-8 pb-12">
            <!-- Mission & Vision Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group" data-aos="fade-right">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-rocket text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Mission</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        To provide comprehensive access to information resources and services that support the teaching, learning, and research needs of the college community. We strive to foster an environment conducive to intellectual growth and discovery.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group" data-aos="fade-left">
                    <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-lightbulb text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Vision</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        To be a premier academic library that transforms lives through knowledge, innovation, and community engagement. We aim to be the heart of the campus, connecting people with ideas and each other.
                    </p>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="mb-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-gradient-to-br from-white to-gray-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 dark:border-gray-700" data-aos="zoom-in" data-aos-delay="0">
                        <h4 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 mb-2">5k+</h4>
                        <span class="text-gray-500 font-medium">Physical Books</span>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-white to-gray-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 dark:border-gray-700" data-aos="zoom-in" data-aos-delay="100">
                        <h4 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 mb-2">10k+</h4>
                        <span class="text-gray-500 font-medium">E-Resources</span>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-white to-gray-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 dark:border-gray-700" data-aos="zoom-in" data-aos-delay="200">
                        <h4 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600 mb-2">24/7</h4>
                        <span class="text-gray-500 font-medium">Digital Access</span>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-white to-gray-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 dark:border-gray-700" data-aos="zoom-in" data-aos-delay="300">
                        <h4 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600 mb-2">500+</h4>
                        <span class="text-gray-500 font-medium">Daily Readers</span>
                    </div>
                </div>
            </div>

            <!-- Location & Contact Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8" data-aos="fade-up">

                <!-- Info Panel -->
                <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Visit Us</h3>

                        <div class="space-y-8">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-location-dot text-primary-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Location</h4>
                                    <p class="text-gray-500 mt-1">NIST Banepa<br>Banepa, Kavrepalanchok</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clock text-emerald-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Opening Hours</h4>
                                    <ul class="text-gray-500 mt-1 space-y-1">
                                        <li class="flex justify-between w-full max-w-[200px]"><span>Sun - Fri:</span> <span class="font-medium text-gray-700 dark:text-gray-300">10:00 AM - 5:00 PM</span></li>
                                        <li class="flex justify-between w-full max-w-[200px]"><span>Saturday:</span> <span class="font-medium text-red-500">Closed</span></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-envelope text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Contact</h4>
                                    <p class="text-gray-500 mt-1">library@nistbanepa.edu.np<br>+977-11-660000</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                        <button class="w-full btn btn-primary py-4 rounded-xl shadow-lg shadow-primary-500/30">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Get Directions
                        </button>
                    </div>
                </div>

                <!-- Map Panel -->
                <div class="lg:col-span-2 relative h-[500px] lg:h-auto rounded-3xl overflow-hidden shadow-lg border-4 border-white dark:border-slate-700">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3534.530114092251!2d85.5143918752512!3d27.639065976221776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb0f3258d48223%3A0x208ffddded7ef03c!2sNIST%20Banepa!5e0!3m2!1sen!2snp!4v1770482068392!5m2!1sen!2snp"
                        class="absolute inset-0 w-full h-full"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="text-center text-gray-400 text-sm py-4">
                &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
            </div>

        </div>
    </main>
</div>
<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }

    .animate-blob {
        animation: blob 7s infinite;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>

<?php require_once 'includes/footer.php'; ?>