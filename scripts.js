document.addEventListener('DOMContentLoaded', function() {
    function updateCharts() {
        // Actualizar los datos de los gráficos en tiempo real
        fetch('dashboard.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-tickets').innerText = data.total_tickets;
                document.getElementById('pending-tickets').innerText = data.pending_tickets;
                document.getElementById('in-process-tickets').innerText = data.in_process_tickets;
                document.getElementById('resolved-tickets').innerText = data.resolved_tickets;
                document.getElementById('cancelled-tickets').innerText = data.cancelled_tickets;
                document.getElementById('on-hold-tickets').innerText = data.on_hold_tickets;

                ticketChart.data.datasets[0].data = [
                    data.pending_tickets,
                    data.in_process_tickets,
                    data.resolved_tickets,
                    data.cancelled_tickets,
                    data.on_hold_tickets
                ];
                ticketChart