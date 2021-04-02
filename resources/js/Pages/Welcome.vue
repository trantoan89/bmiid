<template>
  <div>
    <navbar></navbar>
    <div class="flex container mx-auto justify-center pb-20">
      <!-- <div class="grid grid-rows-2 grid-flow-col gap-4 md:grid-rows-1 mt-32 border w-11/12">
        <div>1</div>
        <div>1</div>
      </div> -->
      <div v-if="loading" class="mt-32 w-full">
        <p class="text-base flex w-full justify-center">
          Loading ...
        </p>
      </div>
      <div v-if="!loading" class="w-full mt-32">
        <div class="block w-9/12 md:w-11/12 mx-auto">
          <h1 class="text-4xl tracking-wide md:text-6xl">Daily News</h1>
          <p class="text-sm md:text-base">{{ news_date | moment("dddd, MMMM Do, YYYY") }}</p>
          <article class="mt-9 text-sm md:text-base">
            {{ news_content }}
          </article>
        </div>
        <div class="mt-16 w-full shadowBox py-8 bg-gradient-to-r from-green-400 to-blue-500">
          <div class="grid grid-rows-2 grid-flow-col gap-2 md:grid-rows-1 justify-center">
            <div class="bg-gradient-to-r from-red-500 h-40 w-44 p-4 rounded">
              <h1 class="text-white text-4xl flex justify-center mt-8">{{ active }}</h1>
              <p class="text-white text-sm flex justify-center">Active Cases</p>
            </div>
            <div class="bg-gradient-to-r from-pink-800 to-pink-400 h-40 w-44 p-4 rounded">
              <h1 class="text-white text-4xl flex justify-center mt-8">{{ deceased }}</h1>
              <p class="text-white text-sm flex justify-center">Deceased</p>
            </div>
            <div class="bg-gradient-to-r from-green-700 to-green-300 h-40 w-44 p-4 rounded">
              <h1 class="text-white text-4xl flex justify-center mt-8">{{ recovered }}</h1>
              <p class="text-white text-sm flex justify-center">Recovered</p>
            </div>
           <div class="bg-gradient-to-r from-yellow-800 to-yellow-300 h-40 w-44 p-4 rounded">
              <h1 class="text-white text-4xl flex justify-center mt-8">{{ total }}</h1>
              <p class="text-white text-sm flex justify-center">Total Cases</p>
            </div>
          </div>
          <div class="flex justify-center pt-5 font-extrabold">
            <select v-on:change="onChange" v-model="case_name" class="bg-transparent border">
              <option v-for="names in disease_names" :key="names.id" :value="names.id">{{ names.disease_name }}</option>
            </select>
          </div>
        </div>
        <div class="mt-16 block w-9/12 md:w-11/12 mx-auto">
          <h1 class="text-3xl tracking-wide md:text-4xl">Health Tips for Covid-19</h1>
          <div class="space-x-4 mx-0 mt-11 bg-white shadow-xl p-1 md:mx-16 md:p-5">
            <div class="flex flex-wrap justify-center content-center md:inline-block">
              <i class="fas fa-people-arrows text-4xl text-yellow-500 md:text-7xl"></i>
            </div>
            <div class="block w-4/5 md:inline-block">
              <p class="text-sm">
                <b>Inside your home:</b> Avoid close contact with people who are sick. If possible, maintain 6 feet between the person who is sick and other household members.
                <br>
                <b>Outside your home: </b> Put 6 feet of distance between yourself and people who don’t live in your household.
              </p>
            </div>
          </div>
          <div class="space-x-4 mx-0 mt-11 bg-white shadow-xl p-1 md:mx-16 md:p-5">
            <div class="flex flex-wrap justify-center content-center md:inline-block">
              <i class="fas fa-head-side-mask text-4xl text-yellow-500 md:text-7xl"></i>
            </div>
            <div class="block w-4/5 md:inline-block">
              <p class="text-sm">
                Everyone 2 and older should wear masks in public. Masks should be worn in addition to staying at least 6 feet apart, especially around people who don’t live with you. If someone in your household is infected, people in the household should take precautions including wearing masks to avoid spread to others.
              </p>
            </div>
          </div>
          <div class="space-x-4 mx-0 mt-11 bg-white shadow-xl p-1 md:mx-16 md:p-5">
            <div class="flex flex-wrap justify-center content-center md:inline-block">
              <i class="fas fa-hands-wash text-4xl text-yellow-500 md:text-7xl"></i>
            </div>
            <div class="block w-4/5 md:inline-block">
              <p class="text-sm">
                Wash your hands often with soap and water for at least 20 seconds especially after you have been in a public place, or after blowing your nose, coughing, or sneezing. If soap and water are not readily available, use a hand sanitizer that contains at least 60% alcohol. Cover all surfaces of your hands and rub them together until they feel dry.
              </p>
            </div>
          </div>
          <div class="space-x-4 mx-0 mt-11 bg-white shadow-xl p-1 md:mx-16 md:p-5">
            <div class="flex flex-wrap justify-center content-center md:inline-block">
              <i class="fas fa-box-tissue text-4xl text-yellow-500 md:text-7xl"></i>
            </div>
            <div class="block w-4/5 md:inline-block">
              <p class="text-sm">
                Always cover your mouth and nose with a tissue when you cough or sneeze or use the inside of your elbow and do not spit. Immediately wash your hands with soap and water for at least 20 seconds. If soap and water are not readily available, clean your hands with a hand sanitizer that contains at least 60% alcohol.
              </p>
            </div>
          </div>
          <div class="space-x-4 mx-0 mt-11 bg-white shadow-xl p-1 md:mx-16 md:p-5">
            <div class="flex flex-wrap justify-center content-center md:inline-block">
              <i class="fas fa-pump-soap text-4xl text-yellow-500 md:text-7xl"></i>
            </div>
            <div class="block w-4/5 md:inline-block">
              <p class="text-sm">
                Clean AND disinfect frequently touched surfaces daily. This includes tables, doorknobs, light switches, countertops, handles, desks, phones, keyboards, toilets, faucets, and sinks. If surfaces are dirty, clean them. Use detergent or soap and water prior to disinfection. Then, use a household disinfectant.
              </p>
            </div>
          </div>
          <div class="space-x-4 mx-0 mt-11 bg-white shadow-xl p-1 md:mx-16 md:p-5">
            <div class="flex flex-wrap justify-center content-center md:inline-block">
              <i class="fas fa-medkit text-4xl text-yellow-500 md:text-7xl"></i>
            </div>
            <div class="block w-4/5 md:inline-block">
              <p class="text-sm">
                Be alert for symptoms. Watch for fever, cough, shortness of breath, or other symptoms of COVID-19. Take your temperature if symptoms develop. Follow CDC guidance if symptoms develop.
              </p>
            </div>
          </div>
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

