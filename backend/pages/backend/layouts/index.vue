<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="12" sm="12" class="">
        <v-card style="border-radius: 5px">
          <!-- {{layouts[0]['structure']['image']}} -->

          <v-data-table
            :headers="headers"
            :items="layouts"
            :search="search"
            :items-per-page="10"
            sort-by="calories"
            class="elevation-1"
            style="font-size: 20px"
          >
            <template v-slot:item.image="{ item }">
              <v-container class="content-center" align="center">
                <v-img
                  :lazy-src="defaultImage"
                  height="auto"
                  width="400"
                  :src="item.image"
                  :alt="item.name"
                ></v-img>
              </v-container>
            </template>
            <template v-slot:item.title="{ item }">
              <v-container class="content-center" align="center">
                <v-chip class="mt-1" color="#00954c">
                  {{ item.title }}
                </v-chip>
              </v-container>
            </template>

            <template v-slot:item.status="{ item }">

                <v-switch
                :key="item.path"
                v-model="item.path"
                :label="item.status === 1 ? 'Active' : 'Inactive'"
                :value="item.status === 0 ? 1 : 0"
                @change="setActive(item.path)"
                v-if="item.status === 1"
                disabled
              ></v-switch>
              <v-switch
                v-else
                :key="item.path"
                :v-model="path"
                :label="item.status === 1 ? 'Active' : 'Inactive'"
                @change="setActive(item.path)"
              ></v-switch>
            </template>
            <template v-slot:item.layouts="{ item }">
              <nuxt-link
                :to="{ name: 'backend-layouts-view-id', params: { id: item.id }}">
                <v-icon
                  small
                  class="mr-2"
                >
                  mdi-pencil
                </v-icon>
              </nuxt-link>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
      <!-- <v-col cols="12" md="7" sm="7" class="text-center">
        <v-card v-if="model != null" style="border-radius: 5px">
          <v-card-title>
            Layout Preview
          </v-card-title>
        </v-card>
        <v-card v-if="model != null" style="border-radius: 5px">
          <v-img
            :src="editedItem.image"
            class="image-preview"
            aspect-ratio="1.6"
          ></v-img>
        </v-card>
      </v-col>  -->
    </v-row>
  </v-container>
</template>

<script>
export default {
  layout: 'backend',
  data: () => ({
    search: "",
    editLayout: "",
    headers: [
      {
        text: "Layouts",
        value: "image",
        align: "start",
        sortable: false,
        width: "40%"
      },
      {
        text: "Name",
        value: "title",
        sortable: false,
        align: "center",
        width: "20%"
      },
      {
        text: "Actions",
        value: "status",
        align: "start",
        sortable: false,
        width: "20%"
      },
      {
        text: "Modify",
        value: "layouts",
        sortable: false,
        align: "center",
        width: "20%"
      },
    ],
    editedItem: {
      image: "/assets/images/layout-4.jpg",
      name: "",
      url: "",
    },
    defaultImage: "/assets/images/layout-4.jpg",
    path: "",
    selected_layout: "Default",
    layouts: [],
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },

    localAttrs() {
      const attrs = {};

      if (this.variant === "default") {
        attrs.absolute = false;
        attrs.fixed = false;
      } else {
        attrs[this.variant] = true;
      }
      return attrs;
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

  created() {
  },

  methods: {
    // Active Layout
    setActive(path) {
      try {
        this.$axios
        .get("layout/status/" + path)
        .then((res) => {
          // console.log(res.data.data);
          this.layouts = res.data.data;
         })
      } catch (error) {
        if (error.response) {
            // Request made and server responded
            //this.errorResponse = error.response.data;
            this.errorCode = error.response.status;
          } else if (error.request) {
            // The request was made but no response was received
            //console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            return { error: error.message, };
          }
      }
    },
  },
  asyncData(context) {
    const AuthStr = "Bearer ".concat(context.$auth.strategy.token.get());
    return (
      context.$axios
        //.get(context.env.apiRoot + 'post-path?path=' + encodeURI(context.params.path))
        .get("/layouts", {
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
          // console.log(res.data.data);
          return {layouts: res.data.data};
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
            return { error: error.message, };
          }
        })
    );
  },
};
</script>
