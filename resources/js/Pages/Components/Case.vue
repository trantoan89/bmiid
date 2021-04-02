<template>
  <user-layout :pending-cases="pendingCases.length">
    <template #header>
      <h2>
        Baguio Disease Cases
      </h2>
    </template>
      
    <div class="w-80 md:w-full max-w-4xl mx-auto">
      <div class="mt-7">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
          <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
              <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">
                  Add New Case
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                  Add new cases information here. <br>
                  Fill-up all information needed.
                  Atleast select one from active, deceased or recovered.
                </p>
              </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
              <form v-on:submit.prevent="addSubmitForm">
                <div class="px-4 py-5 bg-white sm:p-6 shadow rounded">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="case_date" value="Case Date"/>
                      <input :class="{'border-red-700': addValidations.dateErr}" class="border mt-1 w-full py-2 px-3 text-grey-darkest" v-model="addForm.date" type="date" :max="maxDate">
                      <p v-if="addValidations.dateErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.dateErrMsg }}</p>
                    </div>
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="disease_name" value="Select a disease"/>
                      <select :class="{'border-red-700': addValidations.diseaseErr}" class="border mt-1 w-full py-2 px-3 text-grey-darkest" v-model="addForm.disease" type="text">
                        <option value="">Select Disease</option>
                        <option v-for="disease in diseases" :key="disease.id" :value="disease.id">{{ disease.disease_name }}</option>
                      </select>
                      <p v-if="addValidations.diseaseErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.diseaseErrMsg }}</p>
                    </div>
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="barangay_name" value="Select a barangay"/>
                      <select :class="{'border-red-700': addValidations.barangayErr}" class="border mt-1 w-full py-2 px-3 text-grey-darkest" v-model="addForm.barangayID" type="text" placeholder="Barangay name">
                        <option value="">Select Barangay</option>
                        <option v-for="barangay in barangays" :key="barangay.id" :value="barangay.id">{{ barangay.barangay_name }}</option>
                      </select>
                      <p v-if="addValidations.barangayErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.barangayErrMsg }}</p>
                    </div>
                    <div class="block col-span-8 sm:col-span-6 md:flex">
                      <div class="col-span-8 sm:col-span-6">
                        <jet-label for="active" value="Addtional Active cases"/>
                        <input :class="{'border-red-700': addValidations.caseErr}" class="border mt-1 w-full py-2 px-3 text-grey-darkest" v-model="addForm.active" type="number" min="0">
                      </div>
                      <div class="col-span-8 sm:col-span-6">
                        <jet-label for="deceased" value="Deceased"/>
                        <input :class="{'border-red-700': addValidations.caseErr}" class="border mt-1 w-full py-2 px-3 text-grey-darkest" type="number" v-model="addForm.deceased" min="0">
                      </div>
                      <div class="col-span-8 sm:col-span-6">
                        <jet-label for="recovered" value="Recovered"/>
                        <input :class="{'border-red-700': addValidations.caseErr}" class="border mt-1 w-full py-2 px-3 text-grey-darkest" type="number" v-model="addForm.recovered" min="0">
                      </div>
                    </div>
                    <p v-if="addValidations.caseErr" class="col-span-8 sm:col-span-6 ml-1 text-sm text-red-700">{{ addValidations.caseErrMsg }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow rounded">
                  <jet-action-message :on="addValidations.submitAdd" class="mr-3">
                    {{ addValidations.submitMsg }}
                  </jet-action-message>
                  <button class="border-2 border-blue-700 text-blue-700 hover:border-blue-500 hover:text-blue-500 px-7 py-2" :disabled="addValidations.addBtn">{{ addValidations.addBtn ? "Loading...":"Add case" }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs  -->
      <div class="mt-7 p-5 w-full max-w-4xl bg-white rounded shadow-md">
        <div class="flex flex-wrap">
          <div class="w-full">
            <ul class="block md:flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
              <li class="-mb-px mr-2 cursor-pointer last:mr-0 flex-auto text-center">
                <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs(1)" v-bind:class="{'text-pink-600 bg-white': openTab !== 1, 'text-white bg-pink-600': openTab === 1}">
                  Approved Cases
                </a>
              </li>
              <li class="-mb-px mr-2 cursor-pointer last:mr-0 flex-auto text-center">
                <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs(2)" v-bind:class="{'text-pink-600 bg-white': openTab !== 2, 'text-white bg-pink-600': openTab === 2}">
                  Barangay
                </a>
              </li>
              <li class="-mb-px mr-2 cursor-pointer last:mr-0 flex-auto text-center">
                <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs(3)" v-bind:class="{'text-pink-600 bg-white': openTab !== 3, 'text-white bg-pink-600': openTab === 3}">
                  Disease
                </a>
              </li>
              <li class="-mb-px mr-2 cursor-pointer last:mr-0 flex-auto text-center">
                <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs(4)" v-bind:class="{'text-pink-600 bg-white': openTab !== 4, 'text-white bg-pink-600': openTab === 4}">
                  Pending Cases({{ pendingCases.length }})
                </a>
              </li>
              <li class="-mb-px mr-2 cursor-pointer last:mr-0 flex-auto text-center">
                <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal" v-on:click="toggleTabs(5)" v-bind:class="{'text-pink-600 bg-white': openTab !== 5, 'text-white bg-pink-600': openTab === 5}">
                  Declined Cases
                </a>
              </li>
            </ul>
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 rounded">
              <div class="px-1 py-5 flex-auto">
                <div class="tab-content tab-space">
                  <div v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
                    <div class="w-full overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
                      <table class="w-full">
                        <thead>
                          <th v-if="$page.props.user.role == 'Admin'" class="border"></th>
                          <th class="border">Date and Barangay</th>
                          <th class="border">Approved By</th>
                          <th class="border">Disease</th>
                          <th class="border">Active</th>
                          <th class="border">Deceased</th>
                          <th class="border">Recovered</th>
                          <th v-if="$page.props.user.role == 'Admin'" class="border"></th>
                        </thead>
                        <tbody>
                          <template v-for="(caseArr, index) in cases">
                            <tr :key="caseArr.id">
                              <td v-if="$page.props.user.role == 'Admin'" class="p-3 w-2 border">
                                <div class="flex w-full justify-center">
                                  <button class="p-1 w-full border border-red-500 rounded text-center relative deleteBtn" v-on:click="deleteModal(caseArr)">
                                    <i class="far fa-trash-alt text-sm md:text-base text-red-500"></i>
                                    <p class="absolute text-sm px-2 top-5 -right-4 bg-white border border-black z-10 deleteText">Delete</p>
                                  </button>
                                </div>
                              </td>
                              <td class="p-3 text-sm w-52 border">
                                <p class="w-52 p-0 font-bold m-0 uppercase">{{ caseArr.case_date | moment("MMM Do, YYYY") }}</p>
                                <p class="w-52 p-0 m-0 uppercase">{{ caseArr.barangay.barangay_name }}</p>
                              </td>
                              <td class="p-3 text-sm text-green-500 w-52 border">
                                <p v-if="$page.props.user.role == 'Admin'" class="w-52 text-sm p-0 m-0"><i>Created by: {{ caseArr.createdBy.first_name }} {{ caseArr.createdBy.last_name }}</i></p>
                                <p class="w-52 text-sm p-0 m-0"><i>Approved by: {{ caseArr.approvedBy.first_name }} {{ caseArr.approvedBy.last_name }}</i></p>
                              </td>
                              <td class="p-3 w-52 border">
                                <p class="w-52 text-sm p-0 m-0 uppercase">{{ caseArr.dicease.disease_name }}</p>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="active" type="number" :value="caseArr.active" min="0" :disabled="$page.props.user.role == 'Staff'"/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="deceased" type="number" :value="caseArr.deceased" min="0" :disabled="$page.props.user.role == 'Staff'"/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="recovered" type="number" :value="caseArr.recovered" min="0" :disabled="$page.props.user.role == 'Staff'"/>
                              </td>
                              <td class="p-3 border" v-if="$page.props.user.role == 'Admin'">
                                <button v-on:click="updateCase(caseArr, index)" class="w-full cursor-pointer px-4 py-1 border border-blue-700 hover:border-blue-500 hover:text-blue-500 text-blue-700">Update</button>
                                <p ref="check" class="text-sm w-32 text-green-500"></p>
                              </td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                    <p v-if="cases.length < 1" class="text-base text-center mt-1"><i>No Cases added.</i></p>
                  </div>
                  <div v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
                    <div class="w-full overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
                      <table class="w-full">
                        <thead>
                          <th class="border">Date and Barangay</th>
                          <th class="border">Disease</th>
                          <th class="border">Active</th>
                          <th class="border">Deceased</th>
                          <th class="border">Recovered</th>
                          <th class="border">Total</th>
                        </thead>
                        <tbody>
                          <template v-for="(totalBrngy, index) in totalBarangay">
                            <tr :key="index">
                              <td class="p-3 text-sm w-52 border">
                                <p class="w-52 p-0 m-0 uppercase">{{ totalBrngy.barangay_name }}</p>
                              </td>
                              <td class="p-3 w-52 border">
                                <p class="w-52 text-sm p-0 m-0 uppercase">{{ totalBrngy.disease_name }}</p>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2"  type="number" :value="(totalBrngy.active - totalBrngy.deceased - totalBrngy.recovered)" min="0" disabled/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" type="number" :value="totalBrngy.deceased" min="0" disabled/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" type="number" :value="totalBrngy.recovered" min="0" disabled/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" type="number" :value="(totalBrngy.recovered + totalBrngy.deceased + (totalBrngy.active - totalBrngy.deceased - totalBrngy.recovered))" min="0" disabled/>
                              </td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
                    <div class="w-full overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
                      <table class="w-full">
                        <thead>
                          <th class="border">Disease</th>
                          <th class="border">Active</th>
                          <th class="border">Deceased</th>
                          <th class="border">Recovered</th>
                          <th class="border">Total</th>
                        </thead>
                        <tbody>
                          <template v-for="(totalD, index) in totalDisease">
                            <tr :key="index">
                              <td class="p-3 w-52 border">
                                <p class="w-52 text-sm p-0 m-0 uppercase">{{ totalD.disease_name }}</p>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2"  type="number" :value="(totalD.active - totalD.deceased - totalD.recovered)" min="0" disabled/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" type="number" :value="totalD.deceased" min="0" disabled/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" type="number" :value="totalD.recovered" min="0" disabled/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" type="number" :value="(totalD.recovered + totalD.deceased + (totalD.active - totalD.deceased - totalD.recovered))" min="0" disabled/>
                              </td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}">
                    <div class="w-full overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
                      <table class="w-full">
                        <thead>
                          <th class="border"></th>
                          <th class="border">Date and Barangay</th>
                          <th v-if="$page.props.user.role == 'Admin'" class="border">Created By</th>
                          <th class="border">Disease</th>
                          <th class="border">Active</th>
                          <th class="border">Deceased</th>
                          <th class="border">Recovered</th>
                        </thead>
                        <tbody>
                          <template v-for="caseArr in pendingCases">
                            <tr :key="caseArr.id">
                              <td class="p-3 w-6 border">
                                <div class="flex w-full justify-center">
                                  <button v-if="$page.props.user.role == 'Admin'" class="p-1 w-full border border-green-500 rounded text-center relative deleteBtn" v-on:click="approvePending(caseArr)">
                                    <i class="fas fa-check text-sm md:text-base text-green-500"></i>
                                    <p class="absolute text-sm px-2 top-5 -right-10 bg-white border border-black z-10 deleteText">Approved</p>
                                  </button>
                                  <button class="ml-2 p-1 w-full border border-red-500 rounded text-center relative deleteBtn" v-on:click="cancelPending(caseArr, $page.props.user.role)">
                                    <i class="fas fa-times text-sm md:text-base text-red-500"></i>
                                    <p v-if="$page.props.user.role == 'Admin'" class="absolute text-sm px-2 top-5 -right-4 bg-white border border-black z-10 deleteText">Decline</p>
                                    <p v-if="$page.props.user.role == 'Staff'" class="absolute text-sm px-2 top-5 -right-4 bg-white border border-black z-10 deleteText">Cancel</p>
                                  </button>
                                </div>
                              </td>
                              <td class="p-3 text-sm w-52 border">
                                <p class="w-52 p-0 font-bold m-0 uppercase">{{ caseArr.case_date | moment("MMM Do, YYYY") }}</p>
                                <p class="w-52 p-0 m-0 uppercase">{{ caseArr.barangay.barangay_name }}</p>
                              </td>
                              <td v-if="$page.props.user.role == 'Admin'" class="p-3 text-sm text-green-500 w-52 border">
                                <p class="w-52 text-sm p-0 m-0"><i>Created by: {{ caseArr.createdBy.first_name }} {{ caseArr.createdBy.last_name }}</i></p>
                              </td>
                              <td class="p-3 w-52 border">
                                <p class="w-52 text-sm p-0 m-0 uppercase">{{ caseArr.dicease.disease_name }}</p>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="active" type="number" :value="caseArr.active" min="0" disabled="true"/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="deceased" type="number" :value="caseArr.deceased" min="0" disabled="true"/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="recovered" type="number" :value="caseArr.recovered" min="0" disabled="true"/>
                              </td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                    <p v-if="pendingCases.length < 1" class="text-base text-center mt-1"><i>No Pending Case.</i></p>
                  </div>
                  <div v-bind:class="{'hidden': openTab !== 5, 'block': openTab === 5}">
                    <div class="w-full overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
                      <table class="w-full">
                        <thead>
                          <th class="border">Date and Barangay</th>
                          <th class="border">Declined By</th>
                          <th class="border">Disease</th>
                          <th class="border">Active</th>
                          <th class="border">Deceased</th>
                          <th class="border">Recovered</th>
                        </thead>
                        <tbody>
                          <template v-for="caseArr in declinedCases">
                            <tr :key="caseArr.id">
                              <td class="p-3 text-sm w-52 border">
                                <p class="w-52 p-0 font-bold m-0 uppercase">{{ caseArr.case_date | moment("MMM Do, YYYY") }}</p>
                                <p class="w-52 p-0 m-0 uppercase">{{ caseArr.barangay.barangay_name }}</p>
                              </td>
                              <td class="p-3 text-sm text-red-500 w-52 border">
                                <p v-if="$page.props.user.role == 'Admin'" class="w-52 text-sm p-0 m-0"><i>Created by: {{ caseArr.createdBy.first_name }} {{ caseArr.createdBy.last_name }}</i></p>
                                <p class="w-52 text-sm p-0 m-0"><i>Declined by: {{ caseArr.approvedBy.first_name }} {{ caseArr.approvedBy.last_name }}</i></p>
                              </td>
                              <td class="p-3 w-52 border">
                                <p class="w-52 text-sm p-0 m-0 uppercase">{{ caseArr.dicease.disease_name }}</p>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="active" type="number" :value="caseArr.active" min="0" disabled="true"/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="deceased" type="number" :value="caseArr.deceased" min="0" disabled="true"/>
                              </td>
                              <td class="p-3 w-32 border">
                                <input class="w-32 px-2" ref="recovered" type="number" :value="caseArr.recovered" min="0" disabled="true"/>
                              </td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                    <p v-if="declinedCases.length < 1" class="text-base text-center mt-1"><i>No Declined Case.</i></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <transion name="fade">
        <!-- Delete Modal -->
        <div v-if="modalDeleteOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitDeleteForm" class="w-full text-center">
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
        <!-- end of Delete Modal -->
      </transion>
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

  export default {
    components: {
      UserLayout,
      JetLabel,
      JetActionMessage
    },
    data(){
      return {
        declinedCases: [],
        pendingCases: [],
        totalDisease: [],
        totalBarangay: [],
        modalDeleteOpen: false,
        caseDeleteID: '',
        openTab: 1,
        barangays: [],
        diseases: [],
        cases: [],
        maxDate: "",
        addForm: {
          date: '',
          disease: '',
          barangayID: '',
          active: '',
          deceased: '',
          recovered: '',
        },
        addValidations: {
          dateErr: false,
          dateErrMsg: '',
          diseaseErr: false,
          diseaseErrMsg: '',
          barangayErr: false,
          barangayErrMsg: '',
          caseErr: false,
          caseErrMsg: '',
          addBtn: false,
          submitMsg: '',
          submitAdd: false,
        }
      }
    },
    created(){
      document.title = "BMIID(Case)";

      var d = new Date();
      var month = '' + (d.getMonth() + 1);
      var day = '' + d.getDate();
      var year = d.getFullYear();

      if (month.length < 2) 
        month = '0' + month;
      if (day.length < 2) 
        day = '0' + day;
      this.maxDate = year+'-'+month+'-'+day;
      this.addForm.date = this.maxDate;

      this.allDisease();
      this.allBarangays();
      this.allCase();
      this.totalBarangayCase();
      this.totalDiseaseCase();
      this.pendingCase();
      this.declineCase();
    },
    methods: {
      declineCase: function(){
        axios.get('api/decline_case').then(response => {
          this.declinedCases = response.data.data;
        })
      },
      approvePending: function(cases){
        axios.post('api/case_status', { id: cases.id, status: 'approved' }).then(response => {
          this.pendingCase();
          this.allDisease();
          this.allBarangays();
          this.allCase();
          this.totalBarangayCase();
          this.totalDiseaseCase();
        });
      },
      cancelPending: function(cases, role){
        if(role === "Staff"){
          axios.post('api/case_delete', { id: cases.id }).then(response => {
            this.pendingCase();
          });
        }else if(role === "Admin"){
          axios.post('api/case_status', { id: cases.id, status: 'declined' }).then(response => {
            this.pendingCase();
            this.declineCase();
          });
        }
      },
      pendingCase: function(){
        axios.get('api/pending_case').then(response => {
          this.pendingCases = response.data.data;
        })
      },
      totalBarangayCase: function(){
        axios.get('api/total_barangay_case').then(response => {
          this.totalBarangay = response.data[0];
        })
      },
      totalDiseaseCase: function(){
        axios.get('api/total_disease_case').then(response => {
          this.totalDisease = response.data[0];
        })
      },
      toggleTabs: function(tabNumber){
        this.openTab = tabNumber
      },
      submitDeleteForm: function(){
        axios.post('api/case_delete', {id: this.caseDeleteID}).then(response => {
          this.allCase();
          this.modalDeleteOpen = false;
          this.totalBarangayCase();
          this.totalDiseaseCase();
        })
      },
      deleteModal: function(caseDetails){
        this.modalDeleteOpen = true;
        this.caseDeleteID = caseDetails.id;
      },
      updateCase: function(caseDetails, index){
        var active =  this.$refs.active[index].value;
        var deceased = this.$refs.deceased[index].value;
        var recovered = this.$refs.recovered[index].value;

        if(active === "" && deceased === "" && recovered === ""){
          this.$refs.check[index].innerText = "Incorrect Inputs";
        }else{
          if(active < 0 || deceased < 0 || recovered < 0){
            this.$refs.check[index].innerText = "Incorrect Inputs";
          }else{
            axios.post('api/case_update', { id:caseDetails.id, active:active, deceased,deceased, recovered:recovered }).then(response => {
              this.$refs.check[index].innerText = response.data.message;
              this.allCase();
              this.totalBarangayCase();
              this.totalDiseaseCase();
            });
          }
        }
      },
      addSubmitForm: function(){
        this.addValidations.addBtn = true;

        //date validation
        if(this.addForm.date === ""){
          this.addValidations.dateErr = true;
          this.addValidations.dateErrMsg = "Provide a date";
        }else{
          this.addValidations.dateErr = false;
          this.addValidations.dateErrMsg = "";
        }

        //disease validation
        if(this.addForm.disease === ""){
          this.addValidations.diseaseErr = true;
          this.addValidations.diseaseErrMsg = "Select a disease";
        }else{
          this.addValidations.diseaseErr = false;
          this.addValidations.diseaseErrMsg = "";
        }

        //barangay validation
        if(this.addForm.barangayID === ""){
          this.addValidations.barangayErr = true;
          this.addValidations.barangayErrMsg = "Select a barangay";
        }else{
          this.addValidations.barangayErr = false;
          this.addValidations.barangayErrMsg = "";
        }

        //case validation
        if(this.addForm.active ==="" && this.addForm.deceased === "" && this.addForm.recovered === ""){
          this.addValidations.caseErr = true;
          this.addValidations.caseErrMsg = "Atlease provide a number in either of the three";
        }else{
          this.addValidations.caseErr = false;
          this.addValidations.caseErrMsg = "";
        }

        if(this.addValidations.caseErr === false && this.addValidations.barangayErr === false && this.addValidations.diseaseErr === false && this.addValidations.dateErr === false){
          axios.post('api/add_case', this.addForm).then(response => {
            this.addForm = {
              date: '',
              disease: '',
              barangayID: '',
              active: '',
              deceased: '',
              recovered: '',
            }

            this.allCase();
            this.addValidations.submitAdd = true;
            this.addValidations.submitMsg = response.data.message,
            this.addValidations.addBtn = false;
            this.pendingCase();
            this.totalBarangayCase();
            this.totalDiseaseCase();
          })
        }else{
          this.addValidations.addBtn = false;
        }
      },
      allDisease: function(){
        axios.get('api/disease-names').then(response => {
          this.diseases = response.data.data;
        })
      },
      allBarangays: function(){
        axios.get('/api/barangay').then((response) => {
          this.barangays = response.data.data;
        })
      },
      allCase: function(){
        axios.get('api/all_case').then(response => {
          this.cases = response.data.data;
        })
      }
    }
  }
</script>
