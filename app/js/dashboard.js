const ctx = document.getElementById('graficoMensal').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['01', '05', '10', '15', '20', '25', '30'],
    datasets: [{
      label: 'Gastos (R$)',
      data: [120, 300, 150, 400, 200, 180, 250],
      backgroundColor: '#03dac6'
    }]
  },
  options: {
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { color: '#e0e0e0' },
        grid: { color: '#333' }
      },
      x: {
        ticks: { color: '#e0e0e0' },
        grid: { color: '#333' }
      }
    }
  }
});
