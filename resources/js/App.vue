<template>
  <div id="app" class="wrapper">
    <Navbar v-if="!loggedIn"></Navbar>
    <Admin v-if="loggedIn"></Admin>

    <Sidebar v-if="loggedIn"></Sidebar>
    
      <router-view></router-view>
    
    <Footer/>
  </div>
</template>

<script>
import Navbar from './components/layouts/Navbar'
import Admin from './components/layouts/AdminHeader'
import Sidebar from './components/layouts/Sidebar'
import Footer from './components/layouts/Footer'
export default {
  name: 'App',
  data() {
    return {
      msg: "this is main app"
    }
  },
  components: {
    Admin,
    Navbar,
    Sidebar,
    Footer,
  },
  computed: {
        loggedIn() {
            return this.$store.getters.loggedIn
        }
    }
}
</script>
