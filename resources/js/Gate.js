
// Access Control List (Gate.js, AuthServiceProvider.php, master.blade.php, Users.vue)

export default class Gate {

  // user is coming from app.js
  constructor(user) {
    this.user = user;
  }

  isAdminOrAuthor() {
    return this.user.type == 'admin' || this.user.type == 'author';
  }

}
