<template>
  <user-layout>
    <template #header>
      <h2>
        Baguio Disease
      </h2>
    </template>
      
    <div class="w-80 md:w-full max-w-4xl mx-auto">
      <div class="mt-7">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
          <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
              <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">
                  Add New Disease
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                  Add new disease information here. <br>
                  max character for name is 50.
                  max character for description is 500.
                </p>
              </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
              <form v-on:submit.prevent="addSubmitForm">
                <div class="px-4 py-5 bg-white sm:p-6 shadow rounded">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="disease_name" value="Disease name"/>
                      <input :class="{'border-red-700':validations.addNameErr}" v-model="addForm.name" class="border mt-1 w-full py-2 px-3 text-grey-darkest" type="text" maxlength="50">
                      <p v-if="validations.addNameErr"  class="mt-1 ml-1 text-sm text-red-700">{{ validations.addNameMsg }}</p>
                    </div>
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="disease_description" value="Disease description"/>
                      <textarea :class="{'border-red-700':validations.addDescErr}" v-model="addForm.description" class="border mt-1 w-full py-2 px-3 text-grey-darkest resize-x-none" type="text" placeholder="Disease description" maxlength="500" rows="4"></textarea>
                      <p v-if="validations.addDescErr" class="mt-1 ml-1 text-sm text-red-700">{{ validations.addDescMsg }}</p>
                    </div>
                    <div class="block col-span-8 sm:col-span-6 md:flex">
                      <div class="w-full">
                        <jet-label for="first_name" value="Risk Percentage"/>
                      </div>
                    </div>
                    <div class="block col-span-8 sm:col-span-6 md:flex">
                      <div class="w-full">
                        <jet-label for="low_risk" value="Low Risk"/>
                        <input :class="{'border-red-700' : validations.addLowRisk}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="number" v-model="addForm.lowRisk" max="100" min="1">
                      </div>
                      <div class="w-full">
                        <jet-label for="severe" value="Severe"/>
                        <input :class="{'border-red-700' : validations.addSevereRisk}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="number" v-model="addForm.severeRisk" max="100" min="1">
                      </div>
                      <div class="w-full">
                        <jet-label for="high_risk" value="High Risk"/>
                        <input :class="{'border-red-700' : validations.addHighRisk}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="number" v-model="addForm.highRisk" max="100" min="1">
                      </div>
                    </div>
                  </div>
                  <div class="block col-span-8 sm:col-span-6 md:flex">
                    <div class="w-full">
                      <p v-if="validations.risk" class="w-full mt-1 ml-1 text-sm text-red-700">{{ validations.riskMsg }}</p>
                    </div>
                  </div>
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow rounded">
                  <jet-action-message :on="this.validations.addDiseaseMsg" class="mr-3">
                    {{ this.validations.addDiseaseMessage }}
                  </jet-action-message>
                  <button class="border-2 border-blue-700 text-blue-700 hover:border-blue-500 hover:text-blue-500 px-7 py-2" :disabled="validations.addBtn">{{ validations.addBtn ? 'Loading..':'Add' }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-7 p-5 w-full max-w-4xl bg-white rounded shadow-md">
        <h1 class="font-extrabold text-sm md:text-base md:text-2xl uppercase mb-5">Disease List</h1>
        <form class="flex" v-on:submit.prevent="submitSearch">
          <input :sync="true" class="px-3 py-2" type="text" v-model="searchInput" placeholder="Disease name"/>
          <select class="px-3 py-2" v-model="searchInputArchive">
            <option value="" selected>All</option>
            <option :value="true">Archives</option>
            <option :value="false">Not Archive</option>
          </select>
          <button class="px-3 py-2 border border-blue-500 hover:border-blue-700 text-blue-500 hover:text-blue-700 cursor-pointer">Search</button>
        </form>
        <div class="w-full mt-5 overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
          <table class="w-full">
            <tbody>
              <template v-for="disease in diseases">
                <tr :key="disease.id">
                  <td class="p-3 w-2 border">
                    <div class="flex w-full justify-center">
                      <button  v-if="!disease.homepage" class="mr-2 p-1 w-full border border-green-500 rounded text-center relative deleteBtn" v-on:click="archiveModal(disease)">
                        <i class="fas fa-archive text-sm md:text-base" :class="disease.archive ? 'text-green-500':'text-red-500'"></i>
                        <p class="absolute text-sm px-2 top-5 -right-10 bg-white border border-black z-10 deleteText">{{ disease.archive ? "restore":"archive" }}</p>
                      </button>
                      <button class="p-1 w-full border border-red-500 rounded text-center relative deleteBtn" v-on:click="diseaseDelete(disease)">
                        <i class="far fa-trash-alt text-sm md:text-base text-red-500"></i>
                        <p class="absolute text-sm px-2 top-5 -right-4 bg-white border border-black z-10 deleteText">Delete</p>
                      </button>
                    </div>
                  </td>
                  <td class="p-3 w-52 border">
                    <p class="w-52 text-sm md:text-base">{{ disease.disease_name }}. <a v-on:click="editDisease(disease)" type="button" class="text-sm text-green-600 cursor-pointer"><u>edit</u></a></p>
                    <p ref="updated" class="text-sm w-32 text-green-500"></p>
                  </td>
                  <td class="p-3 w-52 border">
                    <article class="w-52 text-sm md:w-80 md:text-base">
                      <a v-on:click="editDisease(disease)" type="button" class="cursor-pointer textWrap w-60 text-sm md:w-80 md:text-base text-blue-700 hover:text-blue-500"><u>{{ disease.description }}</u></a>
                    </article>
                    <p ref="check" class="text-sm w-32 text-green-500"></p>
                  </td>
                  <td class="p-3 w-16 border">
                    <div class="w-16">
                      <toggle-button :sync="true" v-on:change="showNews(disease, $event)" v-if="!disease.homepage" :labels="{checked: 'shown', unchecked: 'show'}" :width="70" :disabled="false"/>
                      <toggle-button :sync="true" v-if="disease.homepage" :value="true" :labels="{checked: 'shown', unchecked: 'show'}" :width="70" :disabled="true"/>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <p v-if="diseases.length < 1" class="text-base text-center mt-1"><i>No disease added.</i></p>
      </div>
      
      <transiton name="fade">
        <!-- Edit Disease Modal -->
        <div v-if="modalEditDiseaseOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-3/4 relative">
            <div class="flex flex-col items-start p-4">
              <div class="flex items-center w-full ">
                <div class="text-gray-900 font-medium text-lg uppercase font-extrabold">
                  Disease Information 
                </div>
              </div>
              <hr>
              <form v-on:submit.prevent="submitEditDiseaseForm" class="w-full">
                <div class="w-full">
                  <div class="flex flex-col mb-4 mt-5">
                    <input :class="{'border-red-700': editValidations.editNameErr}" v-model="editDiseaseForm.name" placeholder="Disease name" class="border py-2 px-3 text-grey-darkest" type="text" name="date" id="date" :max="maxDate">
                    <p v-if="editValidations.editNameErr" class="mt-1 ml-1 text-sm text-red-700">{{ editValidations.editNameMsg }}</p>
                  </div>
                  <div class="flex flex-col mb-4 mt-5">
                    <textarea :class="{'border-red-700': editValidations.editDescErr}" v-model="editDiseaseForm.description" class="border py-2 px-3 text-grey-darkest resize-x-none" placeholder="Disease description" type="text" name="content" id="content" rows="11" maxlength="1500"></textarea>
                    <p v-if="editValidations.editDescErr" class="mt-1 ml-1 text-sm text-red-700">{{ editValidations.editDescMsg }}</p>
                  </div>
                  <div class="block col-span-8 sm:col-span-6 md:flex">
                    <div class="w-full">
                      <jet-label for="low_risk" value="Low Risk"/>
                      <input :class="{'border-red-700': editValidations.editRisk}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="number" v-model="editDiseaseForm.lowRisk" max="100" min="1">
                    </div>
                    <div class="w-full">
                      <jet-label for="severe" value="Severe"/>
                      <input :class="{'border-red-700': editValidations.editRisk}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="number" v-model="editDiseaseForm.severeRisk" max="100" min="1">
                    </div>
                    <div class="w-full">
                      <jet-label for="high_risk" value="High Risk"/>
                      <input :class="{'border-red-700': editValidations.editRisk}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="number" v-model="editDiseaseForm.highRisk" max="100" min="1">
                    </div>
                  </div>
                  <p v-if="editValidations.editRisk" class="mt-1 ml-1 text-sm text-red-700">{{ editValidations.editRiskMsg }}</p>
                </div>
                <div class="w-full mt-7 flex justify-end">
                  <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" :disabled="editValidations.editBtn">
                    {{ editValidations.editBtn ? "Loading...":"Update" }}
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalEditDiseaseOpen = false" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                    Close
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!--Delete disease Modal -->
        <div v-if="modalDeleteOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitDeleteForm" class="w-full text-center">
                <p class="text-red-700 text-sm">*upon deleting this, all the record under {{ delDiseaseForm.name }}  will be deleted.</p>
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

        <!--Archive disease Modal -->
        <div v-if="modalArchiveOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitArchiveForm" class="w-full text-center">
                <p class="text-center">Are you sure you want to {{ archiveForm.archive ? "restore":"archive" }} {{ archiveForm.name }}?</p>
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

        <!--Shown disease Modal -->
        <div v-if="modalShownOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitShownForm" class="w-full text-center">
                <p class="text-center">Are you sure you want show this on Homepage?</p>
                <div class="w-full flex justify-end">
                  <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded">
                    Yes
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalShownOpen = false" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                    No
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </transiton>
    </div>
  </user-layout>
</template>

<style scoped>
  @import '../../../css/navbar.css';
</style>
<script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
<script>
  import UserLayout from '@/Layouts/UserLayout';
  import JetLabel from '@/Jetstream/Label'
  import JetActionMessage from '@/Jetstream/ActionMessage'
  import ArchiveSvg from '../../../images/archive.svg';

  export default {
    components: {
      UserLayout,
      JetLabel,
      JetActionMessage
    },
    data(){
      return {
        ArchiveSvg: ArchiveSvg,
        searchInput: '',
        searchInputArchive: '',
        maxDate: "",
        changeShowID: '',
        modalShownOpen: false,
        modalArchiveOpen: false,
        modalDeleteOpen: false,
        modalEditDiseaseOpen: false,
        diseases: [],
        validations:{
          addBtn: false,
          addNameErr: false,
          addNameMsg: '',
          addDescErr: false,
          addDescMsg: '',
          addDiseaseMsg: false,
          addDiseaseMessage: '',
          addLowRisk : false,
          addSevereRisk: false,
          addHighRisk: false,
          risk: false,
          riskMsg: '',
        },
        addForm: {
          name: '',
          description: '',
          lowRisk: '1',
          severeRisk: '1',
          highRisk: '1',
        },
        editValidations: {
          id: '',
          editBtn: false,
          editNameErr: false,
          editNameMsg: '',
          editDescErr: false,
          editDescMsg: '',
          editRisk: false,
          editRiskMsg: '',
        },
        editDiseaseForm:{
          id: '',
          name: '',
          description: '',
          lowRisk: '',
          severeRisk: '',
          highRisk: '',
          risk_id: '',
        },
        delDiseaseForm:{
          name: '',
          id: '',
        },
        archiveForm:{
          id: '',
          name: '',
          archive: false,
        },
      }
    },
    created(){
      document.title = "BMIID(Disease)";
      this.allDisease();

      var d = new Date();
      var month = '' + (d.getMonth() + 1);
      var day = '' + d.getDate();
      var year = d.getFullYear();
      this.addForm.lowRisk = 1;
      this.addForm.severeRisk = 2;
      this.addForm.highRisk = 3;

      if (month.length < 2) 
        month = '0' + month;
      if (day.length < 2) 
        day = '0' + day;
      this.maxDate = year+'-'+month+'-'+day;
    },
    methods: {
      submitSearch: function(){
        if(this.searchInput === "" && this.searchInputArchive === ""){
          this.allDisease();
        }else{
          axios.post('api/disease_search', { input:  this.searchInput, archives: this.searchInputArchive }).then(response => {
            this.diseases = response.data.data;
          })
        }
      },
      allDisease: function(){
        axios.get('api/disease-names').then(response => {
          console.log(response.data.data);
          this.diseases = response.data.data;
        })
      },
      submitShownForm: function(){
        axios.post('/api/disease_show', {id: this.changeShowID}).then(response => {
          this.allDisease();
          this.modalShownOpen = false;
        })
      },
      showNews: function(disease, event){
        if(event.value){
          this.modalShownOpen = true;
          this.changeShowID = disease.id;
        }
      },
      submitArchiveForm: function(){
        axios.post('api/disease_archive', this.archiveForm).then(response => {
          this.allDisease();
          this.modalArchiveOpen = false;
        })
      },
      archiveModal: function(disease){
        this.archiveForm.id = disease.id;
        this.archiveForm.name = disease.disease_name;
        this.archiveForm.archive = disease.archive;
        this.modalArchiveOpen = true;
      },
      submitDeleteForm: function(){
        axios.post('api/disease_delete', this.delDiseaseForm).then(response => {
          this.allDisease();
          this.modalDeleteOpen = false;
        })
      },
      diseaseDelete: function(disease){
        this.delDiseaseForm.id = disease.id;
        this.delDiseaseForm.name = disease.disease_name;
        this.modalDeleteOpen = true;
      },
      submitEditDiseaseForm: function(){
        this.editValidations.editBtn = true;

        if(this.editDiseaseForm.name === ""){
          this.editValidations.editNameErr = true;
          this.editValidations.editNameMsg = 'Provide a name.';
        }else{
          this.editValidations.editNameErr = false;
          this.editValidations.editNameMsg = '';
        }

        if(this.editDiseaseForm.description === ""){
          this.editValidations.editDescErr = true;
          this.editValidations.editDescMsg = "Provide a description.";
        }else{
          this.editValidations.editDescErr = false;
          this.editValidations.editDescMsg = "";
        }

        if(this.editDiseaseForm.severeRisk <= this.editDiseaseForm.lowRisk){
          this.editValidations.editRisk = true;
          this.editValidations.editRiskMsg = "Severe must be higher than Low Risk.";
        }else{
          if(this.editDiseaseForm.highRisk <= this.editDiseaseForm.severeRisk){
            this.editValidations.editRisk = true;
            this.editValidations.editRiskMsg = "High risk must be higher than severe.";
          }else{
            this.editValidations.editRisk = false;
            this.editValidations.editRiskMsg = "";
          }
        }

        if(this.editValidations.editRisk === false && this.editValidations.editDescErr === false && this.editValidations.editNameErr === false){
          axios.post('api/disease_update', this.editDiseaseForm).then(response => {
            console.log(response.data);
            if(response.data.status_code === "200"){
              this.$refs.updated[this.editDiseaseForm.id - 1].innerText = "updated";
              this.allDisease();
              this.editValidations.editBtn = false;
              this.modalEditDiseaseOpen = false;
            }else{
              this.$refs.updated[this.editDiseaseForm.id - 1].innerText = "error";
              this.editValidations.editBtn = false;
              this.modalEditDiseaseOpen = false;
            }
          })
        }else{
          this.editValidations.editBtn = false;
        }
      },
      editDisease: function(disease){
        this.editDiseaseForm.id = disease.id;
        this.editDiseaseForm.name = disease.disease_name;
        this.editDiseaseForm.description = disease.description;
        this.editDiseaseForm.lowRisk = disease.risk.low_risk;
        this.editDiseaseForm.severeRisk = disease.risk.severe_risk;
        this.editDiseaseForm.highRisk = disease.risk.high_risk;
        this.editDiseaseForm.risk_id = disease.risk.id;
        this.modalEditDiseaseOpen = true;
      },
      addSubmitForm: function(event){
        this.validations.addBtn = true;

        if(this.addForm.name === ""){
          this.validations.addNameErr = true;
          this.validations.addNameMsg = 'Provide a name.';
        }else{
          this.validations.addNameErr = false;
          this.validations.addNameMsg = '';
        }

        if(this.addForm.description === ""){
          this.validations.addDescErr = true;
          this.validations.addDescMsg = "Provide a description.";
        }else{
          this.validations.addDescErr = false;
          this.validations.addDescMsg = "";
        }


        if(this.addForm.severeRisk <= this.addForm.lowRisk){
          this.validations.risk = true;
          this.validations.riskMsg = "Severe must be higher than Low Risk.";
        }else{
          if(this.addForm.highRisk <= this.addForm.severeRisk){
            this.validations.risk = true;
            this.validations.riskMsg = "High risk must be higher than severe.";
          }else{
            this.validations.risk = false;
            this.validations.riskMsg = "";
          }
        }

        if(this.validations.risk === false && this.validations.addNameErr === false && this.validations.addDescErr === false){
          axios.post('api/add_disease', this.addForm).then(response => {
            this.addForm = {
              name: '',
              description: '',
            };
            this.allDisease();
            this.validations.addBtn = false;
            this.validations.addDiseaseMessage = response.data.message;
            this.validations.addDiseaseMsg = true;
          })
        }else{
          this.validations.addBtn = false;
        }
      }
    }
  }
</script>
