<?php
try {
    // Database connection
    require_once("conn.php");
    // SQL query to get return and not-return counts
    $sql = "SELECT
            COUNT(CASE WHEN is_returned = 1 THEN 1 END) AS returned_books,
            COUNT(CASE WHEN is_returned = 0 THEN 1 END) AS not_returned_books
        FROM books_borrowed";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get data
    $returned_books = $row['returned_books'];
    $not_returned_books = $row['not_returned_books'];

    // Calculate total and percentages
    $total_books = $returned_books + $not_returned_books;
    $returned_percentage = ($returned_books / $total_books) * 100;
    $not_returned_percentage = ($not_returned_books / $total_books) * 100;
} catch (Exception $e) {
    echo $e->getMessage();
}

?>

<!-- HTML for displaying the chart -->
<canvas id="returnRateChart"></canvas>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
    var ctx = document.getElementById('returnRateChart').getContext('2d');
    var returnRateChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Returned Books(%)', 'Not Returned Books(%)'],
            datasets: [{
                label: 'Return Rate',
                data: [<?php echo $returned_percentage; ?>, <?php echo $not_returned_percentage; ?>],
                backgroundColor: ['#5a189a', '#f0f0f0'],
                borderColor: ['#ffffff', '#ffffff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    backgroundColor: '#5a189a'
                }
            }
        }
    });
</script>