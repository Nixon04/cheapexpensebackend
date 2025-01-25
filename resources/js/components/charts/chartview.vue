<template>
    <div>
      <canvas id="barChart" data-height="400" class="chartjs"></canvas>
      
    </div>
  </template>
  
  <script>
  import { Chart, registerables } from 'chart.js';
  Chart.register(...registerables);
  
  export default {
    data() {
      return {
        colors: {
          primary: '#836AF9',
          warning: '#ffe800',
          success: '#2D2942',
          body: '#EDF1F4',
          info: '#2B9AFF',
          lightBlue: '#84D0FF',
        },
        config: {
          isDarkStyle: false,
          colors: {
            dark: {
              cardColor: '#333',
              headingColor: '#FFF',
              textMuted: '#AAA',
              bodyColor: '#222',
            },
            light: {
              cardColor: '#FFF',
              headingColor: '#333',
              textMuted: '#555',
              bodyColor: '#EEE',
            },
          },
        },
      };
    },
    mounted() {
      this.initCharts();
    },
    methods: {
      initCharts() {
        const cardColor = this.config.isDarkStyle
          ? this.config.colors.dark.cardColor
          : this.config.colors.light.cardColor;
        const headingColor = this.config.isDarkStyle
          ? this.config.colors.dark.headingColor
          : this.config.colors.light.headingColor;
        const textMutedColor = this.config.isDarkStyle
          ? this.config.colors.dark.textMuted
          : this.config.colors.light.textMuted;
        const borderColor = this.config.isDarkStyle
          ? this.config.colors.dark.bodyColor
          : this.config.colors.light.bodyColor;
  
        this.initBarChart(cardColor, headingColor, textMutedColor, borderColor);
        this.initHorizontalBarChart(cardColor, headingColor, textMutedColor, borderColor);
        this.initLineChart(cardColor, headingColor, textMutedColor, borderColor);
        // Add other chart initializations here
      },
      initBarChart(cardColor, headingColor, textMutedColor, borderColor) {
        const ctx = document.getElementById('barChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",'Aug','sep','oct','Nov','Dec'],
            datasets: [{
              data: [275, 90, 190, 205, 125, 85, 55, 12,218,129,9,393],
              backgroundColor: this.colors.success,
              borderColor: 'transparent',
              maxBarThickness: 15,
              borderRadius: { topRight: 15, topLeft: 15 }
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              tooltip: {
                backgroundColor: cardColor,
                titleColor: headingColor,
                bodyColor: textMutedColor,
                borderWidth: 1,
                borderColor: borderColor
              },
              legend: { display: false }
            },
            scales: {
              x: {
                grid: { color: borderColor },
                ticks: { color: textMutedColor }
              },
              y: {
                grid: { color: borderColor },
                ticks: { color: textMutedColor, stepSize: 100 }
              }
            }
          }
        });
      },
      initHorizontalBarChart(cardColor, headingColor, textMutedColor, borderColor) {
        const ctx = document.getElementById('horizontalBarChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ["MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN"],
            datasets: [{
              data: [710, 350, 470, 580, 230, 460, 120],
              backgroundColor: this.colors.info,
              borderColor: 'transparent',
              maxBarThickness: 15
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              tooltip: {
                backgroundColor: cardColor,
                titleColor: headingColor,
                bodyColor: textMutedColor,
                borderWidth: 1,
                borderColor: borderColor
              },
              legend: { display: false }
            },
            scales: {
              x: {
                grid: { color: borderColor },
                ticks: { color: textMutedColor }
              },
              y: {
                grid: { color: borderColor, display: false },
                ticks: { color: textMutedColor }
              }
            }
          }
        });
      },
      initLineChart(cardColor, headingColor, textMutedColor, borderColor) {
        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: Array.from({ length: 15 }, (_, i) => i * 10),
            datasets: [
              {
                label: 'Europe',
                data: [80, 150, 180, 270, 210, 160, 160, 202, 265, 210, 270, 255, 290, 360, 375],
                borderColor: this.colors.warning,
                fill: false
              },
              {
                label: 'Asia',
                data: [80, 125, 105, 130, 215, 195, 140, 160, 230, 300, 220, 170, 210, 200, 280],
                borderColor: this.colors.primary,
                fill: false
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              tooltip: {
                backgroundColor: cardColor,
                titleColor: headingColor,
                bodyColor: textMutedColor,
                borderWidth: 1,
                borderColor: borderColor
              },
              legend: { position: 'top', labels: { color: textMutedColor } }
            },
            scales: {
              x: { grid: { color: borderColor }, ticks: { color: textMutedColor } },
              y: { grid: { color: borderColor }, ticks: { color: textMutedColor } }
            }
          }
        });
      }
    }
  };
  </script>
  
  <style scoped>
  .chartjs {
    width: 100%;
    height: 250px;
    margin: 0 auto;
  }
  </style>
