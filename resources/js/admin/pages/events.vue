<template>

    <!-- Breadcrumb -->
    <div class="d-sm-flex justify-content-between align-items-center">
        <breadcrumb :items="BreadcrumbItems" moduleName="Events"/>
    </div>

    <!-- Search and new -->
    <div class="row justify-content-between">
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="position-relative">
                <input type="text" name="keyword" class="form-control ps-5" required autocomplete="new-search"
                       placeholder="Search here" v-model="listData.keyword" @keyup="SearchData()">
                <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="bi bi-search"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 mb-3 d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-light border-0 mx-2" @click="deleteEventModalOpen()" v-if="tableData.length > 0 && loading === false && selected.length > 0">
                <i class="bi bi-trash2 text-danger"></i>
            </button>
            <newBtn @click="manageEventModalOpen(null)"/>
        </div>
    </div>

    <!-- Table list data -->
    <div class="card rounded-3 border-0 shadow" v-if="!loading && tableData.length > 0">
        <div class="card-body card-list scrollbar">

            <table class="table">
                <thead>
                    <tr>
                        <th class="checkbox">
                            <input type="checkbox" class="form-checkbox" :checked="tableData.length > 0 && tableData.length === selected.length" @change="toggleCheckAll($event)">
                        </th>
                        <th class="default-width">
                            Name.
                        </th>
                        <th class="default-width">
                            Date.
                        </th>
                        <th class="default-width">
                            Start time.
                        </th>
                        <th class="default-width">
                            End time.
                        </th>
                        <th class="default-width">
                            guest.
                        </th>
                        <th class="default-width">
                            location.
                        </th>
                        <th class="action">
                            Action.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="each in tableData">
                        <td class="checkbox">
                            <input type="checkbox" class="form-checkbox" :checked="CheckIfChecked(each.id)" @change="toggleCheck($event,each.id)">
                        </td>
                        <td class="default-width">
                            {{each.name}}
                        </td>
                        <td class="default-width">
                            {{each.date}}
                        </td>
                        <td class="default-width">
                            {{each.start_time}}
                        </td>
                        <td class="default-width">
                            {{each.end_time}}
                        </td>
                        <td class="default-width">
                            {{each.guest}}
                        </td>
                        <td class="default-width">
                            {{each.location}}
                        </td>
                        <td class="action">
                            <div class="dropdown">
                                <button type="button" class="btn border-0 p-0 btn-icon" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end p-2 mt-1 overflow-hidden rounded-3 border">
                                    <li class="mb-2">
                                        <button type="button" class="dropdown-item rounded-3" @click="manageEventModalOpen(each.id)">
                                            Edit
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item rounded-3" @click="deleteEventModalOpen(each.id)">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <!-- Preloader of table list data -->
    <preloader v-if="loading"/>

    <!-- No data founded of table list data -->
    <noDataFounded :text="'course'" :newModalFunction="manageEventModalOpen" v-if="!loading  && tableData.length === 0"/>

    <!-- Pagination of list data -->
    <pagination :total_pages="total_pages" :current_page="current_page" :buttons="buttons"
                @page-change="handlePageChange" v-if="!loading && tableData.length > 0"/>

    <!-- Modal of manage event -->
    <div class="modal fade" id="manageEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="manageEvent()" class="modal-content px-3 py-2 rounded-3 border-0">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                        <span v-if="this.formData.id === undefined"> Create </span>
                        <span v-if="this.formData.id !== undefined"> Edit </span>
                        event
                    </h1>
                    <button type="button" class="btn-close shadow-none" @click="manageEventModalClose"></button>
                </div>
                <div class="modal-body border-0">

                    <div class="form-group mb-3">
                        <label for="upload-image" v-if="this.formData.avatar === null && !uploadLoading"
                               class="form-label hpx-150 d-flex justify-content-center align-items-center flex-column bg-white text-center cursor-pointer border">
                            <input id="upload-image" type="file" name="update-image" hidden="hidden" @change="uploadFile($event)">
                            <i class="bi bi-cloud-arrow-down-fill fs-1"></i>
                            Click to upload Image
                        </label>
                        <div class="position-relative" v-if="this.formData.avatar != null && !uploadLoading">
                            <img :src="formData.avatar" class="img-fluid object-fit-cover w-100 hpx-150 rounded-4" alt="uploaded image">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-danger wpx-35 hpx-35 d-flex justify-content-center align-items-center rounded-circle p-0" @click="deleteFile">
                                    <i class="bi bi-trash2"></i>
                                </button>
                            </div>
                        </div>
                        <div class="position-relative" v-if="uploadLoading">
                            <div class="w-100 hpx-150 rounded-4 bg-secondary-subtle"></div>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="event-name" class="form-label"> Name </label>
                        <input id="event-name" v-model="formData.name" type="text" name="event-name"
                               class="form-control" required autocomplete="new-event-name">
                        <div class="error-report" v-if="error != null && error.student_enroll_capacity !== undefined"> {{error.name[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="event-date" class="form-label"> Date </label>
                        <input id="event-date" v-model="formData.date" type="text" name="event-date"
                               class="form-control" required autocomplete="new-event-date">
                        <div class="error-report" v-if="error != null && error.date !== undefined"> {{error.date[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="event-start-time" class="form-label"> Start time </label>
                        <input id="event-start-time" v-model="formData.start_time" type="text"
                               name="event-start-time" class="form-control" required
                               autocomplete="new-event-start-time">
                        <div class="error-report" v-if="error != null && error.start_time !== undefined"> {{error.start_time[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="event-end-time" class="form-label"> End time </label>
                        <input id="event-end-time" v-model="formData.end_time" type="text" name="event-end-time"
                               class="form-control" required autocomplete="new-event-end-time">
                        <div class="error-report" v-if="error != null && error.end_time !== undefined"> {{error.end_time[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="guest" class="form-label"> Guest name </label>
                        <input id="guest" type="text" name="guest" v-model="formData.guest" class="form-control" required autocomplete="new-event-guest-name">
                        <div class="error-report" v-if="error != null && error.guest !== undefined"> {{error.guest[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="location" class="form-label"> Location </label>
                        <input id="location" type="text" name="location" v-model="formData.location" class="form-control" required autocomplete="new-event-location">
                        <div class="error-report" v-if="error != null && error.location !== undefined"> {{error.location[0]}} </div>
                    </div>

                    <div class="form-group">
                        <label for="event-description" class="form-label"> Description </label>
                        <textarea id="event-description" class="form-textarea" name="event-description"
                                  v-model="formData.description" cols="30" rows="5" required
                                  autocomplete="new-event-description"></textarea>
                        <div class="error-report" v-if="error != null && error.description !== undefined"> {{error.description[0]}} </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary wpx-110" @click="manageEventModalClose">
                        Close
                    </button>
                    <button type="submit" class="btn btn-theme wpx-110" v-if="!manageEventLoading">
                        <span v-if="this.formData.id === undefined"> Save </span>
                        <span v-if="this.formData.id !== undefined"> Update </span>
                    </button>
                    <button type="button" class="btn btn-theme wpx-110" v-if="manageEventLoading">
                        <span class="spinner-border border-2 wpx-15 hpx-15"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal of delete event -->
    <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="eventDelete()" class="modal-content rounded-3 border-0 py-2 px-3">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                        Delete event
                    </h1>
                    <button type="button" class="btn-close shadow-none" @click="deleteEventModalClose"></button>
                </div>
                <div class="modal-body border-0 text-center">

                    <div class="text-center">
                        <i class="bi bi-trash2 fs-1 text-danger"></i>
                    </div>

                    <div class="text-center">
                        Are you sure?
                    </div>

                </div>
                <div class="modal-footer border-0 d-flex justify-content-between align-items-center">
                    <div class="col-5">
                        <button type="button" class="btn btn-secondary rounded-3 w-100" @click="deleteEventModalClose">
                            Close
                        </button>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-theme rounded-3 w-100" v-if="!deleteEventLoading">
                            Confirm
                        </button>
                        <button type="button" class="btn btn-theme rounded-3 w-100" v-if="deleteEventLoading">
                            <span class="spinner-border border-2 wpx-15 hpx-15"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
import search from "../components/search.vue";
import preloader from "../components/preloader.vue";
import noDataFounded from "../components/no-data-founded.vue";
import pagination from "../components/pagination.vue";
import newBtn from "../components/new.vue";
import flatpickr from "flatpickr";
import tableContent from "../components/table.vue";
import breadcrumb from '../components/breadcrumb.vue';
import apiServices from "../../services/apiServices.js";
import apiRoutes from "../../services/apiRoutes.js";

export default {
    components: {
        // Component properties
        search, preloader, noDataFounded, pagination, newBtn, tableContent, breadcrumb
    },
    data() {
        return {
            // Data properties
            BreadcrumbItems: [
                { title: 'Dashboard', route: 'dashboard' },
                { title: 'Events', route: 'events' },
            ],
            formData: {
                name: '',
                date: 'today',
                start_time: '',
                end_time: '',
                description: '',
                guest: '',
                location: '',
                avatar: null,
            },
            loading: false,
            deleteEventParam: {
                ids: []
            },
            tableData: [],
            listData: {
                keyword: '',
                limit: 10,
                page: 1,
            },
            total_pages: 0,
            current_page: 1,
            buttons: [],
            last_page: 0,
            searchTimeOut: null,
            responseData: null,
            selected: [],
            manageEventLoading: false,
            error: null,
            deleteEventLoading: false,
            uploadLoading: false,
            uploadedImage: null,
            uploadedImageId: null,
        }
    },
    mounted() {
        this.eventList();
        this.flatpickrConfigDate();
        this.flatpickrConfigStartTime();
        this.flatpickrConfigEndTime();
    },
    methods: {

        // Function of handle page change
        handlePageChange(page) {
            this.current_page = page;
            this.eventList();
        },

        // Function of toggle check all
        toggleCheckAll(e) {
            if (e.target.checked) {
                this.tableData.forEach((v) => {
                    this.selected.push(v.id);
                });
            } else {
                this.selected = [];
            }
        },

        // Function of toggle check
        toggleCheck(e, id) {
            if (e.target.checked) {
                this.selected.push(id);
            } else {
                let index = this.selected.indexOf(id);
                this.selected.splice(index, 1);
            }
        },

        // Function of check if checked
        CheckIfChecked(id) {
            return this.selected.map(function (id) {
                return id
            }).indexOf(id) > -1;
        },

        // Function of manage event modal open
        manageEventModalOpen(data = null) {
            apiServices.clearErrorHandler()
            if(data !== null) {
                this.eventSingle(data);
            }else {
                this.formData = {
                    name: '',
                    date: '',
                    start_time: '',
                    end_time: '',
                    description: '',
                    guest: '',
                    location: '',
                    avatar: null,
                }
            }
            const myModal = new bootstrap.Modal("#manageEventModal", {keyboard: false});
            myModal.show();
        },

        // Function of manage event modal close
        manageEventModalClose() {
            let myModalEl = document.getElementById('manageEventModal');
            let modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        },

        // Function of delete event modal open
        deleteEventModalOpen(id) {
            this.deleteEventParam.ids.push(id)
            const myModal = new bootstrap.Modal("#deleteEventModal", {keyboard: false});
            myModal.show();
        },

        // Function of delete event modal close
        deleteEventModalClose() {
            this.selected = [];
            this.current_page = 1;
            this.formData = {
                name: '',
                date: '',
                start_time: '',
                end_time: '',
                description: '',
                guest: '',
                location: '',
                avatar: null,
            }
            let myModalEl = document.getElementById('deleteEventModal');
            let modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        },

        // Function of event date flatpickr config
        flatpickrConfigDate() {
            flatpickr("#event-date", {
                altFormat: 'j M Y',
                altInput: true,
                minDate: this.formData.date,
                dateFormat: 'Y-m-d',
                disableMobile: true,
            })
        },

        // Function of event start time flatpickr config
        flatpickrConfigStartTime() {
            flatpickr("#event-start-time", {
                enableTime: true,
                noCalendar: true,
                defaultDate: "",
                disableMobile: true,
                dateFormat: "h:i K",
                time_24hr: false,
            })
        },

        // Function of event end time flatpickr config
        flatpickrConfigEndTime() {
            flatpickr("#event-end-time", {
                enableTime: true,
                noCalendar: true,
                defaultDate: "",
                disableMobile: true,
                dateFormat: "h:i K",
                time_24hr: false,
            })
        },

        // Function of event list api callback
        eventList() {
            this.loading = true;
            this.listData.page = this.current_page;
            apiServices.GET(apiRoutes.eventList, this.listData, (res) => {
                this.loading = false;
                if (res.message) {
                    this.tableData = res.data.data;
                    this.last_page = res?.data?.last_page
                    this.total_pages = res?.data?.total < res?.data?.per_page ? 1 : Math.ceil((res?.data?.total / res?.data?.per_page));
                    this.current_page = res?.data?.current_page;
                    this.buttons = [...Array(this.total_pages).keys()].map((i) => i + 1);
                } else {
                    apiServices.clearErrorHandler(res.error)
                }
            })
        },

        // Function of event search data
        SearchData() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.eventList();
            }, 800);
        },

        // Function of event manage of create and update api callback
        manageEvent() {
            if(this.formData.id === undefined) {
                this.eventCreate()
            }else {
                this.eventUpdate()
            }
        },

        // Function of event create api callback
        eventCreate() {
            this.manageEventLoading = true;
            apiServices.POST(apiRoutes.eventCreate, this.formData, (res) => {
                this.manageEventLoading = false;
                if(res.message) {
                    this.formData = {
                        name: '',
                        date: '',
                        start_time: '',
                        end_time: '',
                        description: '',
                        guest: '',
                        location: '',
                        avatar: null,
                    }
                    this.$toast.success(res.message, { position: "top-right" } );
                    this.manageEventModalClose();
                    this.eventList();
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of event update api callback
        eventUpdate() {
            this.manageEventLoading = true;
            apiServices.PATCH(apiRoutes.eventUpdate, this.formData, (res) => {
                this.manageEventLoading = false;
                if(res.message) {
                    this.formData = {
                        name: '',
                        date: '',
                        start_time: '',
                        end_time: '',
                        description: '',
                        guest: '',
                        location: '',
                        avatar: null,
                    }
                    this.$toast.success(res.message, { position: "top-right" } );
                    this.manageEventModalClose();
                    this.eventList();
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of event single api callback
        eventSingle(data) {
            apiServices.PUT(apiRoutes.eventSingle, { id: data }, (res) => {
                if (res.message) {
                    this.formData = res.data;
                } else {
                    this.error = res.errors;
                }
            });
        },

        // Function of event delete api callback
        eventDelete() {
            this.selected.forEach((v) => {
                this.deleteEventParam.ids.push(v);
            })
            this.deleteEventLoading = true;
            apiServices.DELETE(apiRoutes.eventDelete, this.deleteEventParam, (res) => {
                this.deleteEventLoading = false;
                if(res.message) {
                    this.deleteEventModalClose();
                    this.eventList();
                    this.$toast.success(res.message, { position: "top-right" } );
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of upload file
        uploadFile(event) {
            this.uploadLoading = true;
            let file = event.target.files[0];
            let formData = new FormData();
            formData.append("file", file)
            formData.append("media_type", 1);
            apiServices.UPLOAD(apiRoutes.fileUpload, formData, (res) => {
                event.target.value = ''
                this.uploadLoading = false
                if (res) {
                    this.uploadedImageId = res?.data?.id
                    this.formData.avatar = res?.data?.full_file_path
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of delete file
        deleteFile() {
            this.uploadLoading = true;
            apiServices.DELETE(apiRoutes.fileDelete+`/${this.uploadedImageId}`, {}, (res) => {
                if(res) {
                    this.uploadLoading = false;
                    this.formData.avatar = null;
                } else {
                    this.error = res.errors
                }
            });
        },

    }
}

</script>
