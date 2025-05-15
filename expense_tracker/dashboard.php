<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's expenses for the current month
$current_month = date('Y-m');
$stmt = $conn->prepare("
    SELECT SUM(amount) as total_amount, COUNT(*) as total_expenses 
    FROM expenses 
    WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?
");
$stmt->execute([$_SESSION['user_id'], $current_month]);
$monthly_stats = $stmt->fetch();

// Get recent expenses
$stmt = $conn->prepare("
    SELECT * FROM expenses 
    WHERE user_id = ? 
    ORDER BY date DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent_expenses = $stmt->fetchAll();

// Get expenses by category
$stmt = $conn->prepare("
    SELECT category, SUM(amount) as total 
    FROM expenses 
    WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?
    GROUP BY category
");
$stmt->execute([$_SESSION['user_id'], $current_month]);
$category_stats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Expance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-2xl font-bold text-blue-600">Expance</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Expenses</h3>
                        <p class="text-2xl font-semibold">$<?php echo number_format($monthly_stats['total_amount'] ?? 0, 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Number of Transactions</h3>
                        <p class="text-2xl font-semibold"><?php echo $monthly_stats['total_expenses'] ?? 0; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Average per Day</h3>
                        <p class="text-2xl font-semibold">$<?php echo number_format(($monthly_stats['total_amount'] ?? 0) / date('t'), 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Expenses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Category Distribution Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Expenses by Category</h2>
                <canvas id="categoryChart"></canvas>
            </div>

            <!-- Recent Expenses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Expenses</h2>
                <div class="space-y-4">
                    <?php foreach ($recent_expenses as $expense): ?>
                    <div class="flex items-center justify-between border-b pb-4">
                        <div>
                            <h3 class="font-medium"><?php echo htmlspecialchars($expense['description']); ?></h3>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($expense['category']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$<?php echo number_format($expense['amount'], 2); ?></p>
                            <p class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($expense['date'])); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="add_expense.php" class="bg-blue-600 text-white rounded-lg p-4 text-center hover:bg-blue-700 transition duration-300">
                <i class="fas fa-plus-circle text-2xl mb-2"></i>
                <p>Add Expense</p>
            </a>
            <a href="budget.php" class="bg-green-600 text-white rounded-lg p-4 text-center hover:bg-green-700 transition duration-300">
                <i class="fas fa-chart-pie text-2xl mb-2"></i>
                <p>Set Budget</p>
            </a>
            <a href="reports.php" class="bg-purple-600 text-white rounded-lg p-4 text-center hover:bg-purple-700 transition duration-300">
                <i class="fas fa-chart-line text-2xl mb-2"></i>
                <p>View Reports</p>
            </a>
            <a href="profile.php" class="bg-gray-600 text-white rounded-lg p-4 text-center hover:bg-gray-700 transition duration-300">
                <i class="fas fa-user text-2xl mb-2"></i>
                <p>Profile</p>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-8">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="text-center text-gray-400">
                <p>&copy; 2024 Expance. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Category Distribution Chart
        const categoryData = <?php echo json_encode($category_stats); ?>;
        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category),
                datasets: [{
                    data: categoryData.map(item => item.total),
                    backgroundColor: [
                        '#3B82F6',
                        '#10B981',
                        '#8B5CF6',
                        '#EF4444',
                        '#F59E0B'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html> 