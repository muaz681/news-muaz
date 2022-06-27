<template>
  <v-container fluid>
    <v-form class="row" ref="form" v-model="valid" lazy-validation>
      <!-- Left Side -->
      <v-col class="col-">
        <v-card class="mx-auto">
          <v-toolbar color="indigo" class="font-weight-regular" dark dense>
            <v-toolbar-title>Social Link Information</v-toolbar-title>
          </v-toolbar>

          <v-container fluid>
            <v-row dense>
              <v-col class="col-12">
                <v-card>
                  <v-card-text>
                    <v-text-field
                      ref="title"
                      v-model="title"
                      :rules="[() => !!title || 'Title is required']"
                      :error-messages="errorMessages"
                      label="Title"
                      @keyup="makeSlug()"
                      placeholder=""
                      required
                    ></v-text-field>
                    <v-text-field
                      label="Slug"
                      name="slug"
                      v-model="slug"
                      required
                    ></v-text-field>
                    <v-text-field
                      label="URL"
                      :rules="[() => !!url || 'URL required']"
                      name="url"
                      v-model="url"
                      required
                    ></v-text-field>

                    <v-switch
                      prepend-icon="mdi-"
                      v-model="status"
                      :label="status ? 'Active' : ' Deactive'"
                    ></v-switch>

                    <v-row class="d-flex content-center">
                      <v-col cols="" class="display: contents;">
                        <!-- image upload start -->
                        <img
                          :src="imagePreview"
                          class="image-preview"
                          @click="openUpload"
                          height="150px"
                        />
                        <input
                          name="image"
                          type="file"
                          id="file-field"
                          accept="image/.jpg, .png, .jpeg"
                          @change="uploadPreview"
                          style="display: none"
                        />
                        <!-- image upload end -->
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-divider class="mt-12"></v-divider>
                  <v-card-actions>
                    <v-btn text> Cancel </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" text @click="submit"> Submit </v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
    </v-form>
  </v-container>
</template>
<script>
import Swal from 'sweetalert2'
// import vue2Dropzone from "vue2-dropzone";
// import "vue2-dropzone/dist/vue2Dropzone.min.css";
export default {
  imagePreview: "/assets/images/image-plus.png",
  data: () => ({
    valid: true,
    errorMessages: "",
    title: "",
    slug: "",
    logo: "",
    url: "",
    imagePreview: "/assets/images/image-plus.png",
    formHasErrors: false,
    status: true,
    images: [],
    dropzoneOptions: {
      url: "https://httpbin.org/post",
      thumbnailWidth: 200,
      maxFilesize: 1.0,
      maxFile: 1,
      headers: { "My-Awesome-Header": "header value" },
    },
  }),
  components: {
    vueDropzone: vue2Dropzone,
  },

  watch: {
    name() {
      this.errorMessages = "";
    },
  },
  mounted(e) {
    this.slug = this.makeSlug();
  },

  methods: {
    openUpload() {
      document.getElementById("file-field").click();
    },
    uploadPreview(e) {
      // console.log('Working');
      var reader,
        files = e.target.files;
      if (files.length === 0) {
        console.log("empty");
      } else {
        this.logo = files[0];
      }
      reader = new FileReader();
      reader.onload = (e) => {
        this.imagePreview = e.target.result;
        // this.logo = files[0]
      };
      reader.readAsDataURL(files[0]);
    },

    submit() {
      if (this.title.length && this.slug.length && this.url.length !== 0) {
        var formData = new FormData();
        var imagefile = document.querySelector("#file-field");
        formData.append("logo", imagefile.files[0]);
        formData.append("title", this.title);
        formData.append("slug", this.slug);
        formData.append("url", this.url);
        formData.append("isActive", this.status ? 1 : 0);
        try {
          this.$axios
            .post("/socials", formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((res) => {
              this.$axios.get("/socials").then((res) => {
                if (res.data.length === 0 || res.data.error) {
                  throw { statusCode: 404, message: "Social Link not found" };
                }
                this.socials = res.data.data;
              });
              Swal("Data saved successfully!");
              this.$router.push("/social/social");
            });
        } catch (e) {
          this.error = e.result;
        }
      } else {
        this.$refs.form.validate();
      }
    },
    makeSlug() {
      this.slug = this.title.trim().toLowerCase().replaceAll(" ", "-");
    },
  },
};
</script>
<style>
.row {
  justify-content: center;
}
table {
  font-size: 20px;
}
</style>
