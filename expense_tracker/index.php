<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expance - Daily Expense Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-blue-600">Expance</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <a href="expenses.php" class="text-gray-700 hover:text-blue-600">Expenses</a>
                    <a href="budget.php" class="text-gray-700 hover:text-blue-600">Budget</a>
                    <a href="reports.php" class="text-gray-700 hover:text-blue-600">Reports</a>
                    <a href="profile.php" class="text-gray-700 hover:text-blue-600">Profile</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Track Your Daily Expenses with Ease</h1>
                <p class="text-xl mb-8">Take control of your finances with our smart expense tracking solution</p>
                <a href="register.php" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-blue-50 transition duration-300">Get Started</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">Key Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Expense Tracking -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Expense Tracking</h3>
                <p class="text-gray-600">Log and monitor your daily spending with our intuitive interface.</p>
            </div>

            <!-- Receipt Management -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Receipt Management</h3>
                <p class="text-gray-600">Save and organize your receipts effortlessly with our smart system.</p>
            </div>

            <!-- Budget Planner -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Budget Planner</h3>
                <p class="text-gray-600">Set budgets and control your finances with our comprehensive planning tools.</p>
            </div>

            <!-- Smart Reports -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Smart Reports</h3>
                <p class="text-gray-600">Get valuable insights into your spending habits with detailed analytics.</p>
            </div>

            <!-- User-Friendly Interface -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">User-Friendly Interface</h3>
                <p class="text-gray-600">Simple, fast, and intuitive design for the best user experience.</p>
            </div>

            <!-- Additional Features -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">More Features</h3>
                <p class="text-gray-600">Export data, multiple currencies, and smart notifications.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-xl font-semibold mb-4">Expance</h4>
                    <p class="text-gray-400">Your personal expense tracking solution.</p>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Features</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Expance. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html> 