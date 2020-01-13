<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12" v-if="$can('view')">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permissions</div>

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
                                    <th>Role</th>
                                    <th>Created Date</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="permi in permissions.data" :key="permi.id">
                                    <td>{{ permi.id }}</td>
                                    <td>{{ permi.name | upText }}</td>
                                    <td>{{ permi.count }}</td>
                                    <td>{{ permi.created_at | myDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(permi)"><i class="fa fa-edit blue"></i> </a> / 
                                        <a href="#" @click="deleteUser(permi.id)"><i class="fa fa-trash red"></i> </a>
                                        <!-- <a href="#"><i class="fa fa-eye blue"></i> </a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="permissions" @pagination-change-page="getResults"></pagination>
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
                        <h5 class="modal-title" v-show="editMode" id="addNewTitle"> Update Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" id="name" class="form-control" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Select</label>
                                <ul class="checkbox_list"> 
                                    <li v-for="role in roles" :key="role.id">
                                        <input type="checkbox" :value="role.name" v-model="form.role">{{ role.name }}
                                    </li>
                                </ul>                                
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
                roles : {},
                permissions : {},
                form: new Form({
                    id: '',
                    name : '',
                    role : [],
                })
            }
        },
        methods: {
            newModal() {
                this.getRole();
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            editModal(user) {
                this.getRole();
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            getResults(page = 1){
                axios.get("api/permissions?page="+page).then( responce => {
                    this.permissions = responce.data;
                }); 
            },
            getPermisision() {
                if(this.$can('view')){
                    axios.get("api/permissions").then(({ data }) => (this.permissions = data)); 
                }                            
            },
            getRole() {
                if(this.$can('view') || this.$can('edit')){
                    axios.get("api/getroles").then(({ data }) => (this.roles = data)); 
                }
            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/permissions')
                .then( () => {
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Permission Created Successfully',
                    });
                    this.$Progress.finish(); 
                })
                .catch( () => {
                    this.$Progress.finish();
                })                
            },
            updateUser() {
                this.$Progress.start();
                this.form.put('api/permissions/'+this.form.id)
                .then( () => {
                    //success
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Permission has been Updated Successfully',
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
                        this.form.delete('api/permissions/'+id).then(() => {
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
            this.getPermisision();
            Fire.$on('AfterDone', () => this.getPermisision());

            Fire.$on('searching', () => {
                let keywo = this.$parent.search;
                axios.get('api/findpermission?key='+keywo)
                .then((data) => {
                    this.roles = data.data;
                })
                .catch(() => {

                });
            });
        }
    }
</script>