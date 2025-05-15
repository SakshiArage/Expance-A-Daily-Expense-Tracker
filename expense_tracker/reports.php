<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get filter parameters
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$category = $_GET['category'] ?? '';

// Build query conditions
$conditions = ["user_id = ?"];
$params = [$_SESSION['user_id']];

if ($category) {
    $conditions[] = "category = ?";
    $params[] = $category;
}

$date_condition = "date BETWEEN ? AND ?";
$conditions[] = $date_condition;
$params[] = $start_date;
$params[] = $end_date;

$where_clause = implode(" AND ", $conditions);

// Get total expenses
$stmt = $conn->prepare("
    SELECT SUM(amount) as total_amount, COUNT(*) as total_expenses 
    FROM expenses 
    WHERE $where_clause
");
$stmt->execute($params);
$total_stats = $stmt->fetch();

// Get expenses by category
$stmt = $conn->prepare("
    SELECT category, SUM(amount) as total, COUNT(*) as count
    FROM expenses 
    WHERE $where_clause
    GROUP BY category
    ORDER BY total DESC
");
$stmt->execute($params);
$category_stats = $stmt->fetchAll();

// Get daily expenses for chart
$stmt = $conn->prepare("
    SELECT DATE(date) as date, SUM(amount) as total
    FROM expenses 
    WHERE $where_clause
    GROUP BY DATE(date)
    ORDER BY date
");
$stmt->execute($params);
$daily_stats = $stmt->fetchAll();

// Get top expenses
$stmt = $conn->prepare("
    SELECT * FROM expenses 
    WHERE $where_clause
    ORDER BY amount DESC 
    LIMIT 5
");
$stmt->execute($params);
$top_expenses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Reports - Expance</title>
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
                    <a href="dashboard.php" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Report Filters</h2>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        <option value="Groceries" <?php echo $category === 'Groceries' ? 'selected' : ''; ?>>Groceries</option>
                        <option value="Transportation" <?php echo $category === 'Transportation' ? 'selected' : ''; ?>>Transportation</option>
                        <option value="Utilities" <?php echo $category === 'Utilities' ? 'selected' : ''; ?>>Utilities</option>
                        <option value="Entertainment" <?php echo $category === 'Entertainment' ? 'selected' : ''; ?>>Entertainment</option>
                        <option value="Shopping" <?php echo $category === 'Shopping' ? 'selected' : ''; ?>>Shopping</option>
                        <option value="Healthcare" <?php echo $category === 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                        <option value="Education" <?php echo $category === 'Education' ? 'selected' : ''; ?>>Education</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Expenses</h3>
                        <p class="text-2xl font-semibold">$<?php echo number_format($total_stats['total_amount'] ?? 0, 2); ?></p>
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
                        <p class="text-2xl font-semibold"><?php echo $total_stats['total_expenses'] ?? 0; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Average per Day</h3>
                        <p class="text-2xl font-semibold">$<?php echo number_format(($total_stats['total_amount'] ?? 0) / max(1, (strtotime($end_date) - strtotime($start_date)) / (24 * 60 * 60)), 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Daily Expenses Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Daily Expenses</h2>
                <canvas id="dailyChart"></canvas>
            </div>

            <!-- Category Distribution -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Expenses by Category</h2>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Top Expenses -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Top Expenses</h2>
                <div class="space-y-4">
                    <?php foreach ($top_expenses as $expense): ?>
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
        // Daily Expenses Chart
        const dailyData = <?php echo json_encode($daily_stats); ?>;
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => new Date(item.date).toLocaleDateString()),
                datasets: [{
                    label: 'Daily Expenses',
                    data: dailyData.map(item => item.total),
                    borderColor: '#3B82F6',
                    tension: 0.1
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

        // Category Distribution Chart
        const categoryData = <?php echo json_encode($category_stats); ?>;
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
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
                        '#F59E0B',
                        '#EC4899',
                        '#6366F1'
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