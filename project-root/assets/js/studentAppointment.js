// document.addEventListener('DOMContentLoaded', function () {
//     const calendarEl = document.getElementById('calendar');
//     if (!calendarEl) return;

//     const calendar = new FullCalendar.Calendar(calendarEl, {
//         initialView: 'dayGridMonth',
//         height: 500,
//         events: window.appointments || []
//     });

//     calendar.render();
// });

// document.addEventListener('DOMContentLoaded', function () {
//     const calendarEl = document.getElementById('calendar');

//     // Convert appointmentCounts to background events with colors
//     const backgroundEvents = Object.entries(window.appointmentCounts || {}).map(([date, count]) => {
//         let color = '';
//         if (count >= 3) {
//             color = 'red';
//         } else if (count > 0) {
//             color = 'yellow';
//         } else {
//             color = 'green'; // This case won't occur because only dates with counts are present here
//         }
//         return {
//             start: date,
//             allDay: true,
//             display: 'background',
//             backgroundColor: color,
//             borderColor: color
//         };
//     });

//     // Initialize FullCalendar
//     const calendar = new FullCalendar.Calendar(calendarEl, {
//         initialView: 'dayGridMonth',
//         height: 600,
//         selectable: false,
//         events: backgroundEvents,

//         // Optional: show tooltip or some visual on hover for the colored days
//         dayCellDidMount: function (info) {
//             const dateStr = info.date.toISOString().slice(0, 10);
//             const count = window.appointmentCounts[dateStr] || 0;

//             if (count >= 3) {
//                 info.el.title = `High load: ${count} appointments`;
//             } else if (count > 0) {
//                 info.el.title = `${count} appointment(s)`;
//             } else {
//                 info.el.title = 'No appointments';
//             }
//         }
//     });

//     calendar.render();
// });


// calendar funtions
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const appointmentCounts = window.appointmentCounts || {};

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: function (fetchInfo, successCallback, failureCallback) {
            const backgroundEvents = [];

            const start = new Date(fetchInfo.start);
            const end = new Date(fetchInfo.end);

            for (let d = new Date(start); d < end; d.setDate(d.getDate() + 1)) {
                const dateStr = d.toISOString().split('T')[0];
                const count = appointmentCounts[dateStr] || 0;
                let color;

                if (count >= 3) {
                    color = 'red';
                } else if (count > 0) {
                    color = 'yellow';
                } else {
                    color = 'green';
                }

                backgroundEvents.push({
                    start: dateStr,
                    end: dateStr,
                    display: 'background',
                    backgroundColor: color
                });
            }

            successCallback(backgroundEvents);
        }
    });

    calendar.render();
});




