<template>

    <!-- forget -->
    <form @submit.prevent="adminForget()" v-if="tab === 'forget'">
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control" autocomplete="new-email" v-model="forgetParam.email">
            <div class="error-report" v-if="error != null && error.email !== undefined"> {{error.email[0]}} </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-theme wpx-150" v-if="!forgetLoading">
                Forget
            </button>
            <button type="button" class="btn btn-theme wpx-150" v-if="forgetLoading">
                <span class="spinner-border border-2 wpx-15 hpx-15"></span>
            </button>
        </div>
        <div class="text-light-gray">
            Already have an account?
            <router-link :to="{name: 'login'}" class="text-light-gray-hover text-decoration-none">
                Sign in
            </router-link>
        </div>
    </form>

    <!-- reset -->
    <form @submit.prevent="adminReset()" v-if="tab === 'reset'">
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control disabled" disabled autocomplete="new-email" v-model="resetParam.email">
            <div class="error-report" v-if="error != null && error.email !== undefined"> {{error.email[0]}} </div>
        </div>
        <div class="form-group mb-3">
            <label for="code" class="form-label">Code</label>
            <input id="code" type="text" name="code" class="form-control" autocomplete="new-code" v-model="resetParam.code">
            <div class="error-report" v-if="error != null && error.code !== undefined"> {{error.code[0]}} </div>
        </div>
        <div class="form-group mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="position-relative">
                <input id="password" :type="passwordFieldType" name="password" class="form-control" autocomplete="new-password" v-model="resetParam.password">
                <div class="me-3 border-0 bg-transparent position-absolute top-50 end-0 translate-middle-y me-2 cursor-pointer" @click="passwordVisibility">
                    <i class="bi bi-eye" v-if="passwordFieldType === 'text'"></i>
                    <i class="bi bi-eye-slash" v-if="passwordFieldType === 'password'"></i>
                </div>
            </div>
            <div class="error-report" v-if="error != null && error.password !== undefined"> {{error.password[0]}} </div>
        </div>
        <div class="form-group mb-3">
            <label for="confirm-password" class="form-label">Confirm Password</label>
            <div class="position-relative">
                <input id="confirm-password" :type="passwordConfirmationFieldType" name="password_confirmation" class="form-control" autocomplete="new-confirm-password" v-model="resetParam.password_confirmation">
                <div class="me-3 border-0 bg-transparent position-absolute top-50 end-0 translate-middle-y me-2 cursor-pointer" @click="passwordConfirmVisibility">
                    <i class="bi bi-eye" v-if="passwordConfirmationFieldType === 'text'"></i>
                    <i class="bi bi-eye-slash" v-if="passwordConfirmationFieldType === 'password'"></i>
                </div>
            </div>
            <div class="error-report" v-if="error != null && error.password_confirmation !== undefined"> {{error.password_confirmation[0]}} </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-theme wpx-90" v-if="!resetLoading">
                Reset
            </button>
            <button type="submit" class="btn btn-theme wpx-90" v-if="resetLoading">
                <span class="spinner-border border-2 wpx-15 hpx-15"></span>
            </button>
        </div>
        <div class="text-light-gray">
            Already have an account?
            <router-link :to="{name: 'login'}" class="text-light-gray-hover text-decoration-none">
                Sign in
            </router-link>
        </div>
    </form>

</template>

<script>

import apiServices from "../../../services/apiServices.js";
import apiRoutes from "../../../services/apiRoutes.js";
import forget from "../../../front/pages/forget.vue";
import axios from "axios";
import {createToaster} from "@meforma/vue-toaster";
const toaster = createToaster({
    position: 'top-right',
});

export default {
    computed: {
        forget() {
            return forget
        }
    },
    data() {
        return {
            password: '',
            passwordFieldType: 'password',
            passwordConfirmationFieldType: 'password',
            tab: 'forget',
            forgetParam: {
                email: '',
            },
            resetParam: {
                email: '',
                code: '',
                password: '',
                password_confirmation: '',
            },
            forgetLoading: false,
            resetLoading: false,
            error: null,
        }
    },
    mounted() {
    },
    methods: {

        /* Function to password visibility */
        passwordVisibility() {
            this.passwordFieldType = this.passwordFieldType === "password" ? "text" : "password";
        },

        /* Function to password confirm visibility */
        passwordConfirmVisibility() {
            this.passwordConfirmationFieldType = this.passwordConfirmationFieldType === "password" ? "text" : "password";
        },

        /* Function to forget api */
        adminForget() {
            this.forgetLoading = true;
            this.error = null;
            axios.post(apiRoutes.adminForget, this.forgetParam, {headers: apiServices.headerContent}).then((response) => {
                if(response?.data?.error){
                    this.forgetLoading = false;
                    this.error = response?.data?.error;
                } else {
                    toaster.info('Reset code send successfully');
                    this.forgetLoading = false;
                    this.tab = 'reset';
                    this.resetParam.email = this.forgetParam.email
                }
            })
        },

        /* Function to reset api */
        adminReset() {
            this.resetLoading = true;
            this.error = null;
            axios.post(apiRoutes.adminReset, this.resetParam, {headers: apiServices.headerContent}).then((response) => {
                if(response?.data?.error){
                    this.resetLoading = false;
                    this.error = response?.data?.error;
                } else {
                    toaster.info('Reset account successfully');
                    this.resetLoading = false;
                    this.$router.push({name: 'login'});
                }
            })
        },

    }
}
</script>
