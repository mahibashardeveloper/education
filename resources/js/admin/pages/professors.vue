<template>

    <!-- Breadcrumb -->
    <div class="d-sm-flex justify-content-between align-items-center">
        <breadcrumb :items="BreadcrumbItems" moduleName="Professors"/>
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
            <button type="button" class="btn btn-light border-0 mx-2" @click="deleteProfessorModalOpen()" v-if="tableData.length > 0 && loading === false && selected.length > 0">
                <i class="bi bi-trash2 text-danger"></i>
            </button>
            <newBtn @click="manageProfessorModalOpen(null)"/>
        </div>
    </div>

    <!-- Table data list -->
    <div class="card rounded-3 border-0 shadow" v-if="!loading  && tableData.length > 0">
        <div class="card-body card-list scrollbar">

            <table class="table">
                <thead>
                    <tr>
                        <th class="checkbox">
                            <input type="checkbox" class="form-checkbox" :checked="tableData.length > 0 && tableData.length === selected.length" @change="toggleCheckAll($event)">
                        </th>
                        <th class="default-width">
                            Name
                        </th>
                        <th class="default-width">
                            Department
                        </th>
                        <th class="default-width">
                            Education
                        </th>
                        <th class="default-width">
                            Email
                        </th>
                        <th class="default-width">
                            Phone
                        </th>
                        <th class="default-width">
                            Joining date
                        </th>
                        <th class="action">
                            Action
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
                            <span v-if="each.department_info != null">
                                {{ each.department_info.name }}
                            </span>
                        </td>
                        <td class="default-width">
                            {{each.educational_qualification}}
                        </td>
                        <td class="default-width">
                            {{each.email}}
                        </td>
                        <td class="default-width">
                            {{each.phone}}
                        </td>
                        <td class="default-width">
                            {{each.joining_date}}
                        </td>
                        <td class="action">
                            <div class="dropdown">
                                <button type="button" class="btn border-0 p-0 btn-icon" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end p-2 mt-1 overflow-hidden rounded-3 border">
                                    <li class="mb-2">
                                        <button type="button" class="dropdown-item rounded-3" @click="manageProfessorModalOpen(each.id)">
                                            Edit
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item rounded-3" @click="deleteProfessorModalOpen(each.id)">
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

    <!-- Preloader of table data list -->
    <preloader v-if="loading"/>

    <!-- No data founded as empty screen for table data list -->
    <noDataFounded :text="'professor'" :newModalFunction="manageProfessorModalOpen" v-if="!loading  && tableData.length === 0"/>

    <!-- Pagination of list data -->
    <pagination :total_pages="total_pages" :current_page="current_page" :buttons="buttons"
                @page-change="handlePageChange" v-if="!loading && tableData.length > 0"/>

    <!-- Modal of manage professor -->
    <div class="modal fade" id="manageProfessorModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="manageProfessor()" class="modal-content px-3 py-2 rounded-3 border-0">
                <div class="modal-header border-0 d-flex justify-content-between">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                        <span v-if="this.formData.id === undefined"> Create </span>
                        <span v-if="this.formData.id !== undefined"> Edit </span>
                        Professor
                    </h1>
                    <button type="button" class="btn-close shadow-none" @click="manageProfessorModalClose"></button>
                </div>
                <div class="modal-body border-0">

                    <div class="d-flex justify-content-center">
                        <div class="form-group">

                            <!-- Avatar Upload -->
                            <label for="upload-image" v-if="this.formData.avatar === null && !uploadLoading"
                                   class="form-label wpx-170 hpx-170 rounded-circle d-flex justify-content-center align-items-center flex-column bg-white text-center cursor-pointer border">
                                <input id="upload-image" type="file" name="update-image" hidden="hidden" @change="uploadFile($event)">
                                <i class="bi bi-person-plus text-success text-opacity-75 fs-1"></i>
                            </label>

                            <!-- Avatar uploaded -->
                            <div class="position-relative" v-if="this.formData.avatar != null && !uploadLoading">
                                <img :src="formData.avatar" class="img-fluid object-fit-cover wpx-170 hpx-170 rounded-circle" alt="uploaded image">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-danger wpx-35 hpx-35 d-flex justify-content-center align-items-center rounded-circle p-0" @click="deleteFile">
                                        <i class="bi bi-trash2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Avatar preloader -->
                            <div class="position-relative" v-if="uploadLoading">
                                <div class="wpx-170 hpx-170 rounded-4 bg-secondary-subtle"></div>
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
                                    <div class="spinner-border text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="text" v-model="formData.name" name="name" class="form-control"
                               autocomplete="new-name">
                        <div class="error-report" v-if="error != null && error.name !== undefined"> {{error.name[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" v-model="formData.email" name="email" class="form-control"
                               autocomplete="new-email">
                        <div class="error-report" v-if="error != null && error.email !== undefined"> {{error.email[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="education" class="form-label">Education qualification</label>
                        <input id="education" type="text" v-model="formData.educational_qualification" name="education"
                               class="form-control" autocomplete="new-education">
                        <div class="error-report" v-if="error != null && error.educational_qualification !== undefined"> {{error.educational_qualification[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="department" class="form-label">Select Department</label>
                        <select name="department_id" id="department_id" class="form-select" v-model="formData.department_id" autocomplete="new-department-id">
                            <option value="select-department-option">Select department option</option>
                            <option v-for="each in departmentDataList" :value="each.id">
                                {{ each.name }}
                            </option>
                        </select>
                        <div class="error-report" v-if="error != null && error.department_id !== undefined"> {{error.department_id[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone-number" class="form-label">Phone number</label>
                        <input id="phone-number" type="text" v-model="formData.phone" name="phone-number"
                               class="form-control" autocomplete="new-phone-number">
                        <div class="error-report" v-if="error != null && error.phone !== undefined"> {{error.phone[0]}} </div>
                    </div>

                    <div class="form-group" v-if="this.formData.id === undefined">
                        <label for="joining-date" class="form-label">Joining date</label>
                        <input id="joining-date" type="text" v-model="formData.joining_date" name="joining-date"
                               class="form-control" autocomplete="new-joining-date">
                        <div class="error-report" v-if="error != null && error.joining_date !== undefined"> {{error.joining_date[0]}} </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary wpx-110 me-2" @click="manageProfessorModalClose">
                        Close
                    </button>
                    <button type="submit" class="btn btn-theme wpx-110" v-if="!manageProfessorLoading">
                        <span v-if="this.formData.id === undefined"> Save </span>
                        <span v-if="this.formData.id !== undefined"> Update </span>
                    </button>
                    <button type="button" class="btn btn-theme wpx-110" v-if="manageProfessorLoading">
                        <span class="spinner-border border-2 wpx-15 hpx-15"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal of delete professor -->
    <div class="modal fade" id="deleteProfessorModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="professorDelete()" class="modal-content rounded-3 border-0 py-2 px-3">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                        Delete professor
                    </h1>
                    <button type="button" class="btn-close shadow-none" @click="deleteProfessorModalClose"></button>
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
                        <button type="button" class="btn btn-secondary rounded-3 w-100"
                                @click="deleteProfessorModalClose">
                            Close
                        </button>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-theme w-100" v-if="!deleteProfessorLoading">
                            Confirm
                        </button>
                        <button type="button" class="btn btn-theme w-100" v-if="deleteProfessorLoading">
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
import breadcrumb from "../components/breadcrumb.vue";
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
                { title: 'Professors', route: 'professors' },
            ],
            formData: {
                name: '',
                email: '',
                phone: '',
                department_id: 'select-department-option',
                educational_qualification: '',
                joining_date: '',
                avatar: null,
            },
            departmentDataList: [],
            loading: false,
            deleteProfessorParam: {
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
            manageProfessorLoading: false,
            error: null,
            deleteProfessorLoading: false,
            uploadLoading: false,
            uploadedImage: null,
            uploadedImageId: null,
        }
    },
    mounted() {
        this.professorList();
        this.flatpickrConfigDate();
    },
    methods: {

        // Function of handle page change
        handlePageChange(page) {
            this.current_page = page;
            this.professorList();
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

        // Function of manage professor modal open
        manageProfessorModalOpen(data = null) {
            this.getDepartment();
            apiServices.clearErrorHandler()
            if(data !== null) {
                this.professorSingle(data);
            }else {
                this.formData = {
                    name: '',
                    email: '',
                    phone: '',
                    department_id: 'select-department-option',
                    educational_qualification: '',
                    joining_date: '',
                    avatar: null,
                }
            }
            const myModal = new bootstrap.Modal("#manageProfessorModal", {keyboard: false});
            myModal.show();
        },

        // Function of manage professor modal close
        manageProfessorModalClose() {
            let myModalEl = document.getElementById('manageProfessorModal');
            let modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        },

        // Function of delete professor modal open
        deleteProfessorModalOpen(id) {
            this.deleteProfessorParam.ids.push(id)
            const myModal = new bootstrap.Modal("#deleteProfessorModal", {keyboard: false});
            myModal.show();
        },

        // Function of delete professor modal close
        deleteProfessorModalClose() {
            this.selected = [];
            this.current_page = 1;
            this.formData = {
                name: '',
                email: '',
                phone: '',
                department_id: 'select-department-option',
                educational_qualification: '',
                joining_date: '',
                avatar: null,
            }
            let myModalEl = document.getElementById('deleteProfessorModal');
            let modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        },

        // Function of professor joining date
        flatpickrConfigDate() {
            flatpickr("#joining-date", {
                altFormat: 'j M Y',
                altInput: true,
                minDate: "today",
                dateFormat: 'Y-m-d',
                disableMobile: true,
            })
        },

        // Function of professor list api callback
        professorList() {
            this.loading = true;
            this.listData.page = this.current_page;
            apiServices.GET(apiRoutes.professorList, this.listData, (res) => {
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

        // Function of professor search data
        SearchData() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.professorList();
            }, 800);
        },

        // Function of professor manage as create and update api callback
        manageProfessor() {
            if(this.formData.id === undefined) {
                this.professorCreate()
            }else {
                this.professorUpdate()
            }
        },

        // Function of professor create api callback
        professorCreate() {
            this.manageProfessorLoading = true;
            apiServices.POST(apiRoutes.professorCreate, this.formData, (res) => {
                this.manageProfessorLoading = false;
                if(res.message) {
                    this.formData = {
                        name: '',
                        email: '',
                        phone: '',
                        department_id: 'select-department-option',
                        educational_qualification: '',
                        joining_date: '',
                        avatar: null,
                    }
                    this.$toast.success(res.message, { position: "top-right" } );
                    this.manageProfessorModalClose();
                    this.professorList();
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of professor update api callback
        professorUpdate() {
            this.manageProfessorLoading = true;
            apiServices.PATCH(apiRoutes.professorUpdate, this.formData, (res) => {
                this.manageProfessorLoading = false;
                if(res.message) {
                    this.formData = {
                        name: '',
                        email: '',
                        phone: '',
                        department_id: 'select-department-option',
                        educational_qualification: '',
                        joining_date: '',
                        avatar: null,
                    }
                    this.$toast.success(res.message, { position: "top-right" } );
                    this.manageProfessorModalClose();
                    this.professorList();
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of professor single api callback
        professorSingle(data) {
            apiServices.PUT(apiRoutes.professorSingle, { id: data }, (res) => {
                if (res.message) {
                    this.formData = res.data;
                } else {
                    this.error = res.errors;
                }
            });
        },

        // Function of professor delete api callback
        professorDelete() {
            this.selected.forEach((v) => {
                this.deleteProfessorParam.ids.push(v);
            })
            this.deleteProfessorLoading = true;
            apiServices.DELETE(apiRoutes.professorDelete, this.deleteProfessorParam, (res) => {
                this.deleteProfessorLoading = false;
                if(res.message) {
                    this.deleteProfessorModalClose();
                    this.professorList();
                    this.$toast.success(res.message, { position: "top-right" } );
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of get department api callback
        getDepartment(){
            apiServices.GET(apiRoutes.departmentList, '', (res) => {
                if(res.message) {
                    this.departmentDataList = res.data.data
                }else {
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