<script>
  import Navbar from '@/Includes/navbar';
  import Footers from '@/Includes/footer';

  export default {
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
    },
    components: {
      'footers': Footers,
      'navbar': Navbar, 
    },
    data() {
      return {
        disease_names: [],
        news_date: '',
        news_content: '',
        case_name: '',
        loading: true,
        active: 0,
        deceased: 0,
        recovered: 0,
        total: 0,
      }
    },
    created (){
      document.title = "BMIID(Home)";
      
      axios.get('/api/news').then((response) => {
        this.news_content = response.data.data.news_description;
        this.news_date = response.data.data.news_date;
      }).catch((error) => {
        console.log(error);
      }).then(() => {
        axios.get('/api/disease').then((response) => {
          this.case_name = response.data.data.id;
        }).catch((error) => {
          console.log(error);
        }).then(() => {
          this.onChange();
        }).then(() => {
          this.getAllDisease();
          this.loading = false;
        })
      })
    },
    methods:{
      getAllDisease: function(){
        axios.get('/api/alldisease').then((response) => {
          this.disease_names = response.data.data;
        })
      },
      onChange: function(){
        axios.post('/api/count-cases2', { id: this.case_name }).then((response) => {
          this.active = response.data.active - (response.data.deceased + response.data.recovered);
          this.deceased = response.data.deceased;
          this.recovered = response.data.recovered;
          this.total = response.data.total;
        }).catch((error) => {
          console.log(error);
        })
      }
    }
  }
</script>
