document.addEventListener('DOMContentLoaded', function() {
    console.log('Statistics Data:', statisticsData);

    const appointmentsCtx = document.getElementById('appointmentsChart');
    const usersCtx = document.getElementById('usersChart');

    console.log('Chart contexts:', appointmentsCtx, usersCtx);

    // Appointments Doughnut Chart
    new Chart(appointmentsCtx.getContext('2d'), {
        type: 'doughnut',  // Change to doughnut chart for better visibility
        data: {
            labels: ['Pending', 'Accepted', 'Rejected'],
            datasets: [{
                data: [
                    statisticsData.pendingAppointments, 
                    statisticsData.completedAppointments, 
                    statisticsData.rejectedAppointments
                ],
                backgroundColor: ['#ffd700', '#90EE90', '#f44336'],
                borderColor: ['#e5e500', '#66cc66', '#ff6666'],  // Borders for better visibility
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Makes the chart responsive
            cutoutPercentage: 50, // Makes the doughnut more defined
            plugins: {
                title: {
                    display: true,
                    text: 'Appointment Status Distribution',
                    font: {
                        size: 18
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const dataset = tooltipItem.dataset;
                            const value = dataset.data[tooltipItem.dataIndex];
                            return dataset.labels[tooltipItem.dataIndex] + ': ' + value;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        },
                        padding: 15
                    }
                }
            }
        }
    });

    // Users Bar Chart
    new Chart(usersCtx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Students', 'Counselors'],
            datasets: [{
                label: 'Number of Users',
                data: [statisticsData.totalStudents, statisticsData.totalCounselors],
                backgroundColor: ['#e3b766', '#4a90e2'],
                borderColor: ['#d39e00', '#357ab6'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'User Distribution',
                    font: {
                        size: 18
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 14
                        }
                    }
                },
                y: {
                    ticks: {
                        beginAtZero: true,
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
});
