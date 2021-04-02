<template>
  <div>
    <navbar></navbar>
    <div class="flex container mx-auto justify-center pb-20">
      <div v-if="loading" class="mt-32 w-full">
        <p class="text-base flex w-full justify-center">
          Loading ...
        </p>
      </div>
      <div v-if="!loading" class="w-full mt-32">
        <div class="block w-9/12 md:w-11/12 mx-auto">
          <h1 class="text-4xl tracking-wide md:text-6xl">Baguio Map</h1>
          <p class="text-sm md:text-base">Check updates about the disease in barangays of baguio.</p>
        </div>
        <div class="mt-16 w-full shadowBox relative ">
          <gmap-map
            :center="{lat: 16.407093429075125, lng: 120.59766815726287}"
            :zoom="13"
            :streetViewControl="true"
            style="width: 100%; height: 500px; z-index:0;"
          >
            <gmap-polyline v-bind:path.sync="path" v-bind:options="{ strokeColor:'blue'}">
            </gmap-polyline>
            <gmap-marker 
              v-for="b in selectedBarangays"
              :key="b.id"
              :position="getPositions(b)"
              :clickable="true"
              :draggable="false"
              :title="b.barangay_name"
              :icon="{url:barangayMarker[b.id]}"
              v-on:click="handleMarkerClicked(b)"
            >
            </gmap-marker>
          </gmap-map>
          <div class="absolute top-0 right-10 p-5 z-10">
            <select v-on:change="changeDiseaseMap" v-model="disease_id" class="w-56">
              <option v-for="d in disease_names" :key="d.id" :value="d.id">{{ d.disease_name }}</option>
            </select>
          </div>
          <div class="absolute top-12 right-10 p-5 z-10">
            <select class="w-56" v-model="barangaySelect" v-on:change="barangaySelected">
              <option value="all" selected>All</option>
              <option v-for="b in barangays" :value="b.id" :key="b.id">{{ b.barangay_name }}</option>
            </select>
          </div>
          <div class="absolute top-24 right-5 p-5 z-10">
            <div class="flex p-1">
              <div class="h-3.5 w-3.5" style="background-color:red;"></div> &nbsp;
              <p class="text-sm text-white">High Risk</p>
            </div>
            <div class="flex p-1">
              <div class="h-3.5 w-3.5 orange" style="background-color:orange;"></div> &nbsp;
              <p class="text-sm text-white">Severe</p>
            </div>
            <div class="flex p-1">
              <div class="h-3.5 w-3.5" style="background-color:yellow;"></div> &nbsp;
              <p class="text-sm text-white">Low Risk</p>
            </div>
            <div class="flex p-1">
              <div class="h-3.5 w-3.5" style="background-color:green;"></div> &nbsp;
              <p class="text-sm text-white">Safe</p>
            </div>
          </div>
          <transition name="fade">
            <div v-if="show" class="absolute bottom-10 right-10 h-80 w-56 p-5 bg-white z-10 rounded opacity-80">
              <button @click="show=false" class="absolute top-2 right-2 text-sm cursor-pointer z-15"><i class="fas fa-times"></i></button>
              <h1 class="text-2xl">{{ mapSearch.barangay_name }}</h1>
              <p class="text-base pt-3"><b>Cases</b></p>
              <p class="text-sm"><b>Active :</b> {{ mapSearch.active }}</p>
              <p class="text-sm"><b>Deceased:</b> {{ mapSearch.deceased }}</p>
              <p class="text-sm"><b>Recovered:</b> {{ mapSearch.recovered }}</p>
              <p class="text-sm"><b>Total:</b> {{ mapSearch.total }}</p>
            </div>
          </transition>
        </div>
        <div class="mt-16 block w-9/12 md:w-11/12 mx-auto">
          <h1 class="text-3xl tracking-wide md:text-4xl">Disease Graph Illustration</h1>
          <p class="text-sm md:text-base">Check updates about the disease in barangays of baguio.</p>
          <div class="mt-11 block md:flex justify-center w-full">
            <div class="p-5">
              <div class="flex w-full md:w-96 relative">
                <div class="block">
                  <piechart :chart-data="dataChart" :options="chartOptions"></piechart>
                  <p v-if="totalCase == 0" class="flex flex-wrap content-center justify-center text-1xl absolute inset-2.5">No Case for this Disease.</p>
                </div>
                <div class="block">
                  <p class="text-sm mt-32">Active: <br>{{ pieActive }}</p>
                  <p class="text-sm mt-2">Deceased: <br>{{ pieDeceased }}</p>
                  <p class="text-sm mt-2">Recovered:<br> {{ pieRecovered }}</p>
                  <p class="text-sm mt-2">Total: <br>{{ pieTotal }}</p>
                </div>
              </div>
            </div>
            <form v-on:submit.prevent="chartForm">
              <div class="block p-7 ml-0 md:ml-32">
                <div class="block p-2">
                  <select class="w-56" v-model="chart_form.disease_id">
                    <option v-for="d in disease_names" :key="d.id" :value="d.id">{{ d.disease_name }}</option>
                  </select>
                </div>
                <div class="block p-2">
                  <select class="w-56" v-model="chart_form.barangay_id">
                    <option value="Baguio" selected>Baguio</option>
                    <option v-for="b in barangays" :value="b.id" :key="b.id">{{ b.barangay_name }}</option>
                  </select>
                </div>
                <!-- <div class="block p-2">
                  <select class="w-56" v-model="chart_form.month">
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                  </select>
                  <p v-if="chartValidations.monthErr" class="text-sm text-red-700 pl-1">{{ chartValidations.monthMsg }}</p>
                </div>
                <div class="block p-2">
                  <select class="w-56" v-model="chart_form.year">
                    <option value="2020">2020</option>
                    <option v-for="y in countYear" :key="y" :value="currYear + y">{{ currYear + y }}</option>
                  </select>
                </div> -->
                <div class="block p-2">
                  <button type="submit" class="bg-white w-56 border-2 py-2 border-blue-500 text-blue-500 hover:border-blue-700 hover:text-blue-700">
                    Generate Graph
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="mt-16 block w-9/12 md:w-11/12 mx-auto">
          <p class="text-sm md:text-base">This Map and Graph illustration is for Baguio City, Philippines area only. This doesn't involve other areas in Philippines or country, if you want to check Corona Virus(Covid-19) cases in other places you can visit: <a href="https://covid19.who.int/" target="_blank" class="text-blue-700">https://covid19.who.int/</a></p>
          <p class="text-sm md:text-base pt-5">If you think the information that are presented in Map or Graph is not accurate, or is a false information, please <inertia-link href="/contact"  class="text-blue-700">contact us</inertia-link>. We are happy to receive a message from you, Thank You and Take Care. </p>
        </div>
      </div>
    </div>
    <div v-if="!loading">
      <footers></footers>
    </div>
  </div>  
