<template>
  <user-layout>
    <template #header>
      <h2>
        Baguio Barangays
      </h2>
    </template>
      
    <div class="mt-16 w-80 md:w-full max-w-4xl mx-auto">
      <!-- <div class="mt-16">
        <form v-on:submit.prevent="addSubmitForm" class="bg-white shadow-md rounded p-5">
          <h1 class="uppercase text-2xl font-bold">Add Barangay</h1>
          <p v-if="this.validations.msg" class="text-sm text-green-700 mt-3">{{ this.validations.message }}</p>
          <div class="flex flex-col mb-2 mt-5">
            <input :class="{'border-red-700':validations.nameErr}" class="border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.name" placeholder="Barangay name" maxlength="50">
            <p v-if="validations.nameErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.nameErrMsg }}</p>
          </div>
          <div class="block md:flex">
            <div class="flex flex-col mb-4 w-full">
              <input :class="{'border-red-700':validations.latErr}" class="border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.lat" placeholder="Latitude">
              <p v-if="validations.latErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.latErrMsg }}</p>
            </div>
            <div class="flex flex-col mb-4 md:ml-2 w-full">
              <input :class="{'border-red-700':validations.lngErr}" class="border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.lng" placeholder="Longitude">
              <p v-if="validations.lngErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.lngErrMsg }}</p>
            </div>
            <div class="flex flex-col mb-4 md:ml-2 w-full">
              <input :class="{'border-red-700':validations.populationErr}" class="border py-2 px-3 text-grey-darkest" type="number" v-model="addForm.population"  placeholder="Population" min="0">
              <p v-if="validations.populationErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.populationErrMsg }}</p>
            </div>
          </div>
          <div class="flex justify-between w-full">
            <a type="button" v-on:click="showExample = true" class="cursor-pointer text-sm text-blue-700 hover:text-color-500"><u>How to get latitude and longitude?</u></a>
            <button class="border-2 border-blue-700 text-blue-700 hover:border-blue-500 hover:text-blue-500 px-7 py-2" :disabled="validations.addBtn">{{ validations.addBtn ? 'Loading..':'Add' }}</button>
          </div>
        </form>
      </div> -->
      <transition name="fade">
        <div v-if="showExample" class="mt-7 w-full">
          <div class="flex justify-between w-full">
            <h1 class="text-base font-bold uppercase">Getting latitude and longitude in Google maps.</h1>
            <button class="relative cursor-pointer" v-on:click="showExample = false"  @mouseenter="closeText= true" @mouseleave="closeText=false">
              <i class="fas fa-times text-sm"></i>
              <p v-if="closeText" class="border border-black absolute text-sm px-2 -right-2 bg-white">close</p>
            </button>
          </div>
          <ol class="mt-5 text-sm ml-7 list-decimal">
            <li class="mt-1">Open Google maps <a class="text-blue-700 hover:text-blue-500" target="_blank" href="https://www.google.com/maps/">https://www.google.com/maps/ <i class="fas fa-external-link-alt"></i></a></li>
            <li class="mt-1">Search the barangay.</li>
            <li class="mt-1">Right click on the red marker.</li>
            <li class="mt-1">Example: 16.42719 is latitude and 120.58215 is longitude.</li>
          </ol>
          <div class="max-w-3xl mt-5 flex justify-center">
            <img :src="exampleImage" class="w-full"/>
          </div>
        </div>
      </transition>
      <div class="mt-16 p-5 w-full max-w-4xl bg-white rounded shadow-md">
        <h1 class="font-extrabold text-sm md:text-base md:text-2xl uppercase mb-5">Barangay List</h1>
        <form class="flex" v-on:submit.prevent="submitSearch">
          <input class="px-3 py-2" type="text" v-model="searchInput" placeholder="Barangay name"/>
          <button class="px-3 py-2 border border-blue-500 hover:border-blue-700 text-blue-500 hover:text-blue-700 cursor-pointer">Search</button>
        </form>
        <div class="w-full mt-5 overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
          <table class="w-full">
            <thead>
              <th v-if="$page.props.user.role == 'Admin'" class="border"></th>
              <th class="border">Barangay Name</th>
              <th class="border">Population</th>
              <th class="border">Action</th>
            </thead>
            <tbody>
              <template v-for="(brngy, index) in barangays">
                <tr :key="brngy.id">
                  <td class="p-3 w-2 border" v-if="$page.props.user.role == 'Admin'">
                    <div class="flex w-full justify-center">
                      <!-- <button class="mr-2 p-1 w-full border border-green-500 rounded text-center relative deleteBtn" v-on:click="archiveModal(brngy)">
                        <i class="fas fa-archive text-sm md:text-base" :class="brngy.archive ? 'text-green-500':'text-red-500'"></i>
                        <p class="absolute text-sm px-2 top-5 -right-10 bg-white border border-black z-10 deleteText">{{ brngy.archive ? "restore":"archive" }}</p>
                      </button> -->
                      <button class="p-1 w-full border border-red-500 rounded text-center relative deleteBtn" v-on:click="deleteModal(brngy)">
                        <i class="far fa-trash-alt text-sm md:text-base text-red-500"></i>
                        <!-- <p class="absolute text-sm px-2 top-5 -right-4 bg-white border border-black z-10 deleteText">Delete</p> -->
                      </button>
                    </div>
                  </td>
                  <td class="p-3 border">
                    <p class="w-80 text-sm md:text-base">{{ brngy.barangay_name }}.  
                      <!-- <a v-on:click="editBarangay(brngy, index)" type="button" class="text-sm text-green-600 cursor-pointer"><u>edit</u></a> -->
                    </p>
                    <p ref="updated" class="text-sm w-32 text-green-500"></p>
                  </td>
                  <td class="p-3 border">
                    <p class="text-sm w-32 md:text-base">
                      <input ref="population" type="number" class="w-full" min="0" :value="brngy.population"/>
                    </p>
                    <p ref="check" class="text-sm w-32 text-green-500"></p>
                  </td>
                  <td class="p-3 border">
                    <button v-on:click="submitUpdate(brngy, index)" class="w-full cursor-pointer px-4 py-1 border border-blue-700 hover:border-blue-500 hover:text-blue-500 text-blue-700">Update</button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <p v-if="barangays.length < 1" class="text-base text-center mt-1"><i>No barangay records.</i></p>
      </div>
      <transition name="fade">
        <div v-if="modalDeleteOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitDeleteForm" class="w-full text-center">
                <p class="text-red-700 text-sm">*upon deleting this, all the record under {{ deleteBarangayName }} will be deleted.</p>
                <p class="text-center">Are you sure you want to delete this record?</p>
                <div class="w-full flex justify-end">
                  <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded">
                    Yes
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalDeleteOpen = false" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                    No
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div v-if="modalArchiveOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitArchiveForm" class="w-full text-center">
                <p class="text-center">Are you sure you want to {{ archiveForm.archive ? "restore":"archive" }} {{ archiveForm.barangay_name }}?</p>
                <div class="w-full flex justify-end">
                  <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded">
                    Yes
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalArchiveOpen = false" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                    No
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div v-if="modalEditOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitEditForm" class="w-full">
                <h1 class="uppercase text-2xl font-bold">Update Barangay</h1>
                <div class="flex flex-col mb-2 mt-5">
                  <input :class="{'border-red-700':validations.edtiNameErr}" class="border py-2 px-3 text-grey-darkest" type="text" v-model="editForm.name" placeholder="Barangay name" maxlength="50">
                  <p v-if="validations.editNameErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.editNameErrMsg }}</p>
                </div>
                <div class="block md:flex">
                  <div class="flex flex-col mb-4 w-full">
                    <input :class="{'border-red-700':validations.editLatErr}" class="border py-2 px-3 text-grey-darkest" type="text" v-model="editForm.lat" placeholder="Latitude">
                    <p v-if="validations.editLatErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.editLatErrMsg }}</p>
                  </div>
                  <div class="flex flex-col mb-4 md:ml-2 w-full">
                    <input :class="{'border-red-700':validations.editLngErr}" class="border py-2 px-3 text-grey-darkest" type="text" v-model="editForm.lng" placeholder="Longitude">
                    <p v-if="validations.editLngErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.editLngErrMsg }}</p>
                  </div>
                </div>
                <div class="w-full flex justify-end">
                  <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded" :disabled="validations.updateBtn">
                    {{ validations.updateBtn ? "Loading...":"Update" }}
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalEditOpen = false" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                    Close
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </user-layout>
</template>

