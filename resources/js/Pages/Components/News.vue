<template>
  <user-layout>
    <template #header>
      <h2>
        Daily news
      </h2>
    </template>
      
    <div class="mt-16 w-80 md:w-full max-w-4xl mx-auto">
      <div v-if="!newsShow" class="flex justify-end">
        <button v-on:click="newsShow = !newsShow" class="border-2 border-blue-500 text-blue-500 px-5 p-2 hover:border-blue-700 hover:text-blue-700"><i class="fas fa-plus"></i> Add News</button>
      </div>
      <transition name="fade">
        <form v-on:submit.prevent="addForm" v-if="newsShow" class="w-full p-5 bg-gradient-to-l from-gray-500 to-gray-700 rounded relative">
          <div class="absolute top-5 right-5 cursor-pointer" >
            <button type="button" v-on:click="newsShow = !newsShow" class="relative text-xl cursor-pointer" @mouseenter="closeText= true" @mouseleave="closeText=false">
              <i class="fas fa-times text-base text-gray-200"></i>
              <p v-if="closeText" class="absolute text-sm px-2 -right-2 bg-white">close</p>
            </button>
          </div>
          <h1 class="text-base md:text-3xl text-bold  text-gray-200 uppercase">Add daily news</h1>
          <div class="flex flex-col mb-4 mt-5">
            <label class="mb-2 text-sm md:text-base  text-gray-200" for="date">News Date</label>
            <input :class="{'border-red-700':validation.dateErr}" class="border py-2 px-3 text-grey-darkest" v-model="newsForm.date" type="date" name="date" id="date" :max="maxDate">
            <p v-if="validation.dateErr" class="mt-1 ml-1 text-sm text-red-300">{{ validation.dateMsg }}</p>
          </div>
          <div class="flex flex-col mb-4 mt-5">
            <label class="mb-2 text-sm md:text-base  text-gray-200" for="content">News Content</label>
            <textarea :class="{'border-red-700':validation.contentErr}" class="border py-2 px-3 text-grey-darkest resize-x-none" v-model="newsForm.content" placeholder="Place your news here." type="text" name="content" id="content" rows="9" maxlength="3500"></textarea>
            <p v-if="validation.contentErr" class="mt-1 ml-1 text-sm text-red-300">{{ validation.contentMsg }}</p>
            <p v-if="validation.submitted" class="text-base mt-1 text-green-500 text-center">{{ validation.submitMsg }}</p>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="border-2 border-white text-white px-10 py-2 hover:border-gray-200 hover:text-gray-200" :disabled="this.validation.submitForm">{{ this.validation.submitForm ? "Loading..." : "Submit" }}</button>
          </div>
        </form>
      </transition>
      <div class="mt-16 p-5 w-full max-w-4xl bg-white rounded shadow-md">
        <h1 class="font-extrabold text-sm md:text-base md:text-2xl uppercase mb-5">Added News</h1>
        <div class="flex">
          <div class="flex items-center border rounded p-2 mb-3 w-28 mr-3 cursor-pointer">
            <input type="checkbox" id="checkbox-example" class="h-4 w-4 text-gray-700 border rounded mr-2 cursor-pointer" v-on:click="selectAll" v-model="allSelected">
            <label for="checkbox-example" class="cursor-pointer">Select all</label>
          </div>
          <form v-on:submit.prevent="submitDeleteForm">
            <button v-if="newsIds.length > 0" v-on:click="newsDelete(newsIds, 'many')" class="flex items-center border border-red-700 text-red-700 hover:border-red-500 hover:text-red-500 rounded p-2 mb-3 w-28">
              <label class="cursor-pointer"><i class="far fa-trash-alt text-base"></i> Delete</label>
            </button>
          </form>
          <p class="p-2 mb-3 text-sm text-red-300">Selected {{ newsIds.length }} item(s)</p>
        </div>
        <p v-if="deletedTrue" class="text-green-500 text-sm mb-3">{{ deletedMsg }}.</p>
        <div class="w-full overflow-x-auto overflow-y-auto z-0" style="max-height:700px;">
          <table class="w-full">
            <tbody>
              <template v-for="newsArr in news">
                <tr :key="newsArr.id">
                  <td class="p-3 border">
                    <div class="w-4 text-sm md:text-base flex justify-center flex-wrap content-center">
                      <input v-if="!newsArr.show" type="checkbox" :value="newsArr.id" v-model="newsIds" v:on:click="select" class="h-4 w-4 text-gray-700 border rounded mr-2 cursor-pointer">
                    </div>
                  </td>
                  <td class="p-3 border">
                    <div class="w-11 flex justify-center">
                      <button class="p-1 border border-green-500 rounded text-center relative editBtn" v-on:click="newsEdit(newsArr, 'edit')">
                        <i class="far fa-edit text-sm md:text-base text-green-500"></i>
                        <p class="absolute text-sm px-2 top-5 -right-2 bg-white border border-black z-10 editText">Edit</p>
                      </button>&nbsp;
                      <button v-if="!newsArr.show" class="p-1 border border-red-500 rounded text-center relative deleteBtn" v-on:click="newsDelete(newsArr, 'one')">
                        <i class="far fa-trash-alt text-sm md:text-base text-red-500"></i>
                        <p class="absolute text-sm px-2 top-5 -right-2 bg-white border border-black z-10 deleteText">Delete</p>
                      </button>
                    </div>
                  </td>
                  <td class="p-3 border"><p class="w-28 text-sm md:text-base md:w-36">{{ newsArr.news_date | moment("MMM Do, YYYY") }}</p></td>
                  <td class="p-3 border">
                    <article class="w-60 text-sm md:w-80 md:text-base">
                      <a v-on:click="newsEdit(newsArr, 'show')" type="button" class="cursor-pointer textWrap w-60 text-sm md:w-80 md:text-base text-blue-700 hover:text-blue-500"><u>{{ newsArr.news_description }}</u></a>
                    </article>
                  </td>
                  <td class="p-3 border">
                    <div class="w-16">
                      <toggle-button :sync="true" v-on:change="showNews(newsArr, $event)" v-if="!newsArr.show" :labels="{checked: 'shown', unchecked: 'show'}" :width="70" :disabled="false"/>
                      <toggle-button :sync="true" v-if="newsArr.show" :value="true" :labels="{checked: 'shown', unchecked: 'show'}" :width="70" :disabled="true"/>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
      <transition name="fade">
        <div v-if="modalShownOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitShownForm" class="w-full text-center">
                <p class="text-center">Are you sure you want show this on Homepage?</p>
                <div class="w-full flex justify-end">
                  <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded" :disabled="this.validation.showForm">
                    {{ this.validation.showForm ? "Loading..":"Yes" }}
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalClose" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                    No
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div v-if="modalOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-3/4 relative">
            <button class="absolute right-5 top-5" v-on:click="modalClose">
              <svg class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
              </svg>
            </button>
            <div class="flex flex-col items-start p-4">
              <div class="flex items-center w-full ">
                <div class="text-gray-900 font-medium text-lg uppercase font-extrabold">
                  News Information 
                  <button v-if="!editNews" class="p-1 border border-green-500 rounded text-center relative editBtn" v-on:click="editNews = true">
                    <i class="far fa-edit text-sm md:text-base text-green-500"></i>
                    <p class="absolute text-sm p-1 top-10 -right-2 bg-white border border-black z-10 editText">Edit</p>
                  </button>
                </div>
              </div>
              <hr>
              <form v-on:submit.prevent="submitEditForm" class="w-full">
                <div class="w-full">
                  <h1 v-if="!editNews" class="mt-5 mb-2 text-base font-bold">{{ editForm.date | moment("MMM Do, YYYY") }}</h1>
                  <div v-if="editNews" class="flex flex-col mb-4 mt-5">
                    <label class="mb-2 text-sm md:text-base" for="date">News Date</label>
                    <input :class="{'border-red-700':validation.editDateErr}" class="border py-2 px-3 text-grey-darkest" v-model="editForm.date" type="date" name="date" id="date" :max="maxDate">
                    <p v-if="validation.editDateErr" class="mt-1 ml-1 text-sm text-red-700">{{ validation.editDateMsg }}</p>
                  </div>
                  <div class="flex flex-col mb-4 mt-5">
                    <label class="mb-2 text-sm md:text-base mt-2 w-full" for="content">News Content</label>
                    <textarea :class="{'border-red-700':validation.editContentErr}" class="border py-2 px-3 text-grey-darkest resize-x-none" v-model="editForm.content" placeholder="Place your news here." type="text" name="content" id="content" rows="11" maxlength="3500" :readonly="!editNews"></textarea>
                    <p v-if="validation.editContentErr" class="mt-1 ml-1 text-sm text-red-700">{{ validation.editContentMsg }}</p>
                    <p v-if="validation.updated" class="text-base mt-1 text-green-500 text-center">{{ validation.updateMsg }}</p>
                  </div>
                </div>
                <hr>
                <div class="w-full flex justify-end">
                  <button v-if="editNews" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" :disabled="this.validation.updateForm">
                    {{ this.validation.updateForm ? "Loading..":"Update" }}
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalClose" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                    Close
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div v-if="modalDeleteOpen" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
          <div class="bg-white rounded-lg w-1/2 relative">
            <div class="flex flex-col items-start p-4">
              <hr>
              <form v-on:submit.prevent="submitDeleteForm" class="w-full text-center">
                <p v-if="deleteOne" class="text-center">Are you sure you want to delete this News?</p>
                <p v-if="deleteMany" class="text-center">Are you sure you want to delete {{ newsIds.length }} items?</p>
                <div class="w-full flex justify-end">
                  <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded" :disabled="this.validation.deleteForm">
                    {{ this.validation.deleteForm ? "Loading..":"Yes" }}
                  </button>&nbsp;&nbsp;
                  <button v-on:click="modalClose" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                    No
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
<script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
<script>
  import UserLayout from '@/Layouts/UserLayout';

  export default {
    components: {
      UserLayout,
    },
    data(){
      return {
        newsShow: true,
        closeText: false,
        news:[],
        checkedNews: [],
        newsIds: [],
        selected: [],
        allSelected: false,
        modalOpen: false,
        modalDeleteOpen: false,
        maxDate: "",
        editNews: false,
        deleteOne: false,
        deleteMany: false,
        deleteID: '',
        deletedTrue: false,
        deletedMsg: '',
        modalShownOpen: false,
        changeShowID: '', 
        editForm:{
          date: '',
          content: '',
          id: '',
        },
        newsForm: {
          date: '',
          content: '',
        },
        validation:{
          dateErr: false,
          dateMsg: '',
          contentErr: false,
          contentMsg: '',
          submitForm: false,
          submitted: false,
          submitMsg: '',
          editDateErr: false,
          editDateMsg: '',
          editContentErr: false,
          editContentMsg: '',
          updateForm: false,
          updated: false,
          updateMsg: '',
          deleteForm: false,
          showForm: false,
        }
      }
    },
    created(){
      document.title = "BMIID(News)";

      var d = new Date();
      var month = '' + (d.getMonth() + 1);
      var day = '' + d.getDate();
      var year = d.getFullYear();

      if (month.length < 2) 
        month = '0' + month;
      if (day.length < 2) 
        day = '0' + day;
      this.maxDate = year+'-'+month+'-'+day;

      this.getNews();    
    },
    methods: {
      getNews: function(){
        axios.get('/api/all_news').then((response) => {
          this.news = response.data.data;
        })
      },
      showNews: function(news, event){
        if(event.value){
          this.modalShownOpen = true;
          this.changeShowID = news.id;
        }
      },
      submitShownForm: function(){
        this.validation.showForm = true;
        axios.post('/api/news_show', {id: this.changeShowID}).then(response => {
          this.getNews();
          this.deletedMsg = response.data.message;
          this.deletedTrue = true;
          this.validation.showForm = false;
          this.modalShownOpen = false;
        })
      },
      addForm: function(){
        this.validation.submitForm = true;
        this.validation.submitted = false;

        if(this.newsForm.date === ""){
          this.validation.dateErr = true;
          this.validation.dateMsg = "Provide a date for the news";
        }else{
          this.validation.dateErr = false;
          this.validation.dateMsg = "";
        }

        if(this.newsForm.content === ""){
          this.validation.contentErr = true;
          this.validation.contentMsg = "Provide a content for your news";
        }else{
          this.validation.contentErr = false;
          this.validation.contentMsg = "";
        }

        if(this.validation.dateErr === false && this.validation.contentErr === false){
          axios.post('/api/add_news', this.newsForm).then((response) => {
            this.validation.submitted = true;
            this.validation.submitMsg = response.data.message;
            this.validation.submitForm = false;
            this.getNews();
            this.newsForm.reset();
          })
        }else{
          this.validation.submitForm = false;
        }
      },
      submitEditForm: function(){
        this.validation.updateForm = true;
        
        if(this.editForm.date === ""){
          this.validation.editDateErr = true;
          this.validation.editDateMsg = "Provide a date for the news";
        }else{
          this.validation.editDateErr = false;
          this.validation.editDateMsg = "";
        }

        if(this.editForm.content === ""){
          alert("null");
          this.validation.editContentErr = true;
          this.validation.editContentMsg = "Provide a content for your news";
        }else{
          this.validation.editContentErr = false;
          this.validation.editContentMsg = "";
        }

        if(this.validation.editDateErr === false && this.validation.editContentErr === false){
          axios.post('/api/update_news', this.editForm).then((response) => {
            this.validation.updated = true;
            this.validation.updateMsg = response.data.message;
            this.validation.updateForm = false;
          }).then(() => {
            this.getNews();
          })
        }else{
          this.validation.updateForm = false;
        }
      },
      submitDeleteForm:function(){
        this.validation.deleteForm = true;
        var ids;
        var val;
        if(this.deleteOne){
          ids = this.deleteID;
          val = "one";
        }else if(this.deleteMany){
          ids = this.newsIds;
          val = "many";
        }

        axios.post('/api/news_delete', { ids: ids, val: val }).then(response => {
          this.getNews();
          this.deletedMsg = response.data.message;
          this.deletedTrue = true;
          this.validation.deleteForm = false;
          this.modalDeleteOpen = false;
        })
      },
      modalClose: function()
      {
        this.modalShownOpen = false;
        this.modalDeleteOpen = false;
        this.modalOpen = false;
        this.editNews = false;
        this.validation.editDateErr = false;
        this.validation.editContentErr = false;
      },
      newsEdit: function(news, value){
        if(value === "edit"){
          this.editNews = true;
        }
        this.editForm.date = news.news_date;
        this.editForm.content = news.news_description;
        this.editForm.id = news.id;
        this.modalOpen = true;
      },
      newsDelete: function(news, value){
        this.modalDeleteOpen = true;

        if(value === "one"){
          this.deleteOne = true;
          this.deleteMany = false;
          this.deleteID = news.id;
        }else if(value === "many"){
          this.deleteMany = true;
          this.deleteOne = false;
        }
      },
      selectAll: function(){
        this.newsIds = [];
        this.allSelected = !this.allSelected;
        
        if (this.allSelected){
          for(var newsArr in this.news) {
            if(this.news[newsArr].show){

            }else{
              this.newsIds.push(this.news[newsArr].id);
            }
          }
        }
      },
      select: function() {
        this.allSelected = false;
      }
    }
  }
</script>
