<template>
  <v-form ref="form" v-model="valid" lazy-validation>
    <v-row dense>
      <v-col class="col-12 background4">
        <v-card color="background4">
          <v-card-title>
            Create Post
            <v-spacer></v-spacer>
            <v-btn icon @click="drawer = !drawer">
              <v-icon v-if="drawer">mdi-chevron-right</v-icon>
              <v-icon v-else>mdi-chevron-left</v-icon>
            </v-btn>
          </v-card-title>
          <v-divider/>
          <p v-if="error_titles.length">
            <v-alert
              close-text="Close Alert"
              dark
              dismissible
              type="error"
              v-for="error in error_titles"
              :key="error.message"
            >
              {{ error }}
            </v-alert>
          </p>
          <p v-if="error_slugs.length">
            <v-alert
              close-text="Close Alert"
              dark
              dismissible
              type="error"
              v-for="error in error_slugs"
              :key="error.message"
            >
              {{ error }}
            </v-alert>
          </p>
          <v-card-text>
            <v-text-field
              ref="title"
              v-model="title"
              dense
              :rules="[() => !!title || 'Title is required']"
              :error-messages="errorMessages"
              label="Title"
              @keyup="makeSlug()"
              placeholder="Add Title"
              outlined
              required
            ></v-text-field>
            <v-row>
              <v-col class="flex-grow-0">
                <div class="mt-2 body-1">
                  Permalink:
                </div>
              </v-col>
              <v-col>
                <v-text-field
                  label="Slug"
                  name="slug"
                  class="post-slug"
                  dense
                  :rules="[() => !!slug || 'Slug is required']"
                  :error-messages="errorMessages"
                  v-model="slug"
                  required
                >

                  <div
                    slot="prepend"
                    color="green"
                  >
                    <client-only>
                      <span>{{$config.baseURL}}/</span>
                    </client-only>
                  </div>
                </v-text-field>
              </v-col>
            </v-row>

            <h4 class="mt-4">Description</h4>
            <ClientOnly>
              <VueEditor
                v-model="description">
              </VueEditor>
            </ClientOnly>

            <v-textarea
              label="Post Excerpt/ Short Description"
              class="mt-4"
              outlined
              dense
              rows="2"
              v-model="short_description"
            ></v-textarea>
          </v-card-text>
          <v-divider/>
          <!--  Meta Info -->

          <v-col>
            <v-card  class="mt-3">
              <v-card-title class="text-body-1 pa-3">Meta Info</v-card-title>
              <v-divider></v-divider>

              <v-card-text class="py-0">

                <v-container fluid>
                  <v-row
                    dense
                    v-for="(textField, i) in textFields"
                    :key="i"
                  >
                    <v-col class="col-5">
                      <v-text-field
                        label="Key"
                        outlined
                        dense
                        v-model="textField.meta_key"
                      ></v-text-field>
                    </v-col>
                    <v-col class="col-5">
                      <v-text-field
                        label="Value"
                        outlined
                        dense
                        v-model="textField.meta_value"
                      ></v-text-field>
                    </v-col>
                    <v-col class="col-2">
                      <v-btn small @click="remove(i)" class="error mt-1">
                        <v-icon left>
                          mdi-trash-can
                        </v-icon>
                        delete
                      </v-btn>
                    </v-col>
                  </v-row>
                  <v-row dense>
                    <v-col cols="2" offset="10">
                      <v-btn
                        @click="add"
                        small
                        class="primary"
                      >
                        <v-icon left>
                          mdi-card-plus
                        </v-icon>
                        add
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
            </v-card>
          </v-col>
          <v-divider/>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="createPost">
              Submit
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <v-navigation-drawer
      app
      clipped
      right
      v-model="drawer"
      color="background5"
    >
      <v-sheet color="background5">
        <!-- Published Type -->
        <v-card flat color="transparent">
          <v-card-title>Publish</v-card-title>
          <v-divider></v-divider>

          <v-card-text class="py-0">
            <v-switch
              v-model="status"
              :label="status ? 'Published' : 'Not Published'"
            ></v-switch>
          </v-card-text>
        </v-card>
        <v-divider/>

        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">Featured Image</v-card-title>
          <v-divider></v-divider>

          <v-card-text class="py-0">
              <p v-if="error_images.length">
                <v-alert
                  close-text="Close Alert"
                  dark
                  dismissible
                  dense
                  type="error"
                  v-for="error in error_images"
                  :key="error.message"
                >
                  {{ error }}
                </v-alert>
              </p>
              <p>
                <img
                  :src="imagePreview"
                  class="image-preview"
                  @click="openUpload"
                  height="200px"
                  width="100%"
                />
                <input
                  name="image"
                  type="file"
                  id="file-field"
                  @change="uploadPreview"
                  style="display: none"
                />
                <!-- image upload end -->
              </p>
            <v-text-field
              ref="img_title"
              v-model="img_title"
              dense
              label="Image Title"
              placeholder="Add Title"
              outlined
              required
            ></v-text-field>
            <v-text-field
              ref="img_author"
              v-model="img_author"
              dense
              label="Image Author"
              placeholder="Author Name"
              outlined
              required
            ></v-text-field>
          </v-card-text>
        </v-card>
        <v-divider/>

        <!-- Categories -->
        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">
            Categories
            <v-spacer></v-spacer>
            <v-btn x-small @click.stop="dialog = true">+ Add</v-btn>
          </v-card-title>
          <v-divider></v-divider>

          <v-card-text>
            <v-autocomplete
              :items="categories['activeCategory']"
              item-text="title"
              label="Category"
              v-model="category_id"
              item-value="id"
              multiple
              small-chips
              dense
              chips
            ></v-autocomplete>
          </v-card-text>
          <v-dialog v-model="dialog" max-width="290">
            <v-card flat>
              <v-card-text>
                <v-text-field
                  ref="category_title"
                  v-model="category_title"
                  :rules="[() => !!category_title || 'Title is required']"
                  :error-messages="errorMessages"
                  label="Title"
                  @keyup="makeSlug()"
                  placeholder=""
                  required
                ></v-text-field>
                <v-text-field
                  label="Slug"
                  name="category_slug"
                  v-model="category_slug"
                  :rules="[() => !!category_slug || 'Slug is required']"
                  :error-messages="errorMessages"
                  required
                ></v-text-field>
                <v-switch
                  v-model="category_status"
                  :label="
                          category_status
                            ? 'Category Active'
                            : 'Category Deactive'
                        "
                ></v-switch>
                <v-switch
                  v-model="category_isMenu"
                  :label="
                          category_isMenu ? 'Menu Active' : 'Menu Deactive'
                        "
                ></v-switch>
              </v-card-text>
              <v-divider class="mt-12"></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="submit">
                  Submit
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card>
        <v-divider/>

        <!-- Categories end -->
        <!-- Tags -->
        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">
            Tags
            <v-spacer></v-spacer>
            <v-btn x-small @click.stop="tagdialog = true">+ Add</v-btn>
          </v-card-title>
          <v-divider/>

          <v-card-text v-if="tags['activeTag']">
            <v-autocomplete
              :items="tags['activeTag']"
              item-text="title"
              label="Tag"
              v-model="tag_id"
              item-value="id"
              multiple
              small-chips
              dense
              chips
            ></v-autocomplete>
          </v-card-text>
          <v-dialog v-model="tagdialog" max-width="290">
            <v-card flat>
              <v-card-text>
                <v-text-field
                  ref="tag_title"
                  v-model="tag_title"
                  :rules="[() => !!tag_title || 'Title is required']"
                  :error-messages="errorMessages"
                  label="Title"
                  @keyup="makeSlug()"
                  placeholder=""
                  required
                ></v-text-field>
                <v-text-field
                  label="Slug"
                  name="tag_slug"
                  v-model="tag_slug"
                  :rules="[() => !!tag_slug || 'Slug is required']"
                  :error-messages="errorMessages"
                  required
                ></v-text-field>
                <v-switch
                  v-model="tag_status"
                  :label="tag_status ? 'Tag Active' : 'Tag Deactive'"
                ></v-switch>
              </v-card-text>
              <v-divider class="mt-12"></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="tagsubmit">
                  Submit
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card>
        <v-divider/>
        <!-- Tags end -->

        <!-- District News -->
        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">District</v-card-title>
          <v-divider/>
          <v-card-text v-if="districts">
            <v-autocomplete
              :items="districts"
              :filter="districtFilter"
              item-text="title_bn"
              item-value="id"
              v-model="district_id"
              label="District"
              small-chips
              dense
              chips
            ></v-autocomplete>
          </v-card-text>
        </v-card>
        <v-divider/>
        <!-- District News End -->

        <!-- Youtube Link -->
        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">Youtube Video ID</v-card-title>
          <v-divider/>
          <v-card-text>
            <v-text-field
              v-model="video_link"
              label="Video ID"
              dense
              hint="https://www.youtube.com/watch?v="
              clearable
            ></v-text-field>
            <span
              border="top"
              dense
              color="info"
              style="font-size:12px"
              outlined
            >Only video ID from youtube link.
              i.e: https://www.youtube.com/watch?v=<code class="red--text">El7eCeEkZNc</code>
            </span>
          </v-card-text>
        </v-card>
        <v-divider/>
        <!-- Youtube Link End -->

        <!-- Social Media -->
        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">Social Media Link</v-card-title>
          <v-divider/>
          <v-card-text v-if="socials">
            <v-autocomplete
              :items="socials"
              color="white"
              item-text="title"
              item-value="id"
              v-model="social_id"
              label="Social Media"
              multiple
              small-chips
              dense
              chips
            ></v-autocomplete>
          </v-card-text>
        </v-card>
        <v-divider/>
        <!-- Social Media Link -->

        <!-- Seo Information -->
        <v-card class="mt-3" flat color="transparent">
          <v-card-title class="pa-3">SEO Information</v-card-title>
          <v-divider/>
          <v-card-text ref="SEOform">
            <p v-if="error_meta_titles.length">
              <v-alert
                close-text="Close Alert"
                dark
                dismissible
                type="error"
                v-for="error in error_meta_titles"
                :key="error.message"
              >
                {{ error }}
              </v-alert>
            </p>
            <v-text-field
              ref="meta_title"
              v-model="meta_title"
              :rules="[
                          () => !!meta_title || 'Meta Title is required',
                        ]"
              :error-messages="errorMessages"
              label="Meta Title"
              placeholder=""
              required
            ></v-text-field>
            <v-text-field
              ref="meta_description"
              v-model="meta_description"
              label="Meta Description"
            ></v-text-field>

            <v-text-field
              ref="meta_keywords"
              v-model="meta_keywords"
              label="Meta Keywords"
            ></v-text-field>

            <v-text-field
              ref="canonical_url"
              v-model="canonical_url"
              label="Canonical URL"
            ></v-text-field>

            <v-text-field
              ref="meta_type"
              v-model="meta_type"
              label="Meta Type"
            ></v-text-field>

            <v-text-field
              ref="meta_image_link"
              v-model="meta_image_link"
              label="Meta Image link"
            ></v-text-field>
          </v-card-text>
        </v-card>
        <!-- Seo Information End -->
      </v-sheet>

    </v-navigation-drawer>
  </v-form>
