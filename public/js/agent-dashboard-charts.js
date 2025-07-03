document.addEventListener('DOMContentLoaded', function () {
    // Leads by Month Chart
    var leadsByMonthLabels = window.leadsByMonthLabels || [];
    var leadsByMonthData = window.leadsByMonthData || [];
    var leadsByMonthCtx = document.getElementById('leadsByMonthChart').getContext('2d');
    new Chart(leadsByMonthCtx, {
        type: 'bar',
        data: {
            labels: leadsByMonthLabels,
            datasets: [{
                label: 'Leads',
                data: leadsByMonthData,
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#e5e7eb' } }
            }
        }
    });
    // Leads by Status Chart
    var leadsByStatusLabels = window.leadsByStatusLabels || [];
    var leadsByStatusData = window.leadsByStatusData || [];
    var leadsByStatusCtx = document.getElementById('leadsByStatusChart').getContext('2d');
    new Chart(leadsByStatusCtx, {
        type: 'doughnut',
        data: {
            labels: leadsByStatusLabels,
            datasets: [{
                data: leadsByStatusData,
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)', // approved
                    'rgba(234, 179, 8, 0.7)',  // pending
                    'rgba(239, 68, 68, 0.7)'   // rejected
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
