<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="4" sm="5" class="text-center">
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-card style="padding: 20px; border-radius: 5px">
            <v-alert
              dense
              outlined
              :type="alert_type"
              dismissible
              v-model="alert_info"
            >
              {{ res }}
            </v-alert>
            <v-card-title class="text-center" style="display: block"
              >Image Upload</v-card-title
            >
            <img
              :src="imagePreview"
              class="image-preview"
              @click="openUpload"
              height="300px"
              width="300px"
            />

            <input
              name="image"
              type="file"
              id="file-field"
              @change="uploadPreview"
              style="display: none"
            />

            <v-text-field
              label="Name"
              @keyup="makeSlug()"
              v-model="name"
              :rules="[() => !!name || 'Name is required']"
              required
            ></v-text-field>

            <v-text-field
              label="Slug"
              name="slug"
              v-model="slug"
              required
            ></v-text-field>
            <br />
            <v-btn class="mt-3 mb-2" @click="storeImage"> Upload </v-btn>
          </v-card>
        </v-form>
      </v-col>

      <v-col md="8" sm="7" class="">
        <v-card style="border-radius: 5px">
          <v-card-title class="primary">
            <v-toolbar-title>Images</v-toolbar-title>
          </v-card-title>
          <v-card-title>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="images"
            :items-per-page="5"
            :search="search"
            class="elevation-1"
          >
            <template v-slot:item.small="{ item }">
              <v-img
                :lazy-src="item.small"
                height="auto"
                style="border-radius: 5px"
                width="150px"
                :src="item.small"
              ></v-img>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-icon color="red" small @click="deleteItem(item.id)" title="Delete">
                mdi-trash-can-outline
              </v-icon>
            </template>
            <template v-slot:item.url="{ item }">
              <v-btn small outlined color="gray lighten-3" @click="copyUrl(item.full)">
                Copy Url
                <v-icon small> mdi-content-copy </v-icon>
              </v-btn>
            </template>
            <template v-slot:item.updated_at="{ item }">
              <!-- {{ date }} -->
              <span>{{ new Date(item.updated_at).toLocaleDateString('en-US') }}</span>
            </template>
            <template v-slot:top>
              <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card>
                  <v-card-title class="text-h5"
                    >Are you sure you want to delete this item?</v-card-title
                  >
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete"
                      >Cancel</v-btn
                    >
                    <v-btn
                      color="blue darken-1"
                      text
                      @click="deleteItemConfirm(delete_id)"
                      >OK</v-btn
                    >
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </template>
            <template v-slot:no-data>
              <v-btn color="primary"> No Data found!!! </v-btn>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  layout: 'backend',
  data: () => ({
    valid: true,
    alert_info: false,
    alert_type: "warning",
    res: "",
    delete_id: null,
    dialog: false,
    dialogDelete: false,
    name: "",
    slug: "",
    image: "",
    errorMessages: "",
    imagePreview: "/assets/images/image-plus.png",
    search: "",
    headers: [
      {
        text: "Image",
        value: "small",
        align: "start",
        sortable: false,
        // width: "20%",
      },
      {
        text: "Name",
        value: "name",
        // width: "10%",
      },
      // {
      //   text: "Slug",
      //   value: "slug",
      //   sortable: false,
      //   // width: "10%",
      // },
      {
        text: " Url",
        value: "url",
        align: "center",
        // width: "30%",
      },
      {
        text: "Uploded On",
        value: "updated_at",
        align: "center",
        // width: "10%",
      },
      {
        text: "Actions",
        value: "actions",
        align: "center",
        sortable: false,
        // width: "10%",
      },
    ],
    images: [],
    alert_value: false,
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created () {
    this.getImages();
  },
  methods: {
    copyUrl(url){
      console.log(url);
      navigator.clipboard.writeText(url);
      // alert('Coppied: ' + url);
      var cp = document.getElementById("copyUrl");
      console.log(cp.children);
      cp.innerHTML = "Copied";

    },
    openUpload() {
      document.getElementById("file-field").click();
    },
    uploadPreview(e) {
      var reader,
        files = e.target.files;
      if (files.length === 0) {
        console.log("empty");
      } else {
        this.image = files[0];
      }
      reader = new FileReader();
      reader.onload = (e) => {
        this.imagePreview = e.target.result;
      };
      reader.readAsDataURL(files[0]);
    },
    makeSlug() {
      this.slug = this.name.trim().toLocaleLowerCase().replaceAll(" ", "-");
    },

    storeImage() {
      // console.log(this.image.length);
      if (
        this.name.length != 0 &&
        this.slug.length != 0 &&
        this.image.length != 0
      ) {
        var formData = new FormData();
        var image = document.querySelector("#file-field");
        // console.log('image length:   ', this.image.length);
        formData.append("image", image.files[0]);
        formData.append("name", this.name);
        formData.append("slug", this.slug);
        try {
          this.$axios
            .post("/store-image", formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((res) => {
              if (res.data.length === 0 || res.data.error) {
                throw {
                  statusCode: 404,
                  message: "Image not found",
                };
              }
              // console.log(res.data.data);
              this.images = res.data.data;
              this.imagePreview = "/assets/images/image-plus.png";
              this.name = null;
              this.slug = null;
              this.res = "Image Saved Successfully";
              this.alert_info = true;
              this.alert_type = "success";
            });
        } catch (e) {
          this.error = e.result;
        }
      } else {
        this.$refs.form.validate();
        if (
          this.name.length == 0 &&
          this.slug.length == 0 &&
          this.image.length == 0
        ) {
          this.res = "Please Select a Image and write Name";
          this.alert_info = true;
          this.alert_type = "warning";
        } else if (this.name.length == 0) {
          this.res = "Please enter Name";
          this.alert_info = true;
          this.alert_type = "warning";
          this;
        } else if (this.slug.length == 0) {
          this.res = "Slug required!";
          this.alert_info = true;
          this.alert_type = "warning";
        } else if (this.image.length == 0) {
          this.res = "Please Select Image";
          this.alert_info = true;
          this.alert_type = "warning";
        }
      }
    },

    editItem(item) {
      // this.name = this.images.indexOf(item);
      this.editedIndex = this.images.indexOf(item);
      this.editedItem = Object.assign({}, item);
      // this.dialog = true;
      // console.log('Called', this.images.indexOf(item));
    },

    deleteItem(id) {
      this.delete_id = id;
      this.dialogDelete = true;
    },

    deleteItemConfirm(id) {
      // console.log(id);
      try {
        this.$axios.get("/delete-image/" + id).then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw {
              statusCode: 404,
              message: "Image not found",
            };
          }
          // console.log('Images: ', res.data.data);
          this.images = res.data.data;
        });
      } catch (e) {
        this.error = e.result;
      }
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    async getImages() {
      try {
        const {data} = await this.$axios.get('/images');
        this.images = data.data;
      } catch (e) {
        this.error = e.data;
      }
    },
  },

/*  asyncData(context) {
    const AuthStr = "Bearer ".concat(context.$auth.strategy.token.get());
    return context.$axios
      .get("/images", {
        headers: {
          Authorization: AuthStr,
        },
      })
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw {
            statusCode: 404,
            message: "Posts not found",
          };
        }
        // console.log('Images: ', res.data.data);
        return {
          images: res.data.data,
        };
      })
      .catch((error) => {
        if (error.response) {
          // Request made and server responded
          //this.errorResponse = error.response.data;
          this.errorCode = error.response.status;
        } else if (error.request) {
          // The request was made but no response was received
          //console.log(error.request);
        } else {
          // Something happened in setting up the request that triggered an Error
          return {
            error: error.message,
          };
        }
      });
  }*/
};
</script>

<style>
img {
  cursor: pointer;
}

.row {
  justify-content: center;
}

table {
  font-size: 20px;
}
</style>
