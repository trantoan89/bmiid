<template>
    <div>
        <select v-on:change="timeChange" v-model="year" class="px-7 py-2 ml-2" title="Select year">
          <option v-for="y in caseYears" :key="y" :value="y">{{ y }}</option>
        </select>
        <select v-on:change="timeChange" v-model="month" class="px-7 py-2 ml-2" title="Select month">
          <option value=0 style="color: rgba(0, 0, 0, 0.6)" selected>&lt;all month&gt;</option>
          <option v-for="(m, k) in MONTHS" :key="k" :value="k+1">{{ m }}</option>
        </select>
    </div>
</template>
<script>
  export default {
    props: {
        caseYears: Array,
        defaultYear: Number,
        callback: Function
    },
    data() {
        return {
            year: this.defaultYear,
            month: 0,
            MONTHS: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        }
    },
    methods: {
        timeChange: function() {
            this.callback(this.year, this.month);
        }
    }
  }
</script>
