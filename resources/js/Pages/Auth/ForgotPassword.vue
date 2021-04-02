<template>
    <jet-authentication-card>
        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to create a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div v-else-if="!status && submitted" class="mb-4 font-medium text-sm text-green-600">
            Check your email to reset your password.
        </div>

        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </jet-button>
            </div>
            <div class="flex items-center justify-end mt-2">
                <inertia-link href="/login" class="text-blue-700 cursor-pointer hover:text-blue-500 text-sm">
                    <u>go back to login</u>
                </inertia-link>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors
        },

        props: {
            status: String
        },

        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        created(){
            document.title= "BMIID(Forgot-password)";
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'),{
                    onFinish: () => this.submitted = true
                })
            }
        }
    }
</script>
