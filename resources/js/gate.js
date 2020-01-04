export default class Gate{

    constructor(user){
        this.user = user;
        //console.log(user);
    }

    isSuperAdmin(){
        return this.user.role_id === 'SuperAdmin'
    }

    isAdmin(){
        return this.user.role_id === 'Admin'
    }

    isUser(){
        return this.user.role_id === 'User'
    }

    isGuest(){
        return this.user.role_id === 'Guest'
    }

    isAdminOrUser(){
        if(this.user.role_id === 'SuperAdmin' || this.user.role_id === 'Admin' || this.user.role_id === 'User')
        { 
            return true; 
        }
    }

    isGuestOrUser(){
        if(this.user.role_id === 'Guest' || this.user.role_id === 'User')
        { 
            return true; 
        }
    }
}