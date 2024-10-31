<?php
require_once "include/header.php";
require_once "include/database-connection.php";

$first_month = $_POST['first_month'] ?? null;
$second_month = $_POST['second_month'] ?? null;

$first_month_expense = $second_month_expense = 0;

// Fetch expenses for the first month
if ($first_month) {
    $first_month_expense_query = "SELECT SUM(price) AS total FROM expenses WHERE MONTH(date) = $first_month AND email = '$_SESSION[email]'";
    $result_first = mysqli_query($conn, $first_month_expense_query);
    $row_first = mysqli_fetch_assoc($result_first);
    $first_month_expense = $row_first['total'] ?? 0;
}

// Fetch expenses for the second month
if ($second_month) {
    $second_month_expense_query = "SELECT SUM(price) AS total FROM expenses WHERE MONTH(date) = $second_month AND email = '$_SESSION[email]'";
    $result_second = mysqli_query($conn, $second_month_expense_query);
    $row_second = mysqli_fetch_assoc($result_second);
    $second_month_expense = $row_second['total'] ?? 0;
}

// Calculate difference
$difference = $first_month_expense - $second_month_expense;

// Prepare pie chart data
$pieLabels = json_encode(["Expenses for " . date("F", mktime(0, 0, 0, $first_month, 1)), "Expenses for " . date("F", mktime(0, 0, 0, $second_month, 1))]);
$pieValues = json_encode([$first_month_expense, $second_month_expense]);
?>

<div class="container mt-4">
    <h1>Comparison of Expenses</h1>

    <form action="compare.php" method="POST">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_month">Select First Month:</label>
                <select name="first_month" id="first_month" class="form-control">
                    <option value="">Select Month</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="second_month">Select Second Month:</label>
                <select name="second_month" id="second_month" class="form-control">
                    <option value="">Select Month</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Compare</button>
    </form>

    <?php if ($first_month && $second_month): ?>
        <h3 class="mt-4">Results:</h3>
        <div class="card mb-4" style="border: 2px solid #007bff; background-color: #e9f7fe;">
            <div class="card-header" style="background-color: #007bff; color: white; text-align: center;">
                <h5 class="card-title">Comparison Summary</h5>
            </div>
            <div class="card-body text-center">
                <p style="font-size: 1.5rem; font-weight: bold;">Expenses for <strong><?php echo date("F", mktime(0, 0, 0, $first_month, 1)); ?></strong>: <span style="color: #28a745; font-size: 1.5rem;">₹<?php echo number_format($first_month_expense, 2); ?></span></p>
                <p style="font-size: 1.5rem; font-weight: bold;">Expenses for <strong><?php echo date("F", mktime(0, 0, 0, $second_month, 1)); ?></strong>: <span style="color: #dc3545; font-size: 1.5rem;">₹<?php echo number_format($second_month_expense, 2); ?></span></p>
                <p style="font-size: 1.8rem; font-weight: bold; margin-top: 20px;">Difference in Expenses: 
                    <span style="<?php echo ($difference < 0) ? 'color: #dc3545;' : 'color: #28a745;'; ?>">
                        ₹<?php echo number_format($difference, 2); ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Bar Chart -->
                <canvas id="barChart" width="600" height="400"></canvas>
            </div>
            <div class="col-md-6">
                <!-- Pie Chart -->
                <canvas id="pieChart" width="600" height="400"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Bar Chart
            const barCtx = document.getElementById('barChart').getContext('2d');
            const barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo date("F", mktime(0, 0, 0, $first_month, 1)); ?>', '<?php echo date("F", mktime(0, 0, 0, $second_month, 1)); ?>'],
                    datasets: [{
                        label: 'Expenses',
                        data: [<?php echo $first_month_expense; ?>, <?php echo $second_month_expense; ?>],
                        backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Pie Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: <?php echo $pieLabels; ?>,
                    datasets: [{
                        label: 'Expenses',
                        data: <?php echo $pieValues; ?>,
                        backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Expenses Comparison'
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>

<?php
require_once "include/footer.php";
?>