</template>
<script>
import { VueEditor } from "vue2-editor";
import Swal from 'sweetalert2'

export default {
  ssr: false,
  layout: 'backend',
  imagePreview: "/assets/images/image-plus.png",
  components: {
    VueEditor
  },
  data: () => ({
    valid: true,
    errorMessages: "",
    title: "",
    img_title: "",
    img_author: "",
    category_title: "",
    tag_title: "",
    formHasErrors: false,
    category_id: [],
    status: true,
    category_status: true,
    tag_status: true,
    category_isMenu: true,
    slug: "",
    category_slug: "",
    tag_slug: "",
    imagePreview: "/assets/images/image-plus.png",
    short_description: "",
    description: "",
    thumbnail: "",
    categories: [],
    meta_key: "",
    meta_value: "",
    meta_title: "",
    meta_description: "",
    meta_keywords: "",
    canonical_url: "",
    meta_type: "",
    meta_image_link: "",
    tags: [],
    tag_id: [],
    social_id: [],
    hasSaved: false,
    model: null,
    textFields: [],
    video_link: "",
    districts: [],
    district_id: [],
    tab: null,
    dialog: false,
    tagdialog: false,
    error_titles: [],
    error_slugs: [],
    error_meta_titles: [],
    error_images: [],
    socials: [],
    drawer: true,
  }),

  watch: {
    name() {
      this.errorMessages = "";
    },
    model(val) {
      if (val.length > 5) {
        this.$nextTick(() => this.model.pop());
      }
    },
  },
  created() {
    this.getCategories();
    this.getTags();
    this.getDistricts();
  },

  mounted(e) {
    this.slug = this.makeSlug();
    this.category_slug = this.makeSlug();
    this.tag_slug = this.makeSlug();
  },

  methods: {
    submit() {
      if (this.category_title.length && this.category_slug.length !== 0) {
        try {
          this.$axios.post("/categories", {
            title: this.category_title,
            slug: this.category_slug,
            status: this.category_status ? 1 : 0,
            isMenu: this.category_isMenu ? 1 : 0,
          });
        } catch (e) {
          this.error = e.response.data.message;
        }
      } else {
        alert("Please Category section fill the required Fields");
      }
      this.close();
    },
    close() {
      this.dialog = false;
      this.tagdialog = false;
    },
    tagsubmit() {
      if (this.tag_title.length && this.tag_slug.length !== 0) {
        try {
          this.$axios.post("/tags", {
            title: this.tag_title,
            slug: this.tag_slug,
            status: this.tag_status ? 1 : 0,
          });
        } catch (e) {
          this.error = e.response.data.message;
        }
      } else {
        alert("Please Title and SLug fill the required Fields");
      }
      this.close();
    },
    add() {
      this.textFields.push({
        meta_key: "",
        meta_value: "",
      });
    },
    remove(index) {
      this.textFields.splice(index, 1);
    },
    save() {
      this.hasSaved = true;
    },
    async getCategories() {
      this.$axios
        .get("/categories")
        .then((response) => {
          this.categories = response.data;
        })
        .catch();
    },
    async getDistricts() {
      this.$axios
        .get("/get-districts")
        .then((response) => {
          this.districts = response.data;
        })
        .catch();
    },
    async getTags() {
      this.$axios
        .get("/tags")
        .then((response) => {
          this.tags = response.data;
        })
        .catch();
    },
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
        this.thumbnail = files[0];
      }
      reader = new FileReader();
      reader.onload = (e) => {
        this.imagePreview = e.target.result;
        // this.logo = files[0]
      };
      reader.readAsDataURL(files[0]);
    },

    createPost(e) {
      if (
        this.title.length &&
        this.slug.length &&
        this.meta_title.length &&
        this.thumbnail.length !== 0
      ) {
        var formData = new FormData();
        var imagefile = document.querySelector("#file-field");
        formData.append("thumbnail[]", imagefile.files[0])
        formData.append("img_title", this.img_title);
        formData.append("img_author", this.img_author);
        formData.append("title", this.title);
        formData.append("slug", this.slug);
        formData.append("short_description", this.short_description);
        formData.append("description", this.description);
        formData.append("video_link", this.video_link);
        formData.append("district_id", this.district_id);
        this.category_id.forEach(function (element) {
          formData.append("category_id[]", element);
        });
        this.tag_id.forEach(function (element) {
          formData.append("tag_id[]", element);
        });
        this.social_id.forEach(function (element) {
          formData.append("social_id[]", element);
        });
        this.textFields.forEach(function (element, index) {
          formData.append("meta_key[" + index + "]", element.meta_key);
          formData.append("meta_value[" + index + "]", element.meta_value);
        });
        formData.append("status", this.status ? 1 : 0);
        formData.append("meta_title", this.meta_title);
        formData.append("meta_description", this.meta_description);
        formData.append("canonical_url", this.canonical_url);
        formData.append("meta_type", this.meta_type);
        formData.append("meta_image_link", this.meta_image_link);
        try {
          this.$axios.post("/posts", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }).then((res) => {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Post has been saved",
                showConfirmButton: false,
                timer: 1500,
              });
              this.$router.push("/backend/posts/");
            })
            .catch((error) => {

              if (error.response.status === 422){
                  // console.log(error.response.data.errors.slug[0]);
                  Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: error.response.data.errors.slug[0],
                    showConfirmButton: false,
                    timer: 2000,
                  });
              }

            });
        } catch (e) {
          this.error = e.result;
        }
      } else {
        // this.error = e.result;
        this.$refs.form.validate();
        Swal.fire(
          "Just need a little bit more info...",
          "Please make sure to fill up these fields: Title, Slug, Featured Image and Meta Title",
          "warning"
        );
        // alert("Please fill the required Title, Slug, Image, Meta Title Fields");
        this.error_titles = [];
        // this.error_slugs = [];
        // this.error_meta_titles = [];
        this.error_images = [];
        if (!this.title) {
          this.error_titles.push("Title is required.");
          // Swal("Title is required.");
        } else if (!this.slug) {
          // this.error_slugs.push("Slug is required.");
          // Swal("Slug is required.");
          Swal.fire("Oops...", "Slug is missing!", "error");
        } else if (!this.thumbnail) {
          this.error_images.push("Image is required.");
          // Swal("Image is required.");
          Swal.fire("Oops...", "Image is missing!", "error");
        } else if (!this.meta_title) {
          // this.error_meta_titles.push("Meta Title is required.");
          // Swal("Meta Title is required.");
          Swal.fire("Oops...", "Meta Title is missing!", "error");
        }
      }
    },

    makeSlug() {
      this.slug = this.title
        .trim()
        .toLocaleLowerCase()
        .replaceAll(" ", "-")
        .replaceAll(":", "")
        .replaceAll(";", "")
        .replaceAll(",", "");
      this.tag_slug = this.tag_title
        .trim()
        .toLocaleLowerCase()
        .replaceAll(" ", "-")
        .replaceAll(":", "")
        .replaceAll(";", "")
        .replaceAll(",", "");
      this.category_slug = this.category_title
        .trim()
        .toLocaleLowerCase()
        .replaceAll(" ", "-")
        .replaceAll(":", "")
        .replaceAll(";", "")
        .replaceAll(",", "");
    },
    districtFilter(item, queryText, itemText) {
      const textOne = item.title_en.toLowerCase();
      const textTwo = item.title_bn.toLowerCase();
      const searchText = queryText.toLowerCase();
      return (
        textOne.indexOf(searchText) > -1 || textTwo.indexOf(searchText) > -1
      );
    },
  },
  asyncData(context) {
    const AuthStr = "Bearer ".concat(context.$auth.strategy.token.get());
    return context.$axios
      .get("/socials", { headers: { Authorization: AuthStr } })
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw {
            statusCode: 404,
            message: "Social Link not found",
          };
        }
        console.log(res.data);
        return { socials: res.data.data };
      })
      .catch((error) => {
        if (error.response) {
          this.errorCode = error.response.status;
        } else if (error.request) {
        } else {
          // Something happened in setting up the request that triggered an Error
          return {
            error: error.message,
          };
        }
      });
  },
};
</script>
<style>
.swal2-title, .swal2-html-container {
  font-family: "Roboto";
}
.post-slug .v-input__prepend-outer {
  margin-top: 7px;
  margin-right: 2px;
}
.post-slug input {
  font-size: 12px;
}
</style>


