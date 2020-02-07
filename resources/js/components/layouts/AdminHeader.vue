<template>
   <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            
            <a href="../../index3.html" class="navbar-brand">
                <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>
            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                    </li>
                    

                    <!-- <li v-for="item in items" :key="item.id" class="nav-item" @click="pushLink(item.link)">
                        <p class="nav-link" v-bind:class="{ active: item.active }">{{ item.item }}</p>
                    </li> -->
                </ul>
                

                <!-- SEARCH FORM -->
                <form class="form-inline ml-0 ml-md-3">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto" id="navbarCollapse">
                <li class="nav-item dropdown" v-if="loggedIn">
                    <p @click="logout()">Logout</p>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Navbar',
    data () {
        return {
            sidebar : false,
            items: [
                {
                    id: 1,
                    item: 'AdminHome',
                    link: '/',
                    active: true,
                },
                {
                    id: 2,
                    item: 'Contact',
                    link: '/contact',
                    active: false,
                },
                {
                    id: 3,
                    item: 'About',
                    link: '/about',
                    active: false,
                },
            ],
        }
    },
    methods: {
        pushLink(link) {
            this.$router.push(link)
        },
        logout() {
            this.$store.dispatch('destroyToken').then( response => {
                this.$router.push('/');
            }).catch( () => {

            });
        }
    },
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn
        }
    }
}
</script>