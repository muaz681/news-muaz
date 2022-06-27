<template>
<v-row>
  <v-col>
    <!-- Seo Information -->
    <v-container fluid>
      <v-row dense>
        <v-col class="col-12">
          <v-card class="mx-auto">
            <v-toolbar color="indigo" class="font-weight-regular" dark dense>
              <v-toolbar-title>Settings Information</v-toolbar-title>
              <v-spacer></v-spacer>
              <!--      Settings add Dialog           -->
              <v-dialog v-model="dialog" persistent max-width="600px">
                <!-- <template v-slot:activator="{ on, attrs }">
                  <v-btn color="info" dark v-bind="attrs" v-on="on">
                    Add New Settings
                  </v-btn>
                </template> -->
                <v-card>
                  <v-card-title>
                    <span class="text-h5">Add New Settings</span>
                  </v-card-title>
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="12" md="12">
                          <v-text-field label="Name" hint="Settings Name" v-model="display_name"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12">
                          <v-text-field label="Key" hint="Settings key"></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12">
                          <v-select :items="setting_type" :item-text="'title'" :item-value="'value'" label="Type"></v-select>
                        </v-col>
                      </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialog = false">
                      Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="addSetting(), dialog = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>

            <v-container fluid>
              <v-row dense>
                <v-col class="col-12">
                  <v-card ref="form">
                    <!-- Test :{{ test }}
                    Footer About :{{ footer_about }} -->
                    <v-row>
                      <v-col cols="7" >
                        <v-card-text>
                          <v-text-field ref="website_name" v-model="website_name" label="Website Name"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="copyritght" v-model="copyritght" label="Copyritght"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="facebook" v-model="facebook" label="Facebook"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="twitter" v-model="twitter" label="Twitter"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="youtube" v-model="youtube" label="Youtube"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="instagram" v-model="instagram" label="Instagram"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="linkedin" v-model="linkedin" label="Linkedin"></v-text-field>
                        </v-card-text>
                         <v-card-text>
                          <v-text-field ref="playstore-url" label="Play Store Link" v-model="play_store_link"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-text-field ref="appstore-url" label="Apps Store Link" v-model="app_store_link"></v-text-field>
                        </v-card-text>
                        <v-card-text>
                          <v-textarea ref="footer_about" v-model="footer_about" label="Footer About" outlined></v-textarea>
                        </v-card-text>
                      </v-col>
                      <v-col cols="5" justify-center>
                        <!-- Website Logo -->
                        <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                          <v-toolbar-title><h3>Website Logo</h3></v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                          <img :src="site_logo_preview" class="image-preview" title="Click to Change" @click="siteLogoUpload" height="150px"  >
                          <input name="image" type="file" accept="image/.jpg, .png, .jpeg" id="site-logo" @change="siteLogoPreview" style="display: none">
                        </v-card-text>
                        <!-- Background Image -->
                        <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                          <v-toolbar-title class="d-flex">
                            <h3> Header & Footer Background </h3>
                          </v-toolbar-title>
                          <v-spacer></v-spacer>

                        </v-toolbar>
                        <v-row dense>
                          <v-card-text>
                            <v-select
                            :items="['Image','Solid Color']"
                            v-model="bg_type"
                            label="Select BG Type"
                          ></v-select>

                          <v-col v-if="bg_type == 'Image'" class="mt-3">

                            <!-- <v-card-text>  -->
                            <img :src="bg_image_preview" class="image-preview" title="Click to Change" @click="openUpload" height="150px"  >
                            <input name="image" type="file" accept="image/.jpg, .png, .jpeg" id="bg-image" @change="uploadPreview" style="display: none">
                          <!-- </v-card-text> -->
                          </v-col>
                          <v-col v-if="bg_type == 'Solid Color'" class="mt-3">
                            <v-color-picker
                              dot-size="25"
                              mode="hexa"
                              swatches-max-height=""
                              v-model="bg_color"
                            ></v-color-picker>
                          </v-col>
                          </v-card-text>
                        </v-row>
                        <!-- BD Image -->
                        <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                          <v-toolbar-title><h3>BD Image</h3></v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                          <img :src="bd_image_preview" class="image-preview" title="Click to Change"  @click="bdOpenUpload" height="150px">
                          <input name="image" type="file" accept="image/.jpg, .png, .jpeg" id="bd-image" @change="bdUploadPreview" style="display: none">
                        </v-card-text>
                        <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                          <v-toolbar-title><h3>Play Store Image</h3></v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                          <img :src="play_store_preview" class="image-preview" title="Click to Change" @click="PlayOpenUpload"  width="150px">
                          <input name="image" type="file" accept="image/.jpg, .png, .jpeg" id="play-store" @change="PlayUploadPreview" style="display: none">
                        </v-card-text>
                        <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                          <v-toolbar-title><h3>App Store Image</h3></v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                          <img :src="app_store_preview" title="Click to Change" class="image-preview" @click="AppOpenUpload" width="150px">
                          <input name="image" type="file" accept="image/.jpg, .png, .jpeg" id="app-store" @change="AppUploadPreview" style="display: none">
                        </v-card-text>
                      </v-col>
                    </v-row>
                    <v-divider class="mt-12"></v-divider>
                    <v-card-actions>
                      <v-btn text>
                        Cancel
                      </v-btn>
                      <v-spacer></v-spacer>
                      <v-btn color="primary" text @click="submit">
                        Submit
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <!-- Seo Information End -->
  </v-col>
