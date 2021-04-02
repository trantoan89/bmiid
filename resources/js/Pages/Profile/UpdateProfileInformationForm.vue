<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- First Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="first_name" value="First name" />
                <jet-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" autocomplete="first_name" />
                <jet-input-error :message="form.errors.first_name" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="last_name" value="Last name" />
                <jet-input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" autocomplete="last_name" />
                <jet-input-error :message="form.errors.last_name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <jet-input-error :message="form.errors.email" class="mt-2" />
            </div>

            <!-- Contact -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="contact_number" value="Contact Number" />
                <VuePhoneNumberInput v-model="form.contact_number" class="mt-1 block w-full" default-country-code="PH" valid-color="green" :error="contactErr" @update="onUpdate" clearable/>
                <jet-input-error :message="form.errors.contact_number" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import VuePhoneNumberInput from 'vue-phone-number-input';
  import 'vue-phone-number-input/dist/vue-phone-number-input.css';

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            VuePhoneNumberInput
        },

        props: ['user'],

        data() {
            return {
                contactErr: false,
                form: this.$inertia.form({
                    _method: 'PUT',
                    first_name: this.user.first_name,
                    last_name: this.user.last_name,
                    contact_number: this.user.contact_number,
                    email: this.user.email,
                    photo: null,
                }),

                photoPreview: null,
            }
        },

        methods: {
            onUpdate (payload) {
                this.contactErr = !payload.isValid;
            },
            updateProfileInformation() {
                if(this.contactErr === false){
                    if (this.$refs.photo) {
                        this.form.photo = this.$refs.photo.files[0]
                    }

                    this.form.post(route('user-profile-information.update'), {
                        errorBag: 'updateProfileInformation',
                        preserveScroll: true
                    });
                }
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => (this.photoPreview = null),
                });
            },
        },
    }
</script>
