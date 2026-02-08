// Mobile menu toggle
window.toggleMobileMenu = function () {
    const menu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.mobile-menu-overlay');
    menu.classList.toggle('active');
    overlay.classList.toggle('active');
};

// Notification popup toggle
window.toggleNotificationPopup = function () {
    const popup = document.getElementById('notificationPopup');
    popup.classList.toggle('active');
};

// Close notification popup when clicking outside
document.addEventListener('click', function (e) {
    const popup = document.getElementById('notificationPopup');
    const notificationBtn = e.target.closest('button[onclick*="toggleNotificationPopup"]');

    if (!notificationBtn && popup && !popup.contains(e.target)) {
        popup.classList.remove('active');
    }
});

// Performance Chart
const ctx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['SO. I', 'SO. II', 'SO. III', 'SO. VI', 'SO. V'],
        datasets: [{
            label: 'Expected Target',
            data: [95, 92, 90, 88, 85],
            borderColor: 'rgba(168, 85, 247, 0.8)',
            backgroundColor: 'rgba(168, 85, 247, 0.3)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Actual Accomplishments',
            data: [0, 0, 0, 0, 0],
            borderColor: 'rgba(59, 130, 246, 0.8)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            borderDash: [5, 5]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: window.innerWidth < 640 ? 8 : 15,
                    font: {
                        size: window.innerWidth < 640 ? 10 : 12
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function (value) {
                        return value + '%';
                    },
                    font: {
                        size: window.innerWidth < 640 ? 10 : 12
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: window.innerWidth < 640 ? 9 : 11
                    }
                }
            }
        }
    }
});
