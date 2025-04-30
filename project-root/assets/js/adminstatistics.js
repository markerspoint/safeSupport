document.addEventListener('DOMContentLoaded', function() {
    console.log('Statistics Data:', statisticsData);
    
    const appointmentsCtx = document.getElementById('appointmentsChart');
    const usersCtx = document.getElementById('usersChart');
    
    console.log('Chart contexts:', appointmentsCtx, usersCtx);
    
    // Appointments Chart
    new Chart(appointmentsCtx.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Pending', 'Completed'],
            datasets: [{
                data: [statisticsData.pendingAppointments, statisticsData.completedAppointments],
                backgroundColor: ['#ffd700', '#90EE90']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Appointment Status Distribution'
                }
            }
        }
    });

    // Users Chart
    new Chart(usersCtx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Students', 'Counselors'],
            datasets: [{
                label: 'Number of Users',
                data: [statisticsData.totalStudents, statisticsData.totalCounselors],
                backgroundColor: ['#e3b766', '#4a90e2']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'User Distribution'
                }
            }
        }
    });
});