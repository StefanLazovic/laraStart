<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12 mt-5">

        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header text-dark" style="background-image: url('./img/user-cover.png')">
            <h3 class="widget-user-username">{{ form.name }}</h3>
          </div>
          <div id="user-image" :src="displayProfilePhoto()" class="widget-user-image image-position"></div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">3,200</h5>
                  <span class="description-text">SALES</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">13,000</h5>
                  <span class="description-text">FOLLOWERS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4">
                <div class="description-block">
                  <h5 class="description-header">35</h5>
                  <span class="description-text">PRODUCTS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
      </div>

      <!-- PROFILE SETTINGS -->
      <div class="col-md-12 mt-4">
        <div class="card">
          <div class="card-header text-white">
            PROFILE SETTINGS
          </div>
          <div class="card-body">

            <form @submit="updateProfileInfo()" class="form-horizontal">
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-12">
                  <input v-model.lazy="form.name" type="text" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-12">
                  <input v-model="form.email" type="email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>
              </div>

              <div class="form-group">
                <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
                <div class="col-sm-12">
                  <textarea v-model="form.bio" class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                </div>
              </div>

              <!-- this is the way how to style default input file button -->
              <div class="form-group mt-4">
                <label class="col-sm-2 control-label" style="display: block">Profile Photo</label>
                <label for="photo" class="btn btn-primary ml-3">Choose File</label> <span>{{ photoName }}</span>
                <div class="col-sm-12">
                    <input id="photo" @change="getProfilePhoto" type="file" name="photo" class="form-input" style="display: none">
                </div>
              </div>

              <div class="form-group">
                <label for="password" class="col-sm-12 control-label">Password (leave this field empty if you don't want to change it)</label>
                <div class="col-sm-12">
                <input v-model="form.password" type="password" class="form-control" id="password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                 <has-error :form="form" field="password"></has-error>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-12">
                <button type="submit" class="btn bg-purple text-white mt-3" style="width: 200px">Update Profile Info</button>
                </div>
              </div>
            </form>

          </div><!-- /.card-body -->
        </div><!-- /.nav-tabs-custom -->
      </div><!-- end tabs -->

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
        photoName: ''

      }
    },
    created() {
      // display profile settings data (UserController@profile, Profile.vue, api.php)
      this.form.get('api/profile')
      .then(({ data }) => {
        this.form.fill(data);
      })
    },
    methods: {

      handleClick() {
        alert('asdf');
      },

      // with this method we are able to reload the whole page, not just components
      reload() {
        window.location.reload();
        let timesRun = 0;
        let x = setInterval(() => {
          timesRun += 1;
          if(timesRun === 2){
            clearInterval(x);
            window.location.reload();
          }
        }, 10);
      },

      getProfilePhoto(e) {
        // display file's name:
        this.photoName = e.target.files[0].name;
        // base64
        let file = e.target.files[0];
        let reader = new FileReader();
        // if a fileze is above 2MB, then throw the error
        if (file.size < 2111775) {
          reader.onloadend = () => {
            // console.log('RESULT', reader.result);
            this.form.photo = reader.result;
          }
          reader.readAsDataURL(file);
        }else {
          Swal.fire(
            'Oops...',
            'The file must be at least 2MB or less.',
            'error'
          )
          this.photoName = '';
        }
      },

      displayProfilePhoto() {
        // display default profile photo
        if (this.form.photo == 'profile.jpeg') {
          return './img/profile.jpeg';
        }
        // show profile photo instantly (this will not work now, because :src isn't in img tag anymore)
        let photo = (this.form.photo.length > 200) ? this.form.photo : 'img/profile/' + this.form.photo;
        return photo;
      },

      updateProfileInfo() {
        /* Password field will not be updated if it's value is an empty string. In that case, it has to be a string (password) or default undefined.
        If you try to put the string inside, password field with undefined value will become string. But, if you change your mind, and delete written,
        password value will stay as an empty string and not undefined as default. In that case, this form will not be able to be updated. */
        if (this.form.password == "") {
          this.form.password = undefined;
        }
        // start the progress bar loading
        this.$Progress.start();
        this.form.put('api/profile')
        .then(() => {
          // finish the progress bar loading
          this.$Progress.finish();
          // reload the page
          this.reload();
        })
      }

    }
  }
</script>

<style scoped>
.widget-user-header {
  background-position: center center;
  background-size: cover;
  height: 250px;
}

.card-footer {
  background: #fff;
}

.image-position {
  margin-top: 140px;
}

#user-image {
  border-radius: 50%;
  width: 85px;
  height: 85px;
  border: 3px solid #fff;
  box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.5);
  background-size: cover;
  background-position: center;
}
</style>
