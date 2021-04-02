<template>
  <div>
    <!-- Page Heading -->
    <div class="block md:flex">
      <div class="w-80 hidden lg:block h-100 z-40">
        <nav class="fixed h-screen bg-white w-64 shadow-lg">
          <div class="pt-7 pb-5 mx-7 border-b border-gray-500">
            <h1 class="text-xl text-gray-600 uppercase text-center">{{ $page.props.user.role }}</h1>
            <div class="w-full relative text-center mt-7">
              <a class="text-base text-gray-600 text-center cursor-pointer" ref="btnDropdownRef2" v-on:click="toggleDropdown2()" v-click-outside="onClickOutside2">{{ $page.props.user.first_name }}  <i class="fas fa-caret-down"></i></a>
              <div ref="popoverDropdownRef2" class="border absolute bg-white text-base z-40 right-6 py-2 list-none text-left rounded shadow-lg mt-1 w-40" v-bind:class="{hidden: !dropdownPopoverShow2,block: dropdownPopoverShow2,}">
                <inertia-link href="/user/profile" class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap hover:bg-gray-100">
                  <i class="fas fa-user-circle text-gray-500"></i> Profile
                </inertia-link>
                <form @submit.prevent="logout">
                  <button type="submit" class="text-sm py-2 text-left px-4 font-normal block w-full whitespace-no-wrap hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt text-gray-500"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
          <div class="mt-4 mx-7 pb-5 border-b border-gray-500">
            <h1 class="text-sm text-gray-600 uppercase font-extrabold">{{ $page.props.user.role }} Pages</h1>
            <table class="w-full mt-1">
              <tr class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/dashboard')}">
                <td class="py-2 text-center"><inertia-link href="/dashboard"><i class="fas fa-home"></i></inertia-link></td>
                <td class="py-2"><inertia-link href="/dashboard">Dashboard</inertia-link></td>
              </tr>
              <tr v-if="$page.props.user.role == 'Admin'" class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/news')}">
                <td class="py-2 text-center"><inertia-link href="/news"><i class="far fa-newspaper"></i></inertia-link></td>
                <td class="py-2"><inertia-link href="/news">Daily News</inertia-link></td>
              </tr>
              <tr class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/barangay')}">
                <td class="py-2 text-center"><inertia-link href="/barangay"><i class="fas fa-place-of-worship"></i></inertia-link></td>
                <td class="py-2"><inertia-link href="/barangay">Barangay</inertia-link></td>
              </tr>
              <tr v-if="$page.props.user.role == 'Admin'" class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/disease')}">
                <td class="py-2 text-center"><inertia-link href="/disease"><i class="fas fa-virus"></i></inertia-link></td>
                <td class="py-2"><inertia-link href="/disease">Disease</inertia-link></td>
              </tr>
              <tr class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/case')}">
                <td class="py-2 text-center"><inertia-link href="/case"><i class="fas fa-sort-numeric-up-alt"></i></inertia-link></td>
                <td class="py-2"><inertia-link href="/case">Case <span v-if="$page.props.user.role == 'Admin'">({{ pendingCases }})</span>  </inertia-link></td>
              </tr>
              <tr v-if="$page.props.user.role == 'Admin'" class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/accounts')}">
                <td class="py-2 text-center"><inertia-link href="/accounts"><i class="fas fa-users"></i></inertia-link></td>
                <td class="py-2"><inertia-link href="/accounts">Users</inertia-link></td>
              </tr>
            </table>
          </div>
          <div class="mt-14 text-center">
            <h1 class="text-center uppercase text-5xl font-bold">BMIID</h1>
          </div>
        </nav>
      </div>
      <div class="block w-full">
        <!-- Page Heading -->
        <nav class="fixed lg:static bg-gray-100 w-full flex flex-wrap justify-between z-40">
          <div class="w-full flex flex-wrap justify-between border-b-2 border-gray-200">
            <div class="flex p-7">
              <button @click="show=!show" class="block lg:hidden px-3 mr-2 py-2 border rounded text-black border-blue-400 hover:border-blue-900">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
              </button>
              <inernia-link class="font-bold txt-base uppercase" href="/dashboard">
                <slot name="header"></slot>
              </inernia-link>
            </div>
            <ul class="flex flex-row  hidden lg:block">
              <li class="p-7 cursor-pointer relative">
                <a class="ease-linear transition-all duration-150" type="button" ref="btnDropdownRef" v-on:click="toggleDropdown()" v-click-outside="onClickOutside">
                  {{ $page.props.user.first_name }} {{ $page.props.user.last_name }} <i class="fas fa-caret-down"></i>
                </a>
                <div ref="popoverDropdownRef" class="border absolute bg-white text-base z-40 right-6 py-2 list-none text-left rounded shadow-lg mt-1 w-40" v-bind:class="{hidden: !dropdownPopoverShow,block: dropdownPopoverShow,}">
                  <inertia-link href="/user/profile" class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap hover:bg-gray-100">
                    <i class="fas fa-user-circle text-gray-500"></i> Profile
                  </inertia-link>
                  <form @submit.prevent="logout">
                    <button type="submit" class="text-sm py-2 text-left px-4 font-normal block w-full whitespace-no-wrap hover:bg-gray-100">
                      <i class="fas fa-sign-out-alt text-gray-500"></i> Logout
                    </button>
                  </form>
                </div>
              </li>
            </ul>
          </div>
          <div v-if="show" class="w-full h-100" :class="{'block lg:hidden':show}">
            <nav class="pb-10 bg-white w-full shadow-lg z-40">
              <div class="pt-7 pb-5 mx-7 border-b border-gray-500">
                <h1 class="text-xl text-gray-600 uppercase text-center ease-linear transition-all duration-150" type="button">Administrator</h1>
                <div class="w-full relative text-center mt-7">
                  <a class="text-base text-gray-600 text-center cursor-pointer" ref="btnDropdownRef2" v-on:click="toggleDropdown2()" v-click-outside="onClickOutside2">{{ $page.props.user.first_name }}  <i class="fas fa-caret-down"></i></a>
                  <div ref="popoverDropdownRef2" class="border absolute bg-white text-base z-40 right-6 py-2 list-none text-left rounded shadow-lg mt-1 w-40" v-bind:class="{hidden: !dropdownPopoverShow2,block: dropdownPopoverShow2,}">
                    <inertia-link href="/user/profile" class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap hover:bg-gray-100">
                      <i class="fas fa-user-circle text-gray-500"></i> Profile
                    </inertia-link>
                    <form @submit.prevent="logout">
                      <button type="submit" class="text-sm py-2 text-left px-4 font-normal block w-full whitespace-no-wrap hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt text-gray-500"></i> Logout
                      </button>
                    </form>
                  </div>
                </div>
              </div>
              <div class="mt-4 mx-7 pb-5 border-b border-gray-500">
                <h1 class="text-sm text-gray-600 uppercase font-extrabold">Admin Pages</h1>
                <table class="w-full mt-1">
                  <tr class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/dashboard')}">
                    <td class="py-2 text-center"><inertia-link href="/dashboard"><i class="fas fa-home"></i></inertia-link></td>
                    <td class="py-2"><inertia-link href="/dashboard">Dashboard</inertia-link></td>
                  </tr>
                  <tr v-if="$page.props.user.role == 'Admin'" class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/news')}">
                    <td class="py-2 text-center"><inertia-link href="/news"><i class="far fa-newspaper"></i></inertia-link></td>
                    <td class="py-2"><inertia-link href="/news">Daily News</inertia-link></td>
                  </tr>
                  <tr class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/barangay')}">
                    <td class="py-2 text-center"><inertia-link href="/barangay"><i class="fas fa-place-of-worship"></i></inertia-link></td>
                    <td class="py-2"><inertia-link href="/barangay">Barangay</inertia-link></td>
                  </tr>
                  <tr v-if="$page.props.user.role == 'Admin'" class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/disease')}">
                    <td class="py-2 text-center"><inertia-link href="/disease"><i class="fas fa-virus"></i></inertia-link></td>
                    <td class="py-2"><inertia-link href="/disease">Disease</inertia-link></td>
                  </tr>
                  <tr class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/case')}">
                    <td class="py-2 text-center"><inertia-link href="/case"><i class="fas fa-sort-numeric-up-alt"></i></inertia-link></td>
                    <td class="py-2"><inertia-link href="/case">Case</inertia-link></td>
                  </tr>
                  <tr v-if="$page.props.user.role == 'Admin'" class="text-gray-600 text-sm cursor-pointer hover:text-blue-500" :class="{'font-extrabold text-blue-500': $inertia.page.url.startsWith('/accounts')}">
                    <td class="py-2 text-center"><inertia-link href="/accounts"><i class="fas fa-users"></i></inertia-link></td>
                    <td class="py-2"><inertia-link href="/accounts">Users</inertia-link></td>
                  </tr>
                </table>
              </div>
              <div class="mt-14 text-center">
                <h1 class="text-center uppercase text-5xl font-bold">BMIID</h1>
              </div>
            </nav>
          </div>
        </nav>
        <!-- Page Heading -->
        <div class="flex container mx-auto justify-center pb-20">
          <div class="mt-28 lg:mt-6 w-11/12">
            <div class="block w-full">
              <div class="grid grid-rows-4 grid-flow-col gap-8 md:grid-rows-1 justify-center">
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
              <p class="flex justify-center pt-5 font-extrabold">{{ case_name }}</p>
              
              <!-- Page Content -->
              <main>
                <slot></slot>
              </main>
              <!-- Page Content -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import vClickOutside from 'v-click-outside';

  export default {
    props: ['pendingCases'],
    data() {
      return {
        dropdownPopoverShow: false,
        dropdownPopoverShow2: false,
        show: false,
        active: 0,
        deceased: 0,
        recovered: 0,
        total: 0,
        case_name: '',
      };
    },
    directives: {
      clickOutside: vClickOutside.directive,
    },
    created(){
      //window.location.reload();
      if(this.$page.props.user){
         axios.get('/api/count-cases').then((response) => {
          this.active = response.data.active - (response.data.deceased + response.data.recovered);
          this.deceased = response.data.deceased;
          this.recovered = response.data.recovered;
          this.total = response.data.total;
        }).catch((error) => {
           console.log(error);
        }).then(() => {
          axios.get('/api/news').then((response) => {
            this.news_content = response.data.data.news_description;
            this.news_date = response.data.data.news_date;
          }).catch((error) => {
            console.log(error);
          }).then(() => {
            axios.get('/api/disease').then((response) => {
              this.case_name = response.data.data.disease_name;
            }).catch((error) => {
              console.log(error);
            })
          })
        })
      }else{
        window.location.reload();
      }
    },
    mounted(){
      if(!this.$page.props.user){
        window.location.reload();
      }
    },
    methods: {
      logout() {
        this.$inertia.post(route('logout'));
      },

      toggleDropdown: function () {
        if (this.dropdownPopoverShow) {
          this.dropdownPopoverShow = false;
        } else {
          this.dropdownPopoverShow = true;
          createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
            placement: "bottom-start",
          });
        }
      },

      toggleDropdown2: function () {
        if (this.dropdownPopoverShow2) {
          this.dropdownPopoverShow2 = false;
        } else {
          this.dropdownPopoverShow2 = true;
          createPopper(this.$refs.btnDropdownRef2, this.$refs.popoverDropdownRef2, {
            placement: "bottom-start",
          });
        }
      },

      onClickOutside () {
        this.dropdownPopoverShow = false;
      },

      onClickOutside2 () {
        this.dropdownPopoverShow2 = false;
      }
    }
  }
</script>