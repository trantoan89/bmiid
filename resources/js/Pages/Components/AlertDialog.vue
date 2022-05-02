<template>
    <div v-show="visible" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
      <div class="bg-white rounded-lg w-1/2 relative">
        <div class="flex flex-col items-start p-4">
          <form v-on:submit.prevent="modalClose" class="w-full text-center">
            <h3 class="text-center" v-html="title"></h3>
            <br>
            <p class="text-center" v-html="body"></p>
            <div class="w-full flex justify-end">
              <button ref="ADbtnOK" v-on:keyup.escape="modalClose" type="submit" class="border border-blue-700 hover:border-blue-500 text-blue-700 py-1 px-2 rounded">
                OK
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    data() {
        return {
            title: '',
            body: '',
            visible: false,
        }
    },
    methods: {
        modalClose: function() {
            this.visible = false;
        },
        modalOpen: function(title, body) {
            this.title = title;
            this.body = body;
            this.visible = true;
            
            this.$nextTick(() => {
                if (this.$refs.hasOwnProperty('ADbtnOK')) {
                    this.$refs.ADbtnOK.focus();
                }
            })
        }
    }
  }
</script>

