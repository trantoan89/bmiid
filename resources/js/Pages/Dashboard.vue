<template>
  <user-layout>
    <template #header>
      <h2>
        Dashboard
      </h2>
    </template>
      
    <div class="w-80 md:w-full max-w-4xl mx-auto">
      <div class="mt-7 w-full">
        <div class="w-full"  id="bar">
          <bar-chart :chart-data="datacollection" :options="options"></bar-chart>
          <p class="text-center">{{ barLabel }}(year: {{ chart_form.years }})</p>
          <p class="text-center font-bold" id="report"></p>
          <p class="text-center font-semibold text-sm" id="dateReport"></p>
        </div>
        <div class="flex justify-center">
          <select id="disease_id" v-on:change="diseaseChange" class="px-7 py-2" v-model="chart_form.disease_id">
            <option v-for="d in disease_names" :key="d.id" :value="d.id">{{ d.disease_name }}</option>
          </select>
          <select id="year" v-on:change="diseaseChange" class="px-7 py-2 ml-2" v-model="chart_form.years">
            <option v-for="y in case_years" :key="y" :value="y">{{ y }}</option>
          </select>
          <select id="month" class="px-7 py-2 ml-2" v-model="chart_form.month" title="Select <month> for generate report for whole year">
            <option value="0" style="color: rgba(0, 0, 0, 0.6)" selected>&lt;month&gt;</option>
            <option v-for="(m, k) in datacollection.labels" :key="k" :value="k+1">{{ m }}</option>
          </select>
          <button v-on:click="getPDF" class="px-7 py-2 ml-2 border border-blue-700 text-blue-700 cursor pointer hover:border-blue-500 hover:text-blue-500"> Generate Report </button>
        </div>
      </div>
      <div class="mt-16 w-full">
        <div class="block md:flex">
          <div class="w-full md:w-1/2">
            <div id="pie">
              <pie-chart :chart-data="dataChart" :options="chartOptions"></pie-chart>
              <p class="text-center">{{ pieLabel }}</p>
              <p class="text-center font-bold" id="report2"></p>
              <p class="text-center font-semibold text-sm" id="dateReport2"></p>
            </div>
            
            <p class="mt-5 text-base uppercase text-center">total cases in Baguio</p>
            <div class="flex justify-center">
              <select v-on:change="diseaseChangePie" class="px-7 py-2" v-model="chart_form2.disease_id">
                <option v-for="d in disease_names" :key="d.id" :value="d.id">{{ d.disease_name }}</option>
              </select>
            </div>
          </div>
          <div  class="mt-5 md:mt-0 w-full md:w-1/2 p-7 bg-white shadow-md rounded">
            <p class="text-base font-semibold my-2">Disease Cases</p>
            <div class="w-full h-full overflow-y-auto overflow-x-auto" style="max-height:450px;">
              <table class="w-full">
                <thead>
                  <th class="border">Disease</th>
                  <th class="border">Total</th>
                </thead>
                <tbody>
                  <template v-for="(totalD, index) in totalDisease">
                    <tr :key="index">
                      <td class="p-3 w-52 border">
                        <p class="w-52 text-sm p-0 m-0 uppercase">{{ totalD.disease_name }}</p>
                      </td>
                      <td class="p-3 w-32 border">
                        {{ (totalD.recovered + totalD.deceased + (totalD.active - totalD.deceased - totalD.recovered)) }}
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </user-layout>
</template>

