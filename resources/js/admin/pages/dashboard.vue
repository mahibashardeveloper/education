<template>

    <!-- breadcrumb -->
    <div class="d-sm-flex justify-content-between align-items-center">
        <breadcrumb moduleName="Dashboard"/>
    </div>

    <!-- dashboard -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4">

        <!-- event -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'events'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ event_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-calendar4-event fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary event
                    </div>
                </div>
            </router-link>

        </div>

        <!-- professor -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'professors'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ professor_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-person fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary professor
                    </div>
                </div>
            </router-link>

        </div>

        <!-- student -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'students'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ student_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-people fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary student
                    </div>
                </div>
            </router-link>
        </div>

        <!-- course -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'courses'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ course_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-mortarboard fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary course
                    </div>
                </div>
            </router-link>
        </div>

        <!-- library -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'library'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ library_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-journal-bookmark fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary library
                    </div>
                </div>
            </router-link>
        </div>

        <!-- department -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'departments'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ department_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-building fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary department
                    </div>
                </div>
            </router-link>

        </div>

        <!-- stuff -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'stuff'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ stuff_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-people fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary stuff
                    </div>
                </div>
            </router-link>

        </div>

        <!-- holiday -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'holiday'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ holiday_count }}
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-gift fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary holiday
                    </div>
                </div>
            </router-link>

        </div>

        <!-- fees -->
        <div class="mb-4">

            <div
                class="bg-white shadow hpx-230 rounded-4 d-flex justify-content-center align-items-center cursor-loading"
                v-if="loading">
                <span class="spinner-border spinner-border" aria-hidden="true"></span>
            </div>

            <router-link :to="{name: 'fees'}" class="text-decoration-none">
                <div
                    class="bg-white shadow text-center hpx-230 rounded-4 d-flex justify-content-center align-items-center flex-column"
                    v-if="!loading">
                    <div class="fw-bold fs-5 mb-3 text-light-gray">
                        {{ totalFeesAmount }} tk
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-currency-dollar fs-3 text-theme"></i>
                    </div>
                    <div class="text-light-gray">
                        Summary fees
                    </div>
                </div>
            </router-link>

        </div>

    </div>

</template>

<script>
import breadcrumb from '../components/breadcrumb.vue';
import apiServices from '../../services/apiServices.js';
import apiRoutes from '../../services/apiRoutes.js';
export default {

    components: {
        breadcrumb
    },

    data() {

        return {
            event_count: '0',
            professor_count: '0',
            student_count: '0',
            course_count: '0',
            library_count: '0',
            department_count: '0',
            stuff_count: '0',
            holiday_count: '0',
            totalFeesAmount: '0',
            loading: false,
        }

    },

    mounted() {
        this.getEventCount()
        this.getProfessorCount()
        this.getStudentCount()
        this.getCourseCount()
        this.getLibraryCount()
        this.getDepartmentCount()
        this.getStuffCount()
        this.getHolidayCount()
        this.getFeesAmount()
    },

    methods: {

        /* Function to count event */
        getEventCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.eventList, '', (res) => {
                this.loading = false;
                this.event_count = res?.data?.total
            })
        },

        /* Function to count professor */
        getProfessorCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.professorList, '', (res) => {
                this.loading = false;
                this.professor_count = res?.data?.total
            })
        },

        /* Function to count student */
        getStudentCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.studentList, '', (res) => {
                this.loading = false;
                this.student_count = res?.data?.total
            })
        },

        /* Function to count course */
        getCourseCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.courseList, '', (res) => {
                this.loading = false;
                this.course_count = res?.data?.total
            })
        },

        /* Function to count library */
        getLibraryCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.libraryAssetList, '', (res) => {
                this.loading = false;
                this.library_count = res?.data?.total
            })
        },

        /* Function to count department */
        getDepartmentCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.departmentList, '', (res) => {
                this.loading = false;
                this.department_count = res?.data?.total
            })
        },

        /* Function to count stuff */
        getStuffCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.stuffList, '', (res) => {
                this.loading = false;
                this.stuff_count = res?.data?.total
            })
        },

        /* Function to count holiday */
        getHolidayCount() {
            this.loading = true;
            apiServices.GET(apiRoutes.holidayList, '', (res) => {
                this.loading = false;
                this.holiday_count = res?.data?.total
            })
        },

        /* Function to fees */
        getFeesAmount() {
            this.loading = true;
            apiServices.GET(apiRoutes.feesList, '', (res) => {
                this.loading = false;
                const fees_amount = res?.data?.data;
                if (fees_amount && fees_amount.length > 0) {
                    this.totalFeesAmount = fees_amount.reduce((total, item) => {
                        return total + parseFloat(item.fees_amount || 0);
                    }, 0);
                }
            });
        }


    }

}

</script>
