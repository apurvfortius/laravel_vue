<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12" v-if="$can('view')">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal"> <i class="fa fa-user-plus fa-fw"></i> Add New </button> 
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" >
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Created Date</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.phone }}</td>
                                    <!-- <td>{{ user.type | upText }}</td> -->
                                    <td>{{ user.created_at | myDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(user)"><i class="fa fa-edit blue"></i> </a> / 
                                        <a href="#" @click="deleteUser(user.id)"><i class="fa fa-trash red"></i> </a>
                                        <!-- <a href="#"><i class="fa fa-eye blue"></i> </a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
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
                        <h5 class="modal-title" v-show="editMode" id="addNewTitle"> Update User's Info</h5>
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
                                <input v-model="form.email" type="email" name="email" id="email" class="form-control" placeholder="Email Address" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.phone" type="text" name="phone" id="phone" class="form-control" placeholder="Phone Number" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                            </div>

                            <div class="form-group">
                                <select v-model="form.type" name="type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Type</option>
                                    <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name | upText }}</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" id="password" class="form-control" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
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
                users : {},
                roles: {},
                form: new Form({
                    id: '',
                    name : '',
                    email :'',
                    phone: '',
                    type: '',
                    role: '',
                    photo: '',
                    password: ''
                })
            }
        },
        methods: {
            newModal() {
                this.getRoles();
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            editModal(user) {
                this.getRoles();
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            getResults(page = 1){
                axios.get("api/users?page="+page).then( responce => {
                        this.users = responce.data;
                    } ); 
            },
            getUsers() {
                if(this.$can('view')){
                    axios.get("api/users").then(({ data }) => (this.users = data)); 
                }                            
            },
            getRoles() {
                if(this.$can('view')){
                    axios.get("api/getroles").then(({ data }) => (this.roles = data)); 
                }                            
            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/users')
                .then( () => {
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'User Created Successfully',
                    });
                    this.$Progress.finish(); 
                })
                .catch( () => {
                    this.$Progress.finish();
                })                
            },
            updateUser() {
                this.$Progress.start();
                this.form.put('api/users/'+this.form.id)
                .then( () => {
                    //success
                    Fire.$emit('AfterDone');
                    $('#addNew').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'User has been Updated Successfully',
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
                        this.form.delete('api/users/'+id).then(() => {
                            swal.fire(
                            'Deleted!',
                            'User has been deleted.',
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
            this.getUsers();
            Fire.$on('AfterDone', () => this.getUsers());

            Fire.$on('searching', () => {
                let keywo = this.$parent.search;
                axios.get('api/finduser?key='+keywo)
                .then((data) => {
                    this.users = data.data;
                })
                .catch(() => {

                });
            });
        }
    }
</script>