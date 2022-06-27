<template>
  <v-form ref="form">
    <!-- Left Side -->
    <v-row dense>
      <v-col class="col-12">
        <v-card>
          <v-card-title>Create Post</v-card-title>
          <v-divider/>
          <p v-if="errors.length">
            <v-alert
              close-text="Close Alert"
              dark
              dismissible
              type="error"
              v-for="error in errors"
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
                  :outlined="enableSlug"
                  :rules="[() => !!slug || 'Slug is required']"
                  :error-messages="errorMessages"
                  v-model="slug"
                  required
                  :readonly="!enableSlug"
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
              <v-col>
                <v-switch v-model="enableSlug" label="Edit" color="primary" class="mt-2" />
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
              <v-card-title class="text-body-1 pa-3 blue lighten-5">Meta Info</v-card-title>
              <v-divider></v-divider>

              <v-card-text class="py-0">

                <v-container fluid>
                  <div v-if="post['postMeta']">
                    <v-row
                      dense
                      v-for="(textField, i) in post['postMeta']"
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
                  </div>
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
            <v-btn color="primary" text @click="submit">
              Submit
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
<!-- test -->
    <v-navigation-drawer
      app
      clipped
      right
    >
      <v-sheet>
        <!-- Published Type -->
        <v-card flat class="mt-3">
          <v-card-title class="text-body-1 pa-3 pink lighten-5">Publish</v-card-title>
          <v-divider></v-divider>

          <v-card-text class="py-0">
            <v-switch
              v-model="status"
              :label="status ? 'Published' : 'Not Published'"
            ></v-switch>
          </v-card-text>
        </v-card>
        <v-divider/>

        <v-card class="mt-3" flat>
          <v-card-title class="text-body-1 pa-3 teal lighten-5">Featured Image</v-card-title>