<style scoped>
  @import '../../../css/navbar.css';
</style>

<script>
  import UserLayout from '@/Layouts/UserLayout';
  import exampleImage from '../../../images/example_map.png';

  export default {
    components: {
      UserLayout,
    },
    data(){
      return {
        searchInput:'',
        count: 0,
        modalArchiveOpen: false,
        modalEditOpen: false,
        modalDeleteOpen: false,
        deleteBarangayName: '',
        barangays: [],
        exampleImage:exampleImage,
        showExample: false,
        closeText: false,
        deleteID: '',
        archiveForm:{
          ID: '',
          archive: false,
          barangay_name: '',
        },
        addForm:{
          name: '',
          lat: '',
          lng: '',
          population: '',
        },
        editForm:{
          name: '',
          lat: '',
          lng: '',
          ID: '',
          index: '',
        },
        validations:{
          nameErr: false,
          nameErrMsg: '',
          latErr: false,
          latErrMsg: '',
          lngErr: false,
          lngErrMsg: '',
          populationErr: false,
          populationErrMsg: '',
          edtiNameErr: false,
          editNameErrMsg: '',
          editLatErr: false,
          editLatErrMsg: '',
          editLngErr: false,
          editLngErrMsg: '',
          addBtn: false,
          updateBtn: false,
          message: '',
          msg: false,
        }
      }
    },
    created(){
      document.title = "BMIID(Barangay)";

      this.barangayList();
    },
    methods: {
      submitSearch: function(){
        if(this.searchInput === ""){
          this.barangayList();
        }else{
          axios.post('api/barangay_search', { input:  this.searchInput}).then(response => {
            this.barangays = response.data.data;
          })
        }
      },
      submitArchiveForm:function(){
        axios.post('api/barangay_archive', this.archiveForm).then(response => {
          this.barangayList();
          this.modalArchiveOpen = false;
        })
      },
      archiveModal: function(barangay){
        this.archiveForm.archive = barangay.archive;
        this.archiveForm.ID = barangay.id;
        this.archiveForm.barangay_name = barangay.barangay_name;
        this.modalArchiveOpen = true;
      },
      submitEditForm: function(){
        this.validations.updateBtn = true;

        //Barangay name check
        if(this.editForm.name === ""){
          this.validations.editNameErr = true;
          this.validations.editNameErrMsg = "Provide a Barangay name.";
        }else{
          this.validations.editNameErr = false;
          this.validations.editNameErrMsg = "";
        }
        //latitude check
        if(this.editForm.lat === ""){
          this.validations.editLatErr = true;
          this.validations.editLatErrMsg = "Provide latitude.";
        }else{
          this.validations.editLatErr = false;
          this.validations.editLatErrMsg = "";
        }
        //longitude check
        if(this.editForm.lng === ""){
          this.validations.editLngErr = true;
          this.validations.editLngErrMsg = "Provide longitude.";
        }else{
          this.validations.editLngErr = false;
          this.validations.editLngErrMsg = "";
        }

        //check all if true
        if(this.validations.nameErr === false && this.validations.latErr === false && this.validations.lngErr === false){
          axios.post('/api/update_barangay', this.editForm).then(response => {
            if(response.data.status_code === "200"){
              this.$refs.updated[this.editForm.index].innerText = "updated";
            }else{
              this.$refs.updated[this.editForm.index].innerText = "Error in updating";
            }

            this.barangayList();
            this.validations.updateBtn = false;
            this.modalEditOpen = false;
          });
        }else{
          this.validations.updateBtn = false;
        }
      },
      editBarangay: function(barangay, index){
        this.editForm.name = barangay.barangay_name;
        this.editForm.lat = barangay.latitude;
        this.editForm.lng = barangay.longitude;
        this.editForm.ID = barangay.id;
        this.editForm.index = index;
        this.modalEditOpen = true;
      },
      submitDeleteForm: function(){
        axios.post('/api/delete_barangay', { id: this.deleteID }).then( response => {
          this.barangayList();
          this.modalDeleteOpen = false;
        })
      },
      deleteModal: function(barangay){
        this.deleteBarangayName = barangay.barangay_name;
        this.modalDeleteOpen = true;
        this.deleteID = barangay.id;
      },
      barangayList: function(){
        axios.get('/api/barangay').then((response) => {
          this.barangays = response.data.data;
        }).catch((error) =>{
          console.log(error);
        })
      },
      submitUpdate: function(barangay, count){
        var value =  this.$refs.population[count].value;
        if(value < 0){
          this.$refs.check[count].innerText = "Incorrect";
        }else{
          axios.post('/api/update_pop', { population: value, id: barangay.id }).then(response => {
            this.$refs.check[count].innerText = "updated";
            this.barangayList();
          });
        }
      },
      addSubmitForm: function(){
        this.validations.addBtn = true;

        //Barangay name check
        if(this.addForm.name === ""){
          this.validations.nameErr = true;
          this.validations.nameErrMsg = "Provide a Barangay name.";
        }else{
          this.validations.nameErr = false;
          this.validations.nameErrMsg = "";
        }
        //latitude check
        if(this.addForm.lat === ""){
          this.validations.latErr = true;
          this.validations.latErrMsg = "Provide latitude.";
        }else{
          this.validations.latErr = false;
          this.validations.latErrMsg = "";
        }
        //longitude check
        if(this.addForm.lng === ""){
          this.validations.lngErr = true;
          this.validations.lngErrMsg = "Provide longitude.";
        }else{
          this.validations.lngErr = false;
          this.validations.lngErrMsg = "";
        }
        //population
        if(this.addForm.population === ""){
          this.validations.populationErr = true;
          this.validations.populationErrMsg = "Provide population.";
        }else{
          this.validations.populationErr = false;
          this.validations.populationErrMsg = "";
        }

        //check all if true
        if(this.validations.nameErr === false && this.validations.latErr === false && this.validations.lngErr === false && this.validations.populationErr === false){
          axios.post('/api/create_barangay', this.addForm).then(response => {
            this.validations.message = response.data.message;
            this.validations.msg = true;
            this.addForm = {
              name: '',
              lat: '',
              lng: '',
              population: '',
            };
            this.barangayList();
          })
        }else{
          this.validations.addBtn = false;
        }
      },
    }
  }
</script>
