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
          <p class="text-sm md:text-base">BMIID Contact</p>
          <article class="mt-9 text-sm md:text-base">
            We are happy to receive a message from you. Feel free to send us a message.
            <br><br>
            The Baguio Map-Integration of Infectious Disease contact form. Provide all information below. 
          </article>
        </div>
        <div class="mt-16 w-9/12 md:w-11/12 mx-auto">
          <div class="block md:flex w-full justify-center mx-auto">
            <div class="w-4/5 px-7 mt-7 md:w-1/3 order-first md:order-last">
              <p class="text-base text-red-500 p-2">*Email must contain @ sign.</p>
              <p class="text-base text-red-500 p-2">*Provide a valid email so we can get in touch with you.</p>
              <p class="text-base text-red-500 p-2">*Maximum character for email is 50.</p>
              <p class="text-base text-red-500 p-2">*Maximum character for name is 50.</p>
              <p class="text-base text-red-500 p-2">*Maximum character for message is 500.</p>
              <p class="text-base text-red-500 p-2">*All fields in contact form is required.</p>
            </div>
            <div class="w-4/5 px-7 md:w-1/3 order-last md:order-first">
              <form class="w-full">
                <div class="flex flex-col mb-4">
                  <label class="mb-2 text-base" for="email">Email</label>
                  <input class="border py-2 px-3 text-grey-darkest" type="email" name="email" id="email" maxlength="50">
                  <!-- <p class="text-sm text-red-700">Provide an email</p> -->
                </div>
                <div class="flex flex-col mb-4">
                  <label class="mb-2 text-base" for="name">Name</label>
                  <input class="border py-2 px-3 text-grey-darkest " type="text" name="name" id="name" maxlength="50">
                  <!-- <p class="text-sm text-red-700">Provide a name</p> -->
                </div>
                <div class="flex flex-col mb-4">
                  <label class="mb-2 text-base" for="name">Message</label>
                  <textarea class="border py-2 px-3 text-grey-darkest resize-none" type="text" name="message" id="message" rows="8" maxlength="500"></textarea>
                  <!-- <p class="text-sm text-red-700">Provide a message</p> -->
                </div>
                <div class="flex flex-col">
                  <button class="bg-white w-full border-2 py-2 border-blue-500 text-blue-500 hover:border-blue-700 hover:text-blue-700">
                    Send Message
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="mt-28 block w-9/12 md:w-11/12 mx-auto">
          <p>Reminder:</p>
          <p class="text-sm pt-3">We will respond to you when we receive your message. Kindly check your email within 48 hours.</p>
          <p class="text-sm pt-1">The contact form is exclusively for disease matter only. We will not respond to none related messages. Thank you.</p>
        </div>
      </div>
    </div>
    <footers></footers>
  </div>  
</template>

<script>
  import Navbar from '@/Includes/navbar';
  import Footer from '@/Includes/footer';

  export default {
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
    },
    components: {
       'navbar': Navbar,
       'footers': Footer
    },
    created (){
      document.title = "BMIID(Contact)";
    }
  }
</script>
