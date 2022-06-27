<template>
  <v-row dense>
    <v-col class="col-12">
      <v-alert v-if="error">
        <h1>{{errorCode}}</h1>
        <p>{{ error.data.message }}</p>
      </v-alert>
      <v-card v-else>
        <v-card-title>
          All Posts
          <v-spacer></v-spacer>
          <NuxtLink to="/backend/posts/create">
            <v-btn
              color="primary"
              dark
              class="mr-3 ml-3 font-weight-bold"
              small
            >
              Add Post
            </v-btn>
          </NuxtLink>
        </v-card-title>
        <v-divider/>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="posts"
            :items-per-page="15"
            hide-default-footer
            :search="search"
            id="printMe"
          >

            <template v-slot:top>
              <v-toolbar
                flat
              >
                <v-toolbar-title>List</v-toolbar-title>
                <v-divider
                  class="mx-4"
                  inset
                  vertical
                ></v-divider>
                <!-- <v-spacer></v-spacer> -->
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                <!-- <v-spacer></v-spacer> -->
              </v-toolbar>
            </template>
            <template v-slot:item.pictures="{ item }">
              <v-img
                min-height="50"
                max-height="150"
                style="margin:5px;
                    border-radius:10px;"
                min-width="50"
                max-width="150"
                :src="item.pictures[0]?item.pictures[0]['thumbnail']:''"
                alt="No Image"
              ></v-img>
            </template>
            <template v-slot:item.posts="{ item }">
              <nuxt-link :to="{ name: 'backend-posts-view-id', params: { id: item.id }}">
                <v-icon
                  small
                  class="mr-2"
                >
                  mdi-eye
                </v-icon>
              </nuxt-link>
              <nuxt-link :to="{ name: 'backend-posts-update-id', params: { id: item.id }}">
                <v-icon
                  small
                  class="mr-2"
                >
                  mdi-pencil
                </v-icon>
              </nuxt-link>
              <v-icon
                @click="deletePost(item.id)"
                color="#F44336"
                v-if="item.status==0"
              >
                mdi-trash-can
              </v-icon>
            </template>
            <template v-slot:item.categories="{ item }" >
              <v-chip x-small v-for="category in item.categories" :key="category.message">
                {{ category.title }}
              </v-chip>
            </template>
            <template v-slot:item.tags="{ item }">
              <v-chip x-small v-for="tag in item.tags" :key="tag.message">
                {{ tag.title }}
              </v-chip>
            </template>
            <template v-slot:item.status="{ item }" >
              <v-btn
                x-small
                color="primary"
                dark
                class="mb-2"
                v-if="item.status==1"
              >
                Active
              </v-btn>
              <v-btn
                x-small
                color="#F44336"
                dark
                class="mb-2"
                v-else
              >
                Inactive
              </v-btn>
            </template>

          </v-data-table>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-pagination
            v-model="pagination.current"
            :length="pagination.total"
            :total-visible="9"
            @input="onPageChange"
          ></v-pagination>
        </v-card-actions>
      </v-card>
      <!-- Delete Dialogue -->
      <v-dialog v-model="dialogDelete" max-width="500px">
        <v-card>
          <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="deletePostConfirm">OK</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <!-- End Delete Dialogue -->

    </v-col>
  </v-row>
</template>
<script>
import {mdiPrinter,} from "@mdi/js";
import Vue from 'vue';
import VueHtmlToPaper from 'vue-html-to-paper';
const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css'
  ]
}
Vue.use(VueHtmlToPaper, options);
  export default {
    layout: 'backend',
    data: () => ({
      search: '',
      postId: '',
      errorCode: null,
      error: null,
      headers: [
        {
          text: 'Title',
          align: 'start',
          sortable: false,
          value: 'title',
          width: "15%",
          overflow: "hidden",
        },
        {
          align: 'center' ,
          text: 'Image',
          value: 'pictures',
          width: "20%",
          overflow: "hidden",
        },
        {
          align: 'center' ,
          text: 'Category',
          value: 'categories',
          width: "10%",
          overflow: "hidden",
        },
        {
          align: 'center' ,
          text: 'Category/Tag',
          value: 'tags',
          width: "10%",
          overflow: "hidden",
        },
        {
          align: 'center' ,
          text: 'Status',
          value: 'status',
          width: "10%",
          overflow: "hidden",
        },
        {
          text: 'Actions',
          value: 'posts',
          sortable: false,
          width: "10%",
          overflow: "hidden",
        },
      ],
      // page: links.data.meta.from,

      posts: [],
      data:[],
      mdiPrinter: mdiPrinter,
      dialogDelete: false,
      links: null,
      pagination: {
                current: 1,
                total: 0
            }
    }),

    created () {
      this.getPosts();
    },

    methods: {

      async getPosts() {

        try {
          const {data} = await this.$axios.get('/posts');

          this.posts = data.data;
          // console.log(data);
        } catch (error) {
          if (error.response) {
            // Request made and server responded
            //this.errorResponse = error.response.data;
            return { errorCode: error.response.status, error: error.response }
          } else if (error.request) {
            // The request was made but no response was received
            //console.log(error.request);
          }else {
            // Something happened in setting up the request that triggered an Error
            return {error: error.message};
            return context.error({ statusCode: 500, message: error.message })
          }
        }
      },

      deletePost(id){
        this.id = id,
        this.dialogDelete = true
      },

      deletePostConfirm () {
        try {
          this.$axios.delete('/posts/'+ this.id ).then(
            this.closeDelete(),
            this.getPosts(),
            this.$router.push('/backend/post/')
          )
        } catch (e) {
          this.error = e.response
        }
      },

      closeDelete () {
        this.dialogDelete = false,
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      print () {
        // Pass the element id here
        this.$htmlToPaper('printMe');
      },
      getPostPaginate() {
          this.$axios.get('/posts?page=' + this.pagination.current)
              .then(response => {
                  this.posts = response.data.data;
                  this.pagination.current = response.data.meta.current_page;
                  this.pagination.total = response.data.meta.last_page;
              });
      },
      onPageChange() {
          this.getPostPaginate();
      }
    },
    //New
    asyncData(context) {
      const AuthStr = 'Bearer '.concat(context.$auth.strategy.token.get());
      return context.$axios
        //.get(context.env.apiRoot + 'post-slug?slug=' + encodeURI(context.params.slug))
        .get('/posts', { headers: { Authorization: AuthStr } })
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw({ statusCode: 404, message: 'Posts not found' })
          }
          // console.log(res);
          return { posts: res.data.data, pagination: res.data.meta

          }
        })
        .catch(error => {
          if (error.response) {
            // Request made and server responded
            //this.errorResponse = error.response.data;
            return { errorCode: error.response.status, error: error.response }
          } else if (error.request) {
            // The request was made but no response was received
            //console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            return {error: error.message};
          }
        });
    },
    //End
    computed: {
      sizeSm: function () {
        return this.$vuetify.breakpoint.name == 'xs' ? false : true;
      },
      sizeXsm: function () {
        return this.$vuetify.breakpoint.name == 'xs' ? true : false;
      },
      // say: function (message) {
      //   alert(message)
      // }
    },
    mounted() {
        this.getPostPaginate();
        // console.log(this.posts);
    },
  }
</script>
<style>
.v-application a{
  text-decoration: none;
}
.theme--dark.v-pagination .v-pagination__item {
    background: #363636;
    color: #FFFFFF;
}
</style>
