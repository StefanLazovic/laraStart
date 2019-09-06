<template>
  <div class="container">
    <!-- Access Control List (Gate.js, AuthServiceProvider.php, master.blade.php, Users.vue) -->
    <div class="row" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12 mt-5">
        <div class="card">
          <div class="card-header text-white">
            USERS TABLE

            <div class="card-tools">
              <button class="btn btn-sm btn-primary" @click="openNewModal()">Add New <i class="fas fa-user-plus fa-fw"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | upText }}</td>
                  <td>{{ user.created_at | myDate }}</td>
                  <td>
                    <a href="#" @click="openEditModal(user)"><i class="fa fa-edit color-blue"></i></a>
                    /
                    <a href="#" @click="deleteUser(user.id)"><i class="fa fa-trash color-red"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer p-0">
            <!-- laravel-vue-pagination (app.js, Users.vue) -->
            <pagination class="pagination-sm mb-0 p-2 pl-4" :data="users" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <p v-show="editmode" class="modal-title text-white" id="addNewLabel">ADD NEW</p>
            <p v-show="!editmode" class="modal-title text-white" id="addNewLabel">UPDATE USER'S INFO</p>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editmode ? createUser() : updateUser()">
            <div class="modal-body">
              <div class="form-group">
                <input v-model="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Name">
                <has-error :form="form" field="name"></has-error>
              </div>

              <div class="form-group">
                <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email Address">
                <has-error :form="form" field="email"></has-error>
              </div>

              <div class="form-group">
                <textarea v-model="form.bio" type="bio" name="bio" class="form-control" placeholder="Short bio for user (Optional)"></textarea>
              </div>

              <div class="form-group">
                <select v-model="form.type" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                  <option value="">Select User Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">Standard User</option>
                  <option value="author">Author</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>

              <div class="form-group">
                <input v-model="form.password" type="password" name="password" id="password"
                class="form-control" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                <has-error :form="form" field="password"></has-error>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-primary">Create</button>
              <button v-show="!editmode" type="submit" class="btn bg-purple text-white">Update</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <!-- Access Control List (Gate.js, AuthServiceProvider.php, master.blade.php, Users.vue) -->
    <!-- if the user is not admin or author, display not found message -->
    <div  v-if="!$gate.isAdminOrAuthor()">
      <!-- app.js -->
      <not-found></not-found>
    </div>

  </div>
</template>

<script>
  export default {
    data() {
      return {

        form: new Form({
          id: '',
          name: '',
          email: '',
          password: '',
          type: '',
          bio: '',
          photo: ''
        }),
        users: {},
        editmode: null

      }
    },
    created() {

      // show users table when we open /users component (UserController@index)
      this.loadUsers();

      // show refreshed users table when we successfully create/update/delete certain informations (UserController@index)
      Fire.$on('RefreshPage', () => {
        this.loadUsers();
      });

      // search form (app.js, master.blade.php, Users.vue, UserController@search, api.php)
      Fire.$on('searching', () => {
        // this.$parent.search - a way to access data from parent component instead props
        this.form.get('api/findUser?q=' + this.$parent.search)
        .then(({ data }) => {
          this.users = data;
        })
      });

    },
    methods: {

      // display all users (UserController@index)
      loadUsers() {
        // Access Control List (Gate.js, AuthServiceProvider.php, master.blade.php, Users.vue)
        if (this.$gate.isAdminOrAuthor()) {
          this.form.get('api/user')
          .then(({ data }) => {
            this.users = data;
          })
        }
      },

      // open modal window for createUser()
      openNewModal() {
        this.editmode = true;
        $('#addNew').modal('show');
        // reset the form fields
        this.form.reset();
        // clear the form errors
        this.form.clear();
      },

      // create new user (UserController@store)
      createUser() {
        // start the progress bar loading
        this.$Progress.start();
        this.form.post('api/user')
        .then(() => {
          $('#addNew').modal('hide');
          // finish the progress bar loading
          this.$Progress.finish();
          // if the new user is successfully created, then refresh page to see created info
          Fire.$emit('RefreshPage');
          // fire sweet alert message
          Toast.fire({
            type: 'success',
            title: 'User created successfully.'
          });
        })
        .catch(() => {
          // if the something goes wrong, turn progress bar color to red
          this.$Progress.fail();
        })
      },

      // open modal window for updateUser()
      openEditModal(data) {
        console.log(data);
        this.editmode = false;
        $('#addNew').modal('show');
        // reset the form fields
        this.form.reset();
        // fill form data
        this.form.fill(data);
      },

      // update user (UserController@update)
      updateUser() {
        // start the progress bar loading
        this.$Progress.start();
        this.form.put('api/user/' + this.form.id)
        .then(() => {
          $('#addNew').modal('hide');
          // finish the progress bar loading
          this.$Progress.finish();
          // if the existing user is successfully updated, then refresh page to see updated info
          Fire.$emit('RefreshPage');
          // fire sweet alert message
          Swal.fire(
            'Updated!',
            'Information has been updated.',
            'success'
          );
        })
        .catch(() => {
          // if the something goes wrong, turn progress bar color to red
          this.$Progress.fail();
        })
      },

      // delete user (UserController@destroy)
      deleteUser(id) {
        // fire sweet alert message
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            // start the progress bar loading
            this.$Progress.start();
            // if you click 'Yes, delete it!', then it is going to delete information, and fire success message
            this.form.delete('api/user/' + id)
            .then(() => {
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              );
              // finish the progress bar loading
              this.$Progress.finish();
              // // if the existing user is successfully deleted, then refresh page to see changed info
              Fire.$emit('RefreshPage');
            })
            .catch(() => {
              // fire sweet alert message if it's refused to proceed
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
              });
              // if the something goes wrong, turn progress bar color to red
              this.$Progress.fail();
            })
          }
        });
      },

      // laravel-vue-pagination (app.js, Users.vue)
      getResults(page = 1) {
  			axios.get('api/user?page=' + page)
  				.then((response) => {
  					this.users = response.data;
  			  });
		  }

    }
  }
</script>