<script>
  import UserLayout from '@/Layouts/UserLayout'
  import BarChart from '../BarChart.js';
  import PieChart from '../PieChart.js';
  import Exporter from "../export.js";
  import moment from 'moment';

  export default {
    components: {
      UserLayout,
      BarChart,
      PieChart
    },
    data(){
      return {
        report: false,
        pieLabel: '',
        barLabel: '',
        totalDisease: [],
        case_years: [],
        disease_names: [],
        chart_form: {
          disease_id: '',
          years: case_min_year,
          month: 1
        },
        chart_form2: {
          disease_id: '',
          years: '2021',
        },
        datacollection: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          datasets: [
            {
              label: 'Data One',
              backgroundColor: 'skyblue',
              pointBackgroundColor: 'white',
              borderWidth: 1,
              pointBorderColor: '#249EBF',
              data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }
          ]
			  },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              },
              gridLines: {
                display: true
              }
            }],
            xAxes: [{
              ticks: {
                beginAtZero: true
              },
              gridLines: {
                display: false
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
              label: function(tooltipItems, data) {
                return '' + tooltipItems.yLabel + ' cases';
              }
            }
          },
          responsive: true,
          maintainAspectRatio: false,
          height: 300
        },
        chartOptions: {
          hoverBorderWidth: 20
        },
        dataChart: {
          hoverBackgroundColor: "red",
          hoverBorderWidth: 10,
          labels: ["Active Cases", "Deceased", "Recovered"],
          datasets: [
            {
              label: "Covid-19 Pie Chart",
              backgroundColor: ["red", "orange", "green", "transparent"],
              data: [0, 0, 0]
            }
          ]
        },
      }
    },
    created(){
      document.title = "BMIID(Dashboard)";
      
        const year = (new Date()).getFullYear();
        const length = year - case_min_year + 1;
        this.case_years = Array.from({ length }, (_, i) => case_min_year + i);

      this.allDisease();
      this.totalDiseaseCase();
    },
    methods: {
        getPDF: function () {
            var year = this.chart_form.years;
            var month = this.chart_form.month;
            if (month < 10) month = '0' + month;
            var disease_id = this.chart_form.disease_id;
            
            var date = year + '-' + month + '-00';
            
            window.open('casesview?date=' + date + '&dsid=' + disease_id);
        },
      createdPDF: function(){
        let bar = document.getElementById("bar");
        let pie = document.getElementById("pie");

        let ele = document.getElementById('report');
        ele.innerHTML += 'Generated report by: '+ this.$page.props.user.first_name +' '+ this.$page.props.user.last_name +'';

        // let ele2 = document.getElementById('report2');
        // ele2.innerHTML += 'Generated report by: '+ this.$page.props.user.first_name +' '+ this.$page.props.user.last_name +'';
        
        let ele3 = document.getElementById('dateReport');
        let ele4 = document.getElementById('dateReport2');
        var date = new Date();
        var a = moment(date).format('dddd, MMMM Do YYYY');
        ele3.innerHTML += a;
        ele4.innerHTML += a;

        const exp = new Exporter([bar, pie]);
        exp.export_pdf().then((pdf) => pdf.save("report.pdf"));
      },
      diseaseChangePie: function (){
        for (var i = 0; i < this.disease_names.length; i++) {
          if(this.chart_form2.disease_id === this.disease_names[i].id){
            this.pieLabel = this.disease_names[i].disease_name;
          }
        };

        axios.post('api/pie_graph', this.chart_form2).then(response => {
          this.dataChart = {
            hoverBackgroundColor: "red",
            hoverBorderWidth: 10,
            labels: ["Active Cases("+(response.data.active - (response.data.deceased + response.data.recovered))+")", "Deceased("+response.data.deceased+")", "Recovered("+response.data.recovered+")"],
            datasets: [
              {
                label: "Covid-19 Pie Chart",
                backgroundColor: ["red", "orange", "green", "transparent"],
                data: [response.data.active - (response.data.deceased + response.data.recovered), response.data.deceased, response.data.recovered]
              }
            ]
          };
        });
      },
      totalDiseaseCase: function(){
        axios.get('api/total_disease_case').then(response => {
          this.totalDisease = response.data[0];
        })
      },
      diseaseChange: function(){
        for (var i = 0; i < this.disease_names.length; i++) {
          if(this.chart_form.disease_id === this.disease_names[i].id){
            this.pieLabel = this.disease_names[i].disease_name;
            this.barLabel = this.disease_names[i].disease_name;
          }
        };
        
        axios.post('api/bar_graph', this.chart_form).then(response => {
          this.datacollection = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
              {
                label: 'Data One',
                backgroundColor: 'skyblue',
                pointBackgroundColor: 'white',
                borderWidth: 1,
                pointBorderColor: '#249EBF',
                data: [response.data[1], response.data[2], response.data[3], response.data[4], response.data[5], response.data[6], response.data[7], response.data[8], response.data[9], response.data[10], response.data[11], response.data[12]]
              }
            ]
          };
        })
      },
      allDisease: function(){
        axios.get('/api/alldisease').then((response) => {
          this.disease_names = response.data.data;
          this.chart_form.disease_id = response.data.data[0].id;
          this.chart_form2.disease_id = response.data.data[0].id;
          this.barLabel = response.data.data[0].disease_name;
          this.pieLabel = response.data.data[0].disease_name;
          this.chart_form.years = case_min_year;
        }).then(() => {
          this.diseaseChange();
          this.diseaseChangePie();
        }).catch((error) =>{
          console.log(error);
        })
      }
    }
  }
</script>
