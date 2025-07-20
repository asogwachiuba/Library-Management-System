<?php
require_once("conn.php");

try {
    // Query to retrieve borrowed and returned books statistics
    $query = "SELECT 
    DATE(date_borrowed) AS date, 
    COUNT(*) AS borrowed_count, -- Count all rows for books borrowed on the date
    COUNT(CASE WHEN is_returned = 1 THEN 1 END) AS returned_count -- Count rows where the book is returned
FROM books_borrowed
GROUP BY DATE(date_borrowed)
ORDER BY DATE(date_borrowed) ASC;
";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch data as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert data to JSON for the JavaScript code
    $jsonData = json_encode($data);
} catch (Exception $e) {
    die("Error fetching book statistics: " . $e->getMessage());
}
?>

<div class="chart-card">
    <div class="chart-card-header" style="background-color: #5a189a; border-color: #5a189a;">
        <h3 class="mb-0">Book Statistics</h3>
    </div>
    <div class="chart-card-body">
        <canvas id="bookStatisticsChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
    // Data retrieved from the PHP backend
    const data = <?= $jsonData ?>;

    // Parse data for the chart
    const labels = data.map(entry => entry.date); // Dates
    const borrowedCounts = data.map(entry => entry.borrowed_count); // Borrowed counts
    const returnedCounts = data.map(entry => entry.returned_count); // Returned counts

    // Initialize Chart.js
    const ctx1 = document.getElementById('bookStatisticsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Books Borrowed',
                    data: borrowedCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Books Returned',
                    data: returnedCounts,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Number of Books' } }
            }
        }
    });
</script>