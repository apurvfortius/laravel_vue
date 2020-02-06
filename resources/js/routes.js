export const routes = [
    { path: '/', components: require('./components/Index') },
    { path: '/login', components: require('./components/auth/Login.vue') },
    { path: '/register', components: require('./components/auth/Register.vue') },

    { path: '/dashboard', components: require('./components/Dashboard.vue'), meta:{ requireAuth : true } },
    { path: '/profile', components: require('./components/Profile.vue'), meta:{ requireAuth : true } },
    { path: '/users', components: require('./components/Users.vue'), meta:{ requireAuth : true } },
    { path: '/developer', components: require('./components/Developer.vue'), meta:{ requireAuth : true } },
    { path: '/roles', components: require('./components/Roles.vue'), meta:{ requireAuth : true } },
    { path: '/permissions', components: require('./components/Permission.vue'), meta:{ requireAuth : true } },
    { path: '/business', components: require('./components/Business.vue'), meta:{ requireAuth : true } },
    { path: '/productype', components: require('./components/Product_type.vue'), meta:{ requireAuth : true } }
]