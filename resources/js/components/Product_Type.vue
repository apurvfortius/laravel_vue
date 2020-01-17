<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12" v-if="$can('view')">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Product Type</div>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal"> <i class="fa fa-user-plus fa-fw"></i> Add New </button> 
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0" >
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Business Modal</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created Date</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in rows.data" :key="row.id">
                                    <td>{{ row.id }}</td>
                                    <td>{{ row.business_name | upText }}</td>
                                    <td>{{ row.product_type | upText }}</td>
                                    <td>{{ row.description }}</td>
                                    <td>{{ row.created_at | myDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(row)"><i class="fa fa-edit blue"></i> </a> / 
                                        <a href="#" @click="deleteUser(row.id)"><i class="fa fa-trash red"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="rows" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-12" v-if="!$can('view')">
                <not-found></not-found>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editMode" id="addNewTitle"> Add New</h5>
                        <h5 class="modal-title" v-show="editMode" id="addNewTitle"> Update</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <form @submit.prevent="editMode ? updateData() : createNew()">
                        <div class="modal-body">
                            <div class="form-group">
                                <select v-model="form.business_id" name="type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select Businees</option>
                                    <option v-for="row in businesses" :key="row.id" :value="row.id">{{ row.business_name | upText }}</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.product_type" type="text" class="form-control" placeholder="Product Type" :class="{ 'is-invalid': form.errors.has('product_type') }">
                                <has-error :form="form" field="product_type"></has-error>
                            </div>

                            <div class="form-group">
                                <textarea v-model="form.description" class="form-control" placeholder="Description" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                <has-error :form="form" field="description"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
        data () {
            return {
                editMode: false,
                businesses : {},
                rows: {},
                form: new Form({
                    id: '',
                    business_id : '',
                    product_type : '',
                    description: '',
                })
            }
        },
        methods: {
            newModal() {
                this.getBusinesses();
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            editModal(user) {
                this.getBusinesses();
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            getResults(page = 1){
                axios.get("api/productype?page="+page).then( responce => {
                    this.rows = responce.data;
                }); 
            },
            getData() {
                if(this.$can('view')){
                    axios.get("api/productype").then(({ data }) => (this.rows = data)); 
                }                            
            },
            getBusinesses() {
                if(this.$can('view')){
                    axios.get("api/getbusiness").then(({ data }) => (this.rows = data)); 
                }                            
            },
            createNew() {
                this.$Progress.start();
                this.form.post('api/productype')
                .then( () => {
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Product Type Created Successfully',
                    });
                    this.$Progress.finish(); 
                })
                .catch( () => {
                    this.$Progress.finish();
                })                
            },
            updateData() {
                this.$Progress.start();
                this.form.put('api/productype/'+this.form.id)
                .then( () => {
                    //success
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Product Type has been Updated Successfully',
                    });
                    this.$Progress.finish();
                }).catch( () => {
                    //error
                    this.$Progress.fail();
                });
            },
            deleteUser(id) {
                swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.value){
                        //send request to delete
                        this.form.delete('api/productype/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'Permission has been deleted.',
                            'success'
                            )
                            Fire.$emit('AfterDone');
                        }).catch(() => {
                            swal.fire(
                                'Failed!',
                                'There Was something wrong.',
                                'warning'
                            )
                        }); 
                    }                 
                })
            }
        },
        created() {
            this.getData();
            Fire.$on('AfterDone', () => this.getData());

            Fire.$on('searching', () => {
                let keywo = this.$parent.search;
                axios.get('api/findproductype?key='+keywo)
                .then(({ data }) => (this.rows = data))
                .catch(() => {

                });
            });
        }
    }
</script>