<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12" v-if="$can('view')">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Business</div>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal"> <i class="fa fa-user-plus fa-fw"></i> Add New </button> 
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0" >
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created Date</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in businesses.data" :key="row.id">
                                    <td>{{ row.id }}</td>
                                    <td>{{ row.business_name | upText }}</td>
                                    <td>{{ row.business_description }}</td>
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
                        <pagination :data="businesses" @pagination-change-page="getResults"></pagination>
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
                                <input v-model="form.business_name" type="text" name="business_name" id="business_name" class="form-control" placeholder="Name" :class="{ 'is-invalid': form.errors.has('business_name') }">
                                <has-error :form="form" field="business_name"></has-error>
                            </div>

                            <div class="form-group">
                                <textarea v-model="form.business_description" name="business_description" id="business_description" class="form-control" placeholder="Description" :class="{ 'is-invalid': form.errors.has('business_description') }"></textarea>
                                <has-error :form="form" field="business_description"></has-error>
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
                form: new Form({
                    id: '',
                    business_name : '',
                    business_description : '',
                })
            }
        },
        methods: {
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            editModal(user) {
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            getResults(page = 1){
                axios.get("api/business?page="+page).then( responce => {
                    this.businesses = responce.data;
                }); 
            },
            getData() {
                if(this.$can('view')){
                    axios.get("api/business").then(({ data }) => (this.businesses = data)); 
                }                            
            },
            createNew() {
                this.$Progress.start();
                this.form.post('api/business')
                .then( () => {
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Business Type Created Successfully',
                    });
                    this.$Progress.finish(); 
                })
                .catch( () => {
                    this.$Progress.finish();
                })                
            },
            updateData() {
                this.$Progress.start();
                this.form.put('api/business/'+this.form.id)
                .then( () => {
                    //success
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Business Type has been Updated Successfully',
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
                        this.form.delete('api/business/'+id).then(() => {
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
                axios.get('api/findbusiness?key='+keywo)
                .then((data) => {
                    this.businesses = data.data;
                })
                .catch(() => {

                });
            });
        }
    }
</script>