<template>
    <div v-show="visible" class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-50 bg-opacity-80">
      <div class="bg-white rounded-lg w-1/2 relative">
        <div class="flex flex-col items-start p-4">
          <hr>
          <form v-on:submit.prevent="submitForm" class="w-full text-center">
            <p class="text-center">{{ title }}</p>
            <input ref="input" v-model="value" class="w-full" autofocus>
            <div class="w-full flex justify-end">
              <button type="submit" class="border border-red-700 hover:border-red-500 text-red-700  py-1 px-2 rounded">
                OK
              </button>&nbsp;&nbsp;
              <!-- <button v-on:click="" type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                Cancel
              </button> -->
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
            callback: null,
            value: '',
            visible: false,
        }
    },
    methods: {
        submitForm: function() {
            if (typeof this.callback === 'function') {
                this.callback(this.value);
            }
            this.visible = false;
        },
        modalOpen: function(title, callback) {
            this.title = title;
            this.callback = callback;
            this.value = '';
            this.visible = true;
            
            this.$nextTick(() => {
                if (this.$refs.hasOwnProperty('input')) {
                    this.$refs.input.focus();
                }
            })
        }
    }
  }
</script>

