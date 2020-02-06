<template>
  <div id="app" class="wrapper">
    <Navbar/>

    <Sidebar/>
    
      <router-view></router-view>
    
    <Footer/>
  </div>
</template>

<script>
import Navbar from './components/layouts/Navbar'
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
    Navbar,
    Sidebar,
    Footer,
  }
}
</script>
