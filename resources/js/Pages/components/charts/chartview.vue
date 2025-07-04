<template>
  <div>
    <canvas id="barChart" data-height="1000" class="chartjs"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);
import axios from "axios";

export default {
  data() {
    return {
      chart: null, // Reference to the chart instance
      colors: {
        primary: "#836AF9",
        warning: "#ffe800",
        success: "#2D2942",
        body: "#EDF1F4",
        info: "#2B9AFF",
        lightBlue: "#84D0FF",
      },
      config: {
        isDarkStyle: false,
        colors: {
          dark: {
            cardColor: "#333",
            headingColor: "#FFF",
            textMuted: "#AAA",
            bodyColor: "#222",
          },
          light: {
            cardColor: "#FFF",
            headingColor: "#333",
            textMuted: "#555",
            bodyColor: "#EEE",
          },
        },
      },
    };
  },
  mounted() {
    this.fetchMonthlyData();
  },
  methods: {
    async fetchMonthlyData() {
  try {
    const response = await axios.get("/cheapx/auth/fetchrecord");
    console.log('Full response data:', response.data); // Log the full response to inspect its structure
    
    const data = response.data.returns; // Adjust based on API response structure
    console.log('Data before mapping:', data); // Log the data before mapping

    // Check if `item.value` is correct or if a different property should be used
    const formattedData = data.map((item) => item.total); // Adjust `value` based on data structure
    console.log('Formatted data:', formattedData); // Log the formatted data

    this.initBarChart(formattedData);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
},
    initBarChart(data) {
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

      const ctx = document.getElementById("barChart").getContext("2d");

      if (this.chart) {
        this.chart.destroy(); // Destroy existing chart if reinitializing
      }

      this.chart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
                 data: data, // Use default data if fetch fails
              backgroundColor: this.colors.success,
              borderColor: "transparent",
              maxBarThickness: 15,
              borderRadius: { topRight: 15, topLeft: 15 },
            },
          ],
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
              borderColor: borderColor,
            },
            legend: { display: false },
          },
          scales: {
            x: {
              grid: { color: borderColor },
              ticks: { color: textMutedColor },
            },
            y: {
              grid: { color: borderColor },
              ticks: { color: textMutedColor, stepSize: 100 },
            },
          },
        },
      });
    },
  },
};
</script>

<style scoped>
.chartjs {
  width: 100%;
  height: 350px;
  margin: 0 auto;
}
</style>
