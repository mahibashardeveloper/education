<template>

    <!-- Breadcrumb -->
    <div class="d-sm-flex justify-content-between align-items-center">
        <breadcrumb :items="BreadcrumbItems" moduleName="Courses"/>
    </div>

    <!-- Search and new -->
    <div class="row justify-content-between">
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="position-relative">
                <input type="text" name="keyword" class="form-control ps-5" autocomplete="new-search"
                       placeholder="Search here" v-model="listData.keyword" @keyup="SearchData()">
                <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="bi bi-search"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 mb-3 d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-light border-0 mx-2" @click="deleteCourseModalOpen()" v-if="tableData.length > 0 && loading === false && selected.length > 0">
                <i class="bi bi-trash2 text-danger"></i>
            </button>
            <newBtn @click="manageCourseModalOpen(null)"/>
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
                            Enroll
                        </th>
                        <th class="default-width">
                            Price
                        </th>
                        <th class="default-width">
                            Duration
                        </th>
                        <th class="default-width">
                            Professor
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
                            {{each.student_enroll_capacity}}
                        </td>
                        <td class="default-width">
                            {{each.price}}
                        </td>
                        <td class="default-width">
                            {{each.duration}}
                        </td>
                        <td class="default-width">
                            <span v-if="each.professor_info != null">
                                {{ each.professor_info.name }}
                            </span>
                        </td>
                        <td class="action">
                            <div class="dropdown">
                                <button type="button" class="btn border-0 p-0 btn-icon" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end p-2 mt-1 overflow-hidden rounded-3 border">
                                    <li class="mb-2">
                                        <button type="button" class="dropdown-item rounded-3" @click="manageCourseModalOpen(each.id)">
                                            Edit
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item rounded-3" @click="deleteCourseModalOpen(each.id)">
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

    <!-- Preloader of data list -->
    <preloader v-if="loading"/>

    <!-- No data found when list data is empty -->
    <noDataFounded :text="'course'" :newModalFunction="manageCourseModalOpen" v-if="!loading  && tableData.length === 0"/>

    <!-- Pagination of list data -->
    <pagination :total_pages="total_pages" :current_page="current_page" :buttons="buttons"
                @page-change="handlePageChange" v-if="!loading && tableData.length > 0"/>

    <!-- Modal of manage course -->
    <div class="modal fade" id="manageCourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="manageCourse()" class="modal-content px-3 py-2 rounded-3 border-0">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                        <span v-if="this.formData.id === undefined"> Create </span>
                        <span v-if="this.formData.id !== undefined"> Edit </span>
                        course
                    </h1>
                    <button type="button" class="btn-close shadow-none" @click="manageCourseModalClose"></button>
                </div>
                <div class="modal-body border-0">

                    <div class="form-group mb-3">
                        <label for="upload-image" v-if="this.formData.avatar === null && !uploadLoading"
                               class="form-label hpx-150 d-flex justify-content-center align-items-center flex-column bg-white rounded-4 text-center cursor-pointer border">
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
                        <label for="student-enroll-capacity" class="form-label"> Student enroll capacity </label>
                        <input id="student-enroll-capacity" v-model="formData.student_enroll_capacity" type="text"
                               name="student-enroll-capacity" class="form-control"
                               autocomplete="new-student-enroll-capacity">
                        <div class="error-report" v-if="error != null && error.student_enroll_capacity !== undefined"> {{error.student_enroll_capacity[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label"> Name </label>
                        <input id="name" v-model="formData.name" type="text" name="name" class="form-control"
                               autocomplete="new-name">
                        <div class="error-report" v-if="error != null && error.name !== undefined"> {{error.name[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="professor_id" class="form-label">Select professor</label>
                        <select name="professor_id" id="professor_id" class="form-select" v-model="formData.professor_id" autocomplete="new-professor-id">
                            <option :value="0">Select professor option</option>
                            <option v-for="each in professorDataList" :value="each.id">
                                {{ each.name }}
                            </option>
                        </select>
                        <div class="error-report" v-if="error != null && error.professor_id !== undefined"> {{error.professor_id[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price" class="form-label"> Price </label>
                        <input id="price" v-model="formData.price" type="text" name="price" class="form-control"
                               autocomplete="new-price">
                        <div class="error-report" v-if="error != null && error.price !== undefined"> {{error.price[0]}} </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="duration" class="form-label"> Duration </label>
                        <input id="duration" v-model="formData.duration" type="text" name="duration"
                               class="form-control" autocomplete="new-duration">
                        <div class="error-report" v-if="error != null && error.duration !== undefined"> {{error.duration[0]}} </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label"> Description </label>
                        <textarea id="description" class="form-textarea" name="description"
                                  v-model="formData.description" cols="30" rows="5"
                                  autocomplete="new-description"></textarea>
                        <div class="error-report" v-if="error != null && error.description !== undefined"> {{error.description[0]}} </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary wpx-110" @click="manageCourseModalClose">
                        Close
                    </button>
                    <button type="submit" class="btn btn-theme wpx-110" v-if="!manageCourseLoading">
                        <span v-if="this.formData.id === undefined"> Save </span>
                        <span v-if="this.formData.id !== undefined"> Update </span>
                    </button>
                    <button type="button" class="btn btn-theme wpx-110" v-if="manageCourseLoading">
                        <span class="spinner-border border-2 wpx-15 hpx-15"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal of delete course -->
    <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="courseDelete()" class="modal-content rounded-3 border-0 py-2 px-3">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                        Delete course
                    </h1>
                    <button type="button" class="btn-close shadow-none" @click="deleteCourseModalClose"></button>
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
                        <button type="button" class="btn btn-secondary rounded-3 w-100" @click="deleteCourseModalClose">
                            Close
                        </button>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-theme rounded-3 w-100" v-if="!deleteCourseLoading">
                            Confirm
                        </button>
                        <button type="button" class="btn btn-theme rounded-3 w-100" v-if="deleteCourseLoading">
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
                { title: 'Courses', route: 'courses' },
            ],
            formData: {
                avatar: null,
                student_enroll_capacity: '',
                name: '',
                professor_id: '0',
                price: '',
                duration: '',
                description: '',
            },
            professorDataList: [],
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
            manageCourseLoading: false,
            error: null,
            deleteCourseLoading: false,
            uploadLoading: false,
            uploadedImage: null,
            uploadedImageId: null,
        }
    },
    mounted() {
        this.courseList();
    },
    methods: {

        // Function of handle page change
        handlePageChange(page) {
            this.current_page = page;
            this.courseList();
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

        // Function of manage course modal open
        manageCourseModalOpen(data = null) {
            this.getProfessor();
            apiServices.clearErrorHandler()
            if(data !== null) {
                this.courseSingle(data);
            }else {
                this.formData = {
                    avatar: null,
                    student_enroll_capacity: '',
                    name: '',
                    professor_id: '0',
                    price: '',
                    duration: '',
                    description: '',
                }
            }
            const myModal = new bootstrap.Modal("#manageCourseModal", {keyboard: false});
            myModal.show();
        },

        // Function of manage course modal close
        manageCourseModalClose() {
            let myModalEl = document.getElementById('manageCourseModal');
            let modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        },

        // Function of delete course modal open
        deleteCourseModalOpen(id) {
            this.deleteProfessorParam.ids.push(id)
            const myModal = new bootstrap.Modal("#deleteCourseModal", {keyboard: false});
            myModal.show();
        },

        // Function of delete course modal close
        deleteCourseModalClose() {
            this.selected = [];
            this.current_page = 1;
            this.formData = {
                avatar: null,
                student_enroll_capacity: '',
                name: '',
                professor_id: '0',
                price: '',
                duration: '',
                description: '',
            }
            let myModalEl = document.getElementById('deleteCourseModal');
            let modal = bootstrap.Modal.getInstance(myModalEl)
            modal.hide();
        },

        // Function of course list api callback
        courseList() {
            this.loading = true;
            this.listData.page = this.current_page;
            apiServices.GET(apiRoutes.courseList, this.listData, (res) => {
                this.loading = false;
                if (res) {
                    this.tableData = res?.data?.data;
                    this.last_page = res?.data?.last_page
                    this.total_pages = res?.data?.total < res?.data?.per_page ? 1 : Math.ceil((res?.data?.total / res?.data?.per_page));
                    this.current_page = res?.data?.current_page;
                    this.buttons = [...Array(this.total_pages).keys()].map((i) => i + 1);
                } else {
                    apiServices.clearErrorHandler(res.error)
                }
            })
        },

        // Function of course search data
        SearchData() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.courseList();
            }, 800);
        },

        // Function of course manage as create and update api callback
        manageCourse() {
            if(this.formData.id === undefined) {
                this.courseCreate()
            }else {
                this.courseUpdate()
            }
        },

        // Function of course create api callback
        courseCreate() {
            this.manageCourseLoading = true;
            apiServices.POST(apiRoutes.courseCreate, this.formData, (res) => {
                this.manageCourseLoading = false;
                if(res.message) {
                    this.formData = {
                        avatar: null,
                        student_enroll_capacity: '',
                        name: '',
                        professor_id: '0',
                        price: '',
                        duration: '',
                        description: '',
                    }
                    this.$toast.success(res.message, { position: "top-right" } );
                    this.manageCourseModalClose();
                    this.courseList();
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of course update api callback
        courseUpdate() {
            this.manageCourseLoading = true;
            apiServices.PATCH(apiRoutes.courseUpdate, this.formData, (res) => {
                this.manageCourseLoading = false;
                if(res.message) {
                    this.formData = {
                        avatar: null,
                        student_enroll_capacity: '',
                        name: '',
                        professor_id: '0',
                        price: '',
                        duration: '',
                        description: '',
                    }
                    this.$toast.success(res.message, { position: "top-right" } );
                    this.manageCourseModalClose();
                    this.courseList();
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of course single api callback
        courseSingle(data) {
            apiServices.PUT(apiRoutes.courseSingle, { id: data }, (res) => {
                if (res.message) {
                    this.formData = res.data;
                } else {
                    this.error = res.errors;
                }
            });
        },

        // Function of course delete api callback
        courseDelete() {
            this.selected.forEach((v) => {
                this.deleteProfessorParam.ids.push(v);
            })
            this.deleteCourseLoading = true;
            apiServices.DELETE(apiRoutes.courseDelete, this.deleteProfessorParam, (res) => {
                this.deleteCourseLoading = false;
                if(res.message) {
                    this.deleteCourseModalClose();
                    this.courseList();
                    this.$toast.success(res.message, { position: "top-right" } );
                } else {
                    this.error = res.errors
                }
            })
        },

        // Function of get professor api callback
        getProfessor(){
            apiServices.GET(apiRoutes.professorList, '', (res) => {
                if(res.message) {
                    this.professorDataList = res.data.data
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