</template>

<style scoped>
  @import '../../css/home.css';
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBCaIysf1ZPoyGPtNavI_4PQAHh1ImSpcg"></script>
<script>
  import Navbar from '@/Includes/navbar';
  import Footer from '@/Includes/footer';
  import PieChart from '../PieChart.js';
  
  export default {
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
    },
    components: {
       'navbar': Navbar,
       'footers': Footer,
       'piechart': PieChart
    },
    data(){
      return {
        path: [
          { lng: 120.59744263, lat: 16.36701012  },
          { lng: 120.58338165, lat: 16.36879921  },
          { lng: 120.5723877, lat: 16.36986923  },
          { lng: 120.56912231, lat: 16.37018013  },
          { lng: 120.56411743, lat: 16.38690948  },
          { lng: 120.55635834, lat: 16.41456985  },
          { lng: 120.55599976, lat: 16.41580009  },
          { lng: 120.54846954, lat: 16.44165993  },
          { lng: 120.54779053, lat: 16.44399071  },
          { lng: 120.55039215, lat: 16.44368935  },
          { lng: 120.55610657, lat: 16.44301987  },
          { lng: 120.57280731, lat: 16.44105911  },
          { lng: 120.57657623, lat: 16.44061089  },
          { lng: 120.58318329, lat: 16.4400692  },
          { lng: 120.59034729, lat: 16.43907928  },
          { lng: 120.59293365, lat: 16.43894958  },
          { lng: 120.59673309, lat: 16.43877029  },
          { lng: 120.59986877, lat: 16.43849945  },
          { lng: 120.60011292, lat: 16.43849945  },
          { lng: 120.60185242, lat: 16.43856049  },
          { lng: 120.60466766, lat: 16.43799973  },
          { lng: 120.60695648, lat: 16.4375  },
          { lng: 120.60955048, lat: 16.43693924  },
          { lng: 120.61045837, lat: 16.43674088  },
          { lng: 120.61522675, lat: 16.43582916  },
          { lng: 120.62415314, lat: 16.43437004  },
          { lng: 120.6312027, lat: 16.43291092  },
          { lng: 120.63124084, lat: 16.42962074  },
          { lng: 120.63131714, lat: 16.42468071  },
          { lng: 120.6311264, lat: 16.4191494  },
          { lng: 120.63111877, lat: 16.41707039  },
          { lng: 120.63079071, lat: 16.41342926  },
          { lng: 120.63088989, lat: 16.39171028  },
          { lng: 120.631073, lat: 16.38231087  },
          { lng: 120.6297226, lat: 16.37521935  },
          { lng: 120.62895203, lat: 16.36245918  },
          { lng: 120.62481689, lat: 16.36362076  },
          { lng: 120.60662842, lat: 16.36569977  },
          { lng: 120.60076141, lat: 16.36660004  },
          { lng: 120.59744263, lat: 16.36701012 },
        ],
        barangaySelect:'all',
        pieActive: '',
        pieDeceased: '',
        pieRecovered: '',
        pieTotal: '',
        barangayMarker: [],
        disease_id: '',
        name: '',
        show: false,
        title: 'Brookside',
        loading: true,
        barangays: [],
        disease_names: [],
        currYear: 2021,
        countYear: 0,
        mapSearch: {
          barangay_name: '',
          active: '',
          deceased: '',
          recovered: '',
          total: '',
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
              backgroundColor: ["red", "orange", "green"],
              data: [1, 1, 1]
            }
          ]
        },
        totalCase: 1,
        chart_form: {
          disease_id: '',
          barangay_id: '',
        },
        chartValidations: {
          monthErr: false,
          monthMsg: '',
        }
      };
    },
    created(){
      document.title = "BMIID(Map)";

      var currentTime = new Date();
      var year = currentTime.getFullYear();
      this.chart_form.month = ['January','February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][currentTime.getMonth()];
      this.countYear = year - this.currYear;
      this.chart_form.year = year;
      this.chart_form.barangay_id = "Baguio";

      axios.get('/api/allbarangay').then((response) => {
        this.barangays = response.data.data;
        this.selectedBarangays = response.data.data;
      }).catch((error) =>{
        console.log(error);
      }).then(() => {
        axios.get('/api/alldisease').then((response) => {
          this.disease_names = response.data.data;
          this.chart_form.disease_id = response.data.data[0].id;
          this.disease_id =  response.data.data[0].id;
        }).then(() => {
          this.chartForm();
        }).catch((error) =>{
          console.log(error);
        }).then(() => {
          this.markerColor();
        }).then(() => {
          this.loading = false;
        })
      })
    },
    methods: {
      barangaySelected: function(){
        if(this.barangaySelect === "all"){
          this.selectedBarangays = this.barangays;
        }else{
          this.selectedBarangays = [];
          this.selectedBarangays.push(this.barangays[this.barangaySelect - 1]);
        }
      },
      changeDiseaseMap: function(){
        this.markerColor();
      },
      // changeMarker: function(b){
      //   var active = this.barangayMarker[b.id];
      //   console.log(active);
      //   if(active === 0){
      //     return {url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'};
      //   }else if(active > 0 && active < 100){
      //     return {url: 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'};
      //   }else if(active > 100 && active < 200){
      //     return {url: 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png'};
      //   }else if(active > 200){
      //     return {url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'};
      //   }
      // },
      markerColor: function(){
        axios.post('api/map_marker', { disease_id: this.disease_id }).then(response => {
          this.barangayMarker = response.data;
          //return {url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'};
        })
      },
      handleMarkerClicked: function(b)
      {
        axios.post('api/map_filter', { barangay_id: b.id, disease_id: this.disease_id }).then(response => {
          this.mapSearch = {
            barangay_name: b.barangay_name,
            active: Math.abs(response.data.active - (response.data.deceased + response.data.recovered)),
            deceased: response.data.deceased,
            recovered: response.data.recovered,
            total: Math.abs(response.data.deceased + response.data.recovered + (response.data.active - (response.data.deceased + response.data.recovered)))
          };
          this.show = true;
        });
      },

      getPositions: (b) => {
        return{
          lat: parseFloat(b.latitude),
          lng: parseFloat(b.longitude)
        }
      },

      chartForm: function()
      {
        axios.post('/api/map_filter', this.chart_form ).then((response) => {
          console.log(response.data);
          var active = Math.abs(response.data.active - (response.data.deceased + response.data.recovered));
          var deceased = response.data.deceased;
          var recovered = response.data.recovered;
          var total = Math.abs(response.data.deceased + response.data.recovered + (response.data.active - (response.data.deceased + response.data.recovered)));
          this.pieActive = active;
          this.pieDeceased = deceased;
          this.pieRecovered = recovered;
          this.pieTotal = total;

          this.dataChart = {
            hoverBackgroundColor: "red",
            hoverBorderWidth: 10,
            labels: ["Active Cases", "Deceased", "Recovered"],
            datasets: [
              {
                label: "Covid-19 Pie Chart",
                backgroundColor: ["red", "orange", "green", "transparent"],
                data: [active, deceased, recovered]
              }
            ]
          }
        })
        // var date = new Date();
        // var month = date.getMonth() + 1;
        // var year = date.getFullYear();
        // var months = ['January','February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        // var chosenMonth = months.indexOf(this.chart_form.month) + 1;
        
        // if(month < chosenMonth && year === this.chart_form.year){
        //   this.chartValidations = {
        //     monthErr: true,
        //     monthMsg: 'Invalid Month',
        //   }
        // }else{
        //   this.chartValidations = {
        //     monthErr: false,
        //     monthMsg: '',
        //   }

          
        // }
      },
    }
	}
</script>
