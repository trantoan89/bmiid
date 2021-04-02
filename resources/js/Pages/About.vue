<template>
  <div>
    <navbar></navbar>
    <div class="flex container mx-auto justify-center pb-20">
      <!-- <div class="grid grid-rows-2 grid-flow-col gap-4 md:grid-rows-1 mt-32 border w-11/12">
        <div>1</div>
        <div>1</div>
      </div> -->
      <div class="w-full mt-32">
        <div class="block w-9/12 md:w-11/12 mx-auto">
          <h1 class="text-4xl tracking-wide md:text-6xl">BMIID</h1>
          <p class="text-sm md:text-base">Baguio Map-Integration of Infectious Disease(BMIID)</p>
          <article class="mt-9 text-sm md:text-base">
            Year 2020, the spread of Covid-19 virus around the world created a pandemic that worries everyone. The virus originated in Wuhan, China when some people in the area started eating a bat that started the so called corona virus or a.k.a Covid-19. Because of the fast spread of the virus, the whole world start monitoring the spread of the Covid-19 and how to take healthy measurements for the disease.
            <br><br>
            The Baguio Map-Integration of Infectious Disease(BMIID) is created to monitor any kind of disease in Baguio City, Philippines. It is for transparency of information to those living in the Baguio and who wants to check the cases of a disease in area of Baguio City. Viewers can check the total number of cases of a disease in the whole baguio or the number of cases in a certain barangay in Baguio City. This will help viewers to avoid or create a healthy measurements to those barangays that has a high number of cases.
          </article>
        </div>
        <div class="mt-16 block w-9/12 md:w-11/12 mx-auto">
          <h1 class="text-3xl tracking-wide md:text-4xl">Features</h1>
          <div class="space-x-4 mx-0 md:mx-16 mt-11 bg-white shadow-xl p-5 flex flex-wrap content-start border-white border-2 hover:border-blue-300">
            <div class="flex justify-center md:inline-block">
              <img :src="MapImage" class="md:h-40 md:w-40 h-60 w-full"/>
            </div>
            <div class="w-4/6 md:inline-block">
              <p class="text-base pt-5">
                A google map that shows all the barangays in baguio using a pin icon. It display different colors that represent the cases of the disease. Viewers can Choose what kind of disease they want to check. Clicking the pin icon will shows the number of cases in that barangay. This will be an easier way for the viewers to check and monitor cases in barangays. 
              </p>
            </div>
          </div>
          <div class="space-x-4 mx-0 md:mx-16 mt-11 bg-white shadow-xl p-5 flex flex-wrap content-start border-white border-2 hover:border-blue-300">
            <div class="flex flex-wrap justify-center content-start md:inline-block">
              <img :src="GraphImage" class="md:h-40 md:w-40 h-60 w-full"/>
            </div>
            <div class="w-4/6 md:inline-block">
              <p class="text-base pt-5">
                A graph that illustrate the information regarding a disease for a better visualization of the cases of a certain disease and to be able to monitor the increase and decrease of the cases. Viewers can choose kind of disease they want to view in the graph. This wil be helpul to have a better understanding about the numbers of cases of a certain disease.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footers></footers>
  </div>  
</template>

<script>
  import Navbar from '@/Includes/navbar';
  import Footer from '@/Includes/footer';
  import MapImage from '../../images/map.png';
  import GraphImage from '../../images/graph.png';

  export default {
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
    },
    data(){
      return{
        MapImage: MapImage,
        GraphImage: GraphImage
      }
    },
    components: {
       'navbar': Navbar,
       'footers': Footer
    },
    created (){
      document.title = "BMIID(About)";
    }
  }
</script>
