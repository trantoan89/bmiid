<template>
  <user-layout>
    <template #header>
      <h2>
        BMIID Accounts
      </h2>
    </template>
      
    <div class="w-80 md:w-full max-w-4xl mx-auto">
      <div class="mt-7">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
          <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
              <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">
                  Add New Account
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                  Add new account information here. <br>
                  Fill-up all information needed. <br>
                  Default password is 123456.
                </p>
              </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
              <form v-on:submit.prevent="addSubmitForm">
                <div class="px-4 py-5 bg-white sm:p-6 shadow rounded">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="username" value="Username"/>
                      <input :class="{'border-red-700' : addValidations.usernameErr}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.username" maxlength="25">
                      <p v-if="addValidations.usernameErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.usernameErrMsg }}</p>
                    </div>
                    <div class="block col-span-8 sm:col-span-6 md:flex">
                      <div class="w-full">
                        <jet-label for="first_name" value="First Name"/>
                        <input :class="{'border-red-700' : addValidations.firstNameErr}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.firstName" maxlength="25">
                        <p v-if="addValidations.firstNameErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.firstNameErrMsg }}</p>
                      </div>
                      <div class="w-full">
                        <jet-label for="last_name" value="Last Name"/>
                        <input :class="{'border-red-700' : addValidations.lastNameErr}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.lastName" maxlength="25">
                        <p v-if="addValidations.lastNameErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.lastNameErrMsg }}</p>
                      </div>
                    </div>
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="email" value="Email"/>
                      <input :class="{'border-red-700' : addValidations.emailErr}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="email" v-model="addForm.email" maxlength="25">
                      <p v-if="addValidations.emailErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.emailErrMsg }}</p>
                    </div>
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="contact_number" value="Contact Number"/>
                      <VuePhoneNumberInput v-model="addForm.contact" default-country-code="PH" valid-color="green" :error="addValidations.contactErr" @update="onUpdate" clearable/>
                      <!-- <input :class="{'border-red-700' : addValidations.contactErr}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.contact" maxlength="13"> -->
                      <p v-if="addValidations.contactErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.contactErrMsg }}</p>
                    </div>
                    <div class="col-span-8 sm:col-span-6">
                      <jet-label for="role" value="Role"/>
                      <select :class="{'border-red-700' : addValidations.roleErr}" class="mt-1 w-full border py-2 px-3 text-grey-darkest" type="text" v-model="addForm.role">
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                      </select>
                      <p v-if="addValidations.roleErr" class="mt-1 ml-1 text-sm text-red-700">{{ addValidations.roleErrMsg }}</p>
                    </div>
                  </div>
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow rounded">
                  <jet-action-message :on="addValidations.submitAdd" class="mr-3">
                    {{ addValidations.submitMsg }}
                  </jet-action-message>
                  <button class="border-2 border-blue-700 text-blue-700 hover:border-blue-500 hover:text-blue-500 px-7 py-2" :disabled="addValidations.addBtn">{{ addValidations.addBtn ? "Loading...":"Add Account" }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- table -->
      <div class="mt-7 p-5 w-full max-w-4xl bg-white rounded shadow-md">
        <h1 class="font-extrabold text-sm md:text-base md:text-2xl uppercase mb-5">Account List</h1>
        <form class="flex" v-on:submit.prevent="submitSearch">
          <input class="px-3 py-2" type="text" v-model="searchInput" placeholder="Disease name"/>
          <button class="px-3 py-2 border border-blue-500 hover:border-blue-700 text-blue-500 hover:text-blue-700 cursor-pointer">Search</button>
        </form>
        <div class="w-full mt-5 overflow-x-auto overflow-y-auto z-0"  style="max-height:700px;">
          <table class="w-full">
            <thead>
              <th class="border"></th>
              <th class="border">Account Type</th>
              <th class="border">Username</th>
              <th class="border">Full Name</th>
              <th class="border">Contact</th>
            </thead>
            <tbody>
              <template v-for="users in allUsers">
                <tr :key="users.id">
                  <td class="p-3 w-2 border">
                    <div class="flex w-full justify-center">
                      <!-- <button class="mr-2 p-1 w-full border border-green-500 rounded text-center relative deleteBtn"  v-on:click="archiveModal(users)">
                        <i class="fas fa-archive text-sm md:text-base" :class="users.archive ? 'text-green-500':'text-red-500'"></i>
                        <p class="absolute text-sm px-2 top-5 -right-10 bg-white border border-black z-10 deleteText">{{ users.archive ? "restore":"archive" }}</p>
                      </button> -->
                      <button class="p-1 w-full border border-red-500 rounded text-center relative deleteBtn" v-on:click="deleteModal(users)">
                        <i class="far fa-trash-alt text-sm md:text-base text-red-500"></i>
                        <p class="absolute text-sm px-2 top-5 -right-4 bg-white border border-black z-10 deleteText">Delete</p>
                      </button>
                    </div>
                  </td>
                  <td class="p-3 border">
                    <p class="text-sm w-32">
                      {{ users.role }}
                    </p>
                  </td>
                  <td class="p-3 border">
                    <p class="w-48 text-sm">{{ users.username }}</p>
                  </td>
                  <td class="p-3 border">
                    <p class="w-80 text-sm ">{{ users.first_name }} {{ users.last_name }}</p>
                  </td>
                  <td class="p-3 border">
                    <p class="w-80 text-sm">{{ users.email }}</p>
                    <p class="w-80 text-sm">{{ users.contact_number }}</p>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <p v-if="allUsers.length < 1" class="text-base text-center mt-1"><i>No Account added.</i></p>
      </div>
      <transition name="fade">
        <!-- Archive modal -->
        <div v-if="modalArchiveOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitArchiveForm" class="w-full text-center">
                <p class="text-center">Are you sure you want to {{ archiveForm.archive ? "restore":"archive" }} {{ archiveForm.user_name }}?</p>
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
        <!--End Archive modal -->

        <!--Delete modal -->
        <div v-if="modalDeleteOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitDeleteForm" class="w-full text-center">
                <p class="text-red-700 text-sm">*upon deleting this, all the record under {{ deleteForm.name }} will be deleted.</p>
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
        <!--End Delete modal -->
      </transition>
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
  import VuePhoneNumberInput from 'vue-phone-number-input';
  import 'vue-phone-number-input/dist/vue-phone-number-input.css';

  export default {
    components: {
      UserLayout,
      JetLabel,
      JetActionMessage,
      VuePhoneNumberInput
    },
    data(){
      return {
        searchInput: '',
        modalDeleteOpen: false,
        modalArchiveOpen: false,
        allUsers: [],
        deleteForm: {
          id: '',
          name: '',
        },
        archiveForm: {
          archive: false,
          user_name: '',
          id: '',
        },
        addForm: {
          username: '',
          firstName: '',
          lastName: '',
          email: '',
          contact: '',
          role: '',
        },
        addValidations:{
          usernameErr: false,
          usernameErrMsg: '',
          firstNameErr: false,
          firstNameErrMsg: '',
          lastNameErr: false,
          lastNameErrMsg: '',
          emailErr: false,
          emailErrMsg: '',
          contactErr: false,
          contactErrMsg: '',
          roleErr: false,
          roleErrMsg: '',
          addBtn: false,
          submitMsg: '',
          submitAdd: false,
        },
      }
    },
    created(){
      document.title = "BMIID(Accounts)";

      this.getAllUsers();
    },
    methods: {
      onUpdate (payload) {
        if(this.addForm.contact === ""){
          this.addValidations.contactErr = false;
          this.addValidations.contactErrMsg = "";
        }else{
          this.addValidations.contactErr = !payload.isValid;
          this.addValidations.contactErrMsg = "Provide a valid number";
        }
      },
      submitSearch: function(){
        if(this.searchInput === ""){
          this.getAllUsers();
        }else{
          axios.post('api/user_search', { input:  this.searchInput}).then(response => {
            this.allUsers = response.data.data;
          })
        }
      },
      submitDeleteForm: function(){
        axios.post('/api/user_delete', this.deleteForm).then( response => {
          this.getAllUsers();
          this.modalDeleteOpen = false;
        })
      },
      deleteModal: function(users){
        this.deleteForm = {
          id: users.id,
          name: users.first_name +" "+users.last_name,
        };
        this.modalDeleteOpen = true;
      },
      submitArchiveForm:function(){
        axios.post('api/user_archive', this.archiveForm).then(response => {
          this.getAllUsers();
          this.modalArchiveOpen = false;
        })
      },
      archiveModal: function(users){
        this.archiveForm = {
          archive: users.archive,
          user_name: users.first_name+' '+users.last_name,
          id: users.id,
        };
        this.modalArchiveOpen = true;
      },
      getAllUsers: function(){
        axios.get('api/users').then(response => {
          this.allUsers = response.data.data;
        })
      },
      addSubmitForm: function(){
        this.addValidations.addBtn = true;
        //username validation
        if(this.addForm.username === ""){
          this.addValidations.usernameErr = true;
          this.addValidations.usernameErrMsg = "Provide a username.";
        }else{
          this.addValidations.usernameErr = false;
          this.addValidations.usernameErrMsg = "";
        }
        //firstname validation
        if(this.addForm.firstName === ""){
          this.addValidations.firstNameErr = true;
          this.addValidations.firstNameErrMsg = "Provide a first name.";
        }else{
          this.addValidations.firstNameErr = false;
          this.addValidations.firstNameErrMsg = "";
        }
        //lastname validation
        if(this.addForm.lastName === ""){
          this.addValidations.lastNameErr = true;
          this.addValidations.lastNameErrMsg = "Provide a last name.";
        }else{
          this.addValidations.lastNameErr = false;
          this.addValidations.lastNameErrMsg = "";
        }
        //email validation
        if(this.addForm.email === ""){
          this.addValidations.emailErr = true;
          this.addValidations.emailErrMsg = "Provide an email.";
        }else{
          this.addValidations.emailErr = false;
          this.addValidations.emailErrMsg = "";
        }
        //contact validation
        if(this.addForm.contact === ""){
          this.addValidations.contactErr = true;
          this.addValidations.contactErrMsg = "Provide a contact.";
        }else{
          this.addValidations.contactErr = false;
          this.addValidations.contactErrMsg = "";
        }
        //role validation
        if(this.addForm.role === ""){
          this.addValidations.roleErr = true;
          this.addValidations.roleErrMsg = "Provide a role.";
        }else{
          this.addValidations.roleErr = false;
          this.addValidations.roleErrMsg = "";
        }

        if(this.addValidations.usernameErr === false && this.addValidations.firstNameErr === false && this.addValidations.lastNameErr === false && this.addValidations.emailErr === false && this.addValidations.contactErr === false && this.addValidations.roleErr === false){
          axios.post('api/add_account', this.addForm).then(response => {
            if(response.data.status_code === "202"){
              this.addValidations.emailErr = true;
              this.addValidations.emailErrMsg = response.data.message;
            }else{
              this.getAllUsers();
              this.addForm = {
                username: '',
                firstName: '',
                lastName: '',
                email: '',
                contact: '',
                role: '',
              };
              this.addValidations.submitMsg = response.data.message;
              this.addValidations.submitAdd = true;
            }
            this.addValidations.addBtn = false;
          })
        }else{
          this.addValidations.addBtn = false;
        }
      }
    }
  }
</script>
