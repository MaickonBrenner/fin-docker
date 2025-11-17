const ctx = document.getElementById('graficoMensal').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels, // vindo do PHP
    datasets: [{
      label: 'Gastos (R$)',
      data: valores, // vindo do PHP
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
