<template>
  <v-card>
    <v-toolbar color="indigo" dark dense class="font-weight-bold">
    <nuxt-link
      :to="{ name: 'post-post'}">
      <v-btn x-small color="primary" dark class="mb-2">
      <v-icon small class="mr-2">
        mdi-arrow-left-bold
      </v-icon>Back</v-btn>
    </nuxt-link>
      <v-toolbar-title>Post View</v-toolbar-title>

      <v-spacer></v-spacer>
      <v-btn x-small color="primary" dark class="mb-2" v-if="status == 1">
        Active
      </v-btn>
      <v-btn x-small color="#F44336" dark class="mb-2" v-else> Inactive </v-btn>
    </v-toolbar>
    <v-simple-table>
      <template v-slot:default>
<!--         {{post}}-->
        <thead>
          <tr>
            <th class="text-left">Heading</th>
            <th class="text-left">Designation</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td width="20%">Title</td>
            <td class="py-4">{{ title }}</td>
          </tr>
          <tr>
            <td width="20%">Short Description</td>
            <td class="truncate" v-if="short_description == 'null'"></td>
            <td class="truncate" v-else>{{ short_description }}</td>
          </tr>
          <tr>
            <td width="20%">Description</td>
            <td class="py-8" v-html="description"></td>
          </tr>
          <tr>
            <td width="20%">Image</td>
            <td class="py-8">
<!--                          {{posts['pictures']?posts[0]['pictures'][0]['thumbnail']:''}}-->
              <!--            {{pictures[0]['thumbnail']}}-->
              <v-img
                min-height="50"
                max-height="150"
                style="margin: 5px; border-radius: 10px"
                min-width="50"
                max-width="150"
                :src="pictures[0] ? pictures[0]['thumbnail'] : ''"
                alt="No Image"
              ></v-img>
            </td>
          </tr>
          <tr>
            <td width="20%">Image Title & Author</td>
            <td class="py-8">
              {{pictures}}
            </td>
          </tr>
          <tr>
            <td width="20%">Categories</td>
            <td class="py-8">
              <span v-for="category in category_id">
                <span> {{ category.title }}, </span>
              </span>
            </td>
          </tr>
          <tr>
            <td width="20%">Tags</td>
            <td class="py-8">
              <span v-for="tag in tag_id">
                <span> {{ tag.title }}, </span>
              </span>
            </td>
          </tr>
          <tr>
            <td width="20%">District Name</td>
            <td class="py-8">
              <span v-for="district in district_id">
                <span>
                  {{ district.title_bn }}
                </span>
              </span>
            </td>
          </tr>
          <tr>
            <td width="20%">Post Meta Information</td>
            <td class="py-8">
              <v-list-item v-for="(meta, index) in postMeta" :key="index">
                <v-list-item-icon>
                  <v-list-item-title v-html="meta.meta_key"></v-list-item-title>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title
                    v-html="meta.meta_value"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    errorMessages: "",
    title: "",
    formHasErrors: false,
    category_id: "",
    status: true,
    categories: [],
    statusCode: null,
    slug: "",
    short_description: "",
    description: "",
    pictures: "",
    tag_id: "",
    postMeta: "",
    district_id: "",
    imagePreview: "",
  }),

  methods: {},

  asyncData(context) {
    const AuthStr = "Bearer ".concat(context.$auth.strategy.token.get());
    return context.$axios
      .get("/posts/" + context.params.id + "/edit", {
        headers: {
          Authorization: AuthStr,
        },
      })
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw {
            statusCode: 404,
            message: "Post not found",
          };
        }
        return {
          post: res.data.post,
          title: res.data.post.title,
          id: res.data.post.id,
          slug: res.data.post.slug,
          short_description: res.data.post.short_description,
          description: res.data.post.description,
          pictures: res.data.post.pictures,
          status: res.data.post.status,
          seo: res.data.post.seo,
          category_id: res.data.post.categories,
          tag_id: res.data.post.tags,
          postMeta: res.data.post.postMeta,
          district_id: res.data.post.postDistrict,
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
          return context.error({
            statusCode: 404,
            message: error.message,
          });
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
td {
  border-right: 1px solid #3b3b3b;
}
td:last-child {
  border-right: 1px solid transparent;
}
.theme--dark.v-data-table
  > .v-data-table__wrapper
  > table
  > thead
  > tr:last-child
  > th {
  font-size: 18px;
  color: #fff;
}
.v-main__wrap .v-toolbar__title{
  margin-left: 13px;
  margin-bottom: 5px;
}
</style>