<!--          {{imagePreview[0]['img_author']}}-->
<!--          {{img_title}}-->
          <v-divider></v-divider>

          <v-card-text class="py-0">

            <v-col class="col-12">
              <!-- image upload start -->
              <v-img
                :lazy-src="imageToPreview"
                max-height="200"
                width="100%"
                :src="imagePreview[0]['thumbnail']"
                @click="openUpload"
              ></v-img>
              <input
                name="image"
                type="file"
                id="file-field"
                @change="uploadPreview"
                style="display: none"
              />
            </v-col>

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
        <v-card class="mt-3" flat>
          <v-card-title class="text-body-1 pa-3 indigo lighten-5">
            Categories
            <v-spacer></v-spacer>
            <v-btn x-small @click.stop="dialog = true">+ Add</v-btn>
          </v-card-title>
          <v-divider></v-divider>

          <v-card-text>
            <v-autocomplete
              :items="categories['activeCategory']"
              color="white"
              item-text="title"
              label="Categories"
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
                  @keyup="makeCategorySlug()"
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
                <v-btn color="primary" text @click="categorySubmit">
                  Submit
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card>
        <v-divider/>

        <!-- Categories end -->
        <!-- Tags -->
        <v-card flat class="mt-3">
          <v-card-title class="text-body-1 pa-3 blue lighten-5">
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
                  @keyup="makeTagSlug()"
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
        <v-card flat class="mt-3">
          <v-card-title class="text-body-1 pa-3 purple lighten-5">District</v-card-title>
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
        <v-card flat class="mt-3">
          <v-card-title class="text-body-1 pa-3 red lighten-5">Youtube Video ID</v-card-title>
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
        <v-card flat class="mt-3">
          <v-card-title class="text-body-1 pa-3 orange lighten-5">Social Media Link</v-card-title>
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
        <v-card class="mt-3" flat>
          <v-card-title class="text-body-1 pa-3 green lighten-5">SEO Information</v-card-title>
          <v-divider/>
          <v-card-text ref="form">
            <v-text-field
              ref="seo['meta_title']"
              v-model="seo['meta_title']"
              :rules="[
                            () =>
                              !!seo['meta_title'] || 'Meta Title is required',
                          ]"
              :error-messages="errorMessages"
              label="Meta Title"
              placeholder=""
              required
            ></v-text-field>

            <v-text-field
              ref="meta_title"
              v-model="meta_title"
              :rules="[() => !!meta_title || 'Meta Title is required',]"
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
  components: {
    VueEditor
  },
  data: () => ({
    enableSlug: false,
    errorMessages: "",
    title: "",
    category_title: "",
    tag_title: "",
    formHasErrors: false,
    category_id: [],
    status: true,
    categories: [],
    statusCode: null,
    category_status: true,
    tag_status: true,
    category_isMenu: true,
    slug: "",
    category_slug: "",
    tag_slug: "",
    id: "",
    short_description: "",
    description: "",
    thumbnail: "",
    img_title: "",
    img_author: "",
    meta_key: "",
    meta_value: "",
    seo: "",
    meta_title: "",
    meta_description: "",
    meta_keywords: "",
    canonical_url: "",
    meta_type: "",
    meta_image_link: "",
    imageToPreview: "/assets/images/image-plus.png",
    imagePreview: "/assets/images/image-plus.png",
    hasSaved: false,
    model: null,
    tags: [],
    tag_id: [],
    social_id: [],
    post_metas: [],
    district_id: "",
    textFields: [],
    video_link: "",
    districts: [],
    socials: [],
    errors: [],
    dialog: false,
    tagdialog: false,
  }),

  watch: {
    name() {
      this.errorMessages = "";
    },
  },
  created() {
    this.getCategories();
    this.getTags();
    this.getDistricts();
    this.getSocials();
  },

  methods: {
    makeCategorySlug() {
      this.category_slug = this.category_title
        .trim()
        .toLocaleLowerCase()
        .replaceAll(" ", "-")
        .replaceAll(":", "")
        .replaceAll(";", "")
        .replaceAll(",", "");
    },
    makeTagSlug() {
      this.tag_slug = this.tag_title
        .trim()
        .toLocaleLowerCase()
        .replaceAll(" ", "-")
        .replaceAll(":", "")
        .replaceAll(";", "")
        .replaceAll(",", "");
    },
    add() {
      this.post["postMeta"].push({
        meta_key: "",
        meta_value: "",
      });
    },
    remove(index) {
      this.post["postMeta"].splice(index, 1);
    },
    save() {
      this.hasSaved = true;
    },
    async getDistricts() {
      this.$axios
        .get("/get-districts")
        .then((response) => {
          this.districts = response.data;
        })
        .catch();
    },
    async getCategories() {
      this.$axios
        .get("/categories")
        .then((response) => {
          this.categories = response.data;
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
    async getSocials() {
      this.$axios
        .get("/socials")
        .then((response) => {
          this.socials = response.data.data;
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
        this.imageToPreview = e.target.result;
        this.imagePreview[0]["thumbnail"] = e.target.result;
      };
      reader.readAsDataURL(files[0]);
    },

    submit() {
      // console.log(this.thumbnail);
      if (this.title.length && this.slug.length !== 0) {
        var formData = new FormData();
        var imagefile = document.querySelector("#file-field");
        if(this.thumbnail.length !== 0) {
          formData.append("thumbnail[]", imagefile.files[0]);
        }
        formData.append("img_title", this.img_title);
        formData.append("img_author", this.img_author);
        formData.append("_method", "PUT");
        formData.append("id", this.$route.params.id);
        formData.append("title", this.title);
        formData.append("slug", this.slug);
        formData.append("short_description", this.short_description);
        formData.append("description", this.description);
        formData.append("video_link", this.video_link);
        formData.append("district_id", this.district_id);
        this.category_id.forEach(function (element) {
          formData.append("category_id[]", element);
        });
        this.post["postMeta"].forEach(function (element, index) {
          formData.append("meta_key[" + index + "]", element.meta_key);
          formData.append("meta_value[" + index + "]", element.meta_value);
        });
        this.tag_id.forEach(function (element) {
          formData.append("tag_id[]", element);
        });
        this.social_id.forEach(function (element) {
          formData.append("social_id[]", element);
        });
        formData.append("status", this.status ? 1 : 0);
        formData.append("meta_title", this.seo["meta_title"]);
        formData.append("meta_description", this.seo["meta_description"]);
        formData.append("canonical_url", this.seo["canonical_url"]);
        formData.append("meta_type", this.seo["meta_type"]);
        formData.append("meta_image_link", this.seo["meta_image_link"]);
        try {
          this.$axios.post("/posts/" + this.$route.params.id, formData, {
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
        } catch (e) {
          this.error = e.result;
        }
      } else {
        // alert("Please fill the required Fields");
        Swal.fire(
          "Just need a little bit more info...",
          "Please make sure to fill up these fields: Title, Slug, Featured Image and Meta Title",
          "warning"
        );
        this.errors = [];
        if (!this.title) {
          this.errors.push("Title is required.");
        } else if (!this.slug) {
          this.errors.push("Slug is required.");
        }
      }
    },
    categorySubmit() {
      if (this.category_title.length && this.category_slug.length !== 0) {
        try {
          this.$axios.post("/categories", {
            title: this.category_title,
            slug: this.category_slug,
            status: this.category_status ? 1 : 0,
            isMenu: this.category_isMenu ? 1 : 0,
          })
            .then(response => {
              this.categories = response.data;
              let category_id = null;
              if (category_id = response.data.allCategory.find(({ slug }) => slug === this.category_slug)){
                this.category_id.push(category_id);
              }
            })
            .catch((error) => {
              console.error(error);
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
          })
            .then(response => {
              this.tags = response.data;
              let tag_id = null;
              if (tag_id = response.data.allTag.find(({ slug }) => slug === this.tag_slug)){
                this.tag_id.push(tag_id);
              }
            })
            .catch((error) => {
              console.error(error);
            })
        } catch (e) {
          this.error = e.response.data.message;
        }
      } else {
        alert("Please Title and SLug fill the required Fields");
      }
      this.close();
    },
    clear (){
      this.title = ''
      this.slug = ''
      this.thumbnail = null
      this.short_description = ''
      this.img_title = ''
      this.img_author = ''
      this.description = ''
      this.video_link = ''
      this.district_id = ''
      this.category_id = ''
      this.postMeta = ''
      this.tag_id = ''
      this.social_id = ''
      this.meta_title = ''
      this.meta_description = ''
      this.canonical_url = ''
      this.meta_type = ''
      this.meta_image_link = ''
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
      .get("/posts/" + context.params.id + "/edit", {
        headers: { Authorization: AuthStr },
      })
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw { statusCode: 404, message: "Post not found" };
        }
        return {
          post: res.data.post,
          title: res.data.post.title,
          id: res.data.post.id,
          slug: res.data.post.slug,
          short_description: res.data.post.short_description,
          description: res.data.post.description,
          video_link: res.data.post.video_link,
          img_title: res.data.post.pictures[0].img_title,
          img_author: res.data.post.pictures[0].img_author,
          imagePreview: res.data.post.pictures,
          status: res.data.post.status,
          seo: res.data.post.seo,
          category_id: res.data.post.categories.map((x) => x.id),
          tag_id: res.data.post.tags.map((x) => x.id),
          social_id: res.data.post.socials.map((x) => x.id),
          postMeta: res.data.post.postMeta,
          district_id: res.data.post.postDistrict.map((x) => x.id),
        };
      })
      .catch((error) => {
        if (error.response) {
          // Request made and server responded
          this.errorCode = error.response.status;
        } else if (error.request) {
          // The request was made but no response was received
        }
        if (error.statusCode === 404) {
          return context.error({ statusCode: 404, message: error.message });
        } else {
          // Something happened in setting up the request that triggered an Error
          return { error: error.message };
          return context.error({ statusCode: 500, message: error.message });
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
.post-slug.v-text-field--outlined.v-input--dense.v-text-field--outlined > .v-input__control > .v-input__slot,
.post-slug .v-input__slot {
  min-height: 32px;
}
.post-slug input {
  font-size: 12px;
}
</style>
