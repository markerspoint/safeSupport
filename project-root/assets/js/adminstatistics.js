document.addEventListener("DOMContentLoaded", function () {
    // Line Chart: Appointments this Week
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    const appointmentsChart = new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: statisticsData.weekDays.map(date => {
                // Format date as Mon, Tue, etc.
                const options = { weekday: 'short' };
                return new Date(date).toLocaleDateString('en-US', options);
            }),
            datasets: [{
                label: 'Appointments This Week',
                data: statisticsData.weekCounts,
                borderColor: '#3e95cd',
                backgroundColor: 'rgba(62, 149, 205, 0.2)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#3e95cd',
                pointBorderColor: '#3e95cd',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    title: {
                        display: true,
                        text: 'Bookings'
                    }
                }
            }
        }
    });

    // Bar Chart: Booking Statuses
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Accepted', 'Rejected'],
            datasets: [{
                label: '',
                data: [
                    statisticsData.pendingAppointments,
                    statisticsData.completedAppointments,
                    statisticsData.rejectedAppointments
                ],
                backgroundColor: [
                    '#f0ad4e', // yellow-orange for pending
                    '#5cb85c', // green for accepted
                    '#d9534f'  // red for rejected
                ],
                borderColor: '#ccc',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // Make it horizontal if you want: 'y'
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});