</v-row>
</template>

<script>
import Swal from 'sweetalert2'
export default {
  layout: 'backend',
  data: () => ({
    test: '',
    errorMessages: '',
    meta_title: '',
    title: '',
    bg_type:'Image',
    meta_description: '',
    meta_keywords: '',
    canonical_url: '',
    meta_type: '',
    meta_image_link: '',
    dialog: false,
    footer_about: '',
    app_store: '',
    play_store: '',
    linkedin: '',
    instagram: '',
    youtube: '',
    twitter: '',
    facebook: '',
    copyritght: '',
    display_name: '',
    website_name: '',
    footer_data: '',
    bg_image: '',
    bd_image: '',
    bg_color: '#000000FF',
    app_store_link: '',
    play_store_link: '',
    site_logo: '',
    setting_type: [
      { "title": "Image", 'key': 'image'},
      { "title": "Text", 'value': 'text'},
      {"title": "Text Area", 'key': 'text_area'},
    ],
    imagePreview: '/assets/images/image-plus.png',
    app_store_preview: '/assets/images/image-plus.png',
    bg_image_preview: '/assets/images/image-plus.png',
    bd_image_preview: '/assets/images/image-plus.png',
    play_store_preview: '/assets/images/image-plus.png',
    site_logo_preview: '/assets/images/no-logo.png',
  }),
  watch: {
    name() {
      this.errorMessages = ''
    },
  },
  created() {
    this.getSetting();
  },
  methods: {
    resetForm() {
      this.errorMessages = []
      this.formHasErrors = false

      Object.keys(this.form).forEach(f => {
        this.$refs[f].reset()
      })
    },
    // site_logo start
    siteLogoUpload(){
      document.getElementById("site-logo").click();
    },
    siteLogoPreview(e){
      var reader, files = e.target.files;
      if (files.length === 0){
        console.log("empty");
      }else{
        this.site_logo = files[0];
      }
      reader = new FileReader()
      reader.onload = (e)=>{
        this.site_logo_preview = e.target.result
      }
      reader.readAsDataURL(files[0])
    }, // site_logo end
    // bg_image start
    openUpload(){
      document.getElementById("bg-image").click();
    },
    uploadPreview(e){
      var reader, files = e.target.files;
      if (files.length === 0){
        console.log("empty");
      }else{
        this.bg_image = files[0];
      }
      reader = new FileReader()
      reader.onload = (e)=>{
        this.bg_image_preview = e.target.result
      }
      reader.readAsDataURL(files[0])
    }, // bg_image end
    // bd_image start
    bdOpenUpload(){
      document.getElementById("bd-image").click();
    },
    bdUploadPreview(e){
      var reader, files = e.target.files;
      if (files.length === 0){
        console.log("empty");
      }else{
        this.bd_image = files[0];
      }
      reader = new FileReader()
      reader.onload = (e)=>{
        this.bd_image_preview = e.target.result
      }
      reader.readAsDataURL(files[0])
    }, // bd_image end
    // play_store start
    PlayOpenUpload(){
      document.getElementById("play-store").click();
    },
    PlayUploadPreview(e){
      var reader, files = e.target.files;
      if (files.length === 0){
        console.log("empty");
      }else{
        this.play_store = files[0];
      }
      reader = new FileReader()
      reader.onload = (e)=>{
        this.play_store_preview = e.target.result
      }
      reader.readAsDataURL(files[0])
    }, // play_store end
    // app_store start
    AppOpenUpload(){
      document.getElementById("app-store").click();
    },
    AppUploadPreview(e){
      var reader, files = e.target.files;
      if (files.length === 0){
        console.log("empty");
      }else{
        this.app_store = files[0];
      }
      reader = new FileReader()
      reader.onload = (e)=>{
        this.app_store_preview = e.target.result
      }
      reader.readAsDataURL(files[0])
    }, // app_store end

    submit() {
      var formData = new FormData();

      if (this.site_logo.length != 0) {
        var siteLogo = document.querySelector('#site-logo');
        formData.append("site_logo", siteLogo.files[0]);
      }
      if (this.play_store.length != 0) {
        var playStore = document.querySelector('#play-store');
        formData.append("play_store", playStore.files[0]);
      }
      if (this.app_store.length != 0) {
        var appStore = document.querySelector('#app-store');
        formData.append("app_store", appStore.files[0]);
      }
      if (this.bg_image.length != 0) {
        var bgImage = document.querySelector('#bg-image');
        formData.append("bg_image", bgImage.files[0]);
      }
      if (this.bd_image.length != 0) {
        var bdImage = document.querySelector('#bd-image');
        formData.append("bd_image", bdImage.files[0]);
      }

      formData.append("website_name", this.website_name);
      formData.append("footer_about", this.footer_about);
      formData.append("copyritght", this.copyritght);
      formData.append("facebook", this.facebook);
      formData.append("twitter", this.twitter);
      formData.append("youtube", this.youtube);
      formData.append("instagram", this.instagram);
      formData.append("linkedin", this.linkedin);
      formData.append("play_store_link", this.play_store_link);
      formData.append("app_store_link", this.app_store_link);
      formData.append("bg_color", this.bg_color);
      formData.append("bg_type", this.bg_type);

      try {
        this.$axios.post('/settings', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then((res) => {
            if (res.data.length === 0 || res.data.error) {
              throw {
                statusCode: 404,
                message: "Setting not found",
              };
            }
            Swal.fire("Data Saved Successfully!");
            this.getSetting();
          })

      } catch (e) {
        this.error = e.result
      }
    },

    getSetting(){
      this.$axios
      .get("/settings")
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw {
            statusCode: 404,
            message: "Posts not found",
          };
        }
          this.website_name = res.data.data.find(obj => obj.key == 'website_name').value;
          this.footer_about = res.data.data.find(obj => obj.key == 'footer_about').value;
          this.copyritght = res.data.data.find(obj => obj.key == 'copyritght').value;
          this.facebook = res.data.data.find(obj => obj.key == 'facebook').value;
          this.twitter = res.data.data.find(obj => obj.key == 'twitter').value;
          this.youtube = res.data.data.find(obj => obj.key == 'youtube').value;
          this.instagram = res.data.data.find(obj => obj.key == 'instagram').value;
          this.linkedin = res.data.data.find(obj => obj.key == 'linkedin').value;
          this.twitter = res.data.data.find(obj => obj.key == 'twitter').value;
          this.play_store_link = res.data.data.find(obj => obj.key == 'play_store_link').value;
          this.app_store_link = res.data.data.find(obj => obj.key == 'app_store_link').value;

          try{
            this.site_logo_preview = res.data.data.find(obj => obj.key == 'site_logo').value;
          }catch(e){
            console.log('site_logo_preview: ',e.message);
          }
          try{
            this.play_store_preview = res.data.data.find(obj => obj.key == 'play_store').value;
          }catch(e){
            console.log('play_store_preview: ',e.message);
          }
          try{
            this.app_store_preview = res.data.data.find(obj => obj.key == 'app_store').value;
          }catch(e){
            console.log('app_store_preview: ',e.message);
          }
          try{
            this.bg_image_preview = res.data.data.find(obj => obj.key == 'bg_image').value;
          }catch(e){
            console.log('bg_image_preview: ',e.message);
          }
          try{
            this.bd_image_preview = res.data.data.find(obj => obj.key == 'bd_image').value;
          }catch(e){
            console.log('bd_image_preview: ', e.message);
          }
          this.bg_color = res.data.data.find(obj => obj.key == 'bg_color')?res.data.data.find(obj => obj.key == 'bg_color').value:'';
          this.bg_type = res.data.data.find(obj => obj.key == 'bg_type')?res.data.data.find(obj => obj.key == 'bg_type').value:'';
      })
    }
  },
}
</script>
<style scoped>
img{
  max-width: 500px;
}
</style>
