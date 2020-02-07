<template>
    <div class="login-page">
        <div class="login-box">
            <div class="card"> 
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <form action="#" @submit.prevent="login">
                        <div class="input-group mb-3">
                            <input v-model="form.email" name="email" id="email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" class="form-control" placeholder="Email">
                            <has-error :form="form" field="email"></has-error>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password" class="form-control" placeholder="Password">
                            <has-error :form="form" field="password"></has-error>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        <!-- /.col -->
                        </div>
                    </form>

                    <!-- <div class="social-auth-links text-center mb-3">
                        <p>- OR -</p>
                        <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                        </a>
                    </div> -->
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="#" @click="pushLink('/forgotpassword')">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="#" @click="pushLink('/register')" class="text-center">Register a new membership</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
    <!-- /.login-box -->
</template>

<script>
export default {
    name: 'login',
    data() {
        return {
            form: new Form({
                email: '',
                password: '',
            })
        }
    },
    methods:{
        login() {
            this.$store.dispatch('retriveToken', this.form ).then( response => {
                this.$router.push('/dashboard');
            }).catch( () => {
                
            })
        },
        pushLink(link) {
            this.$router.push(link)
        },
    }
}
</script>