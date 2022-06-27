<template>
  <v-row>
    <v-col class="col-12">
      <v-container fluid>
        <v-row dense>
          <v-col class="col-12">
            <v-card>
              <v-toolbar
                class="font-weight-bold"
                color="indigo"
                dark
                dense
              >
                <v-toolbar-title>Users</v-toolbar-title>
              </v-toolbar>
              <!-- {{users}} -->
              <v-data-table
                :headers="headers"
                :items="users"
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
                  <nuxt-link to="/backend/users/create">
                    <v-btn
                          color="primary font-weight-bold"
                          dark
                          class="ml-3 mr-3"
                          small
                        >
                          Add User
                    </v-btn>
                  </nuxt-link>
                  <!-- <v-btn icon :x-small="sizeXsm" :small="sizeSm" @click="print" class="mr-1 mr-lg-3">
                    <v-icon>{{mdiPrinter}}</v-icon>
                  </v-btn> -->
                </v-toolbar>
              </template>

              <template v-slot:item.action="{ item }">
                <nuxt-link :to="{ name: 'users-update-id', params: { id: item.id }}">
                  <v-icon
                    small
                    class="mr-2"
                  >
                    mdi-pencil
                  </v-icon>
                </nuxt-link>
                <v-icon
                  @click="deleteUser(item.id)"
                  color="#F44336"
                  v-if="item.status==0"
                  >
                    mdi-trash-can
                </v-icon>
              </template>
              <template v-slot:item.status="{ item }">
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
              <template v-slot:item.created_at="{ item }">
                <span>{{ new Date(item.created_at).toLocaleDateString('en-US') }}</span>
              </template>

              </v-data-table>
            </v-card>
          </v-col>
          <!-- Delete Dialogue -->
          <v-dialog v-model="dialogDeleteUser" max-width="500px">
            <v-card>
              <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="deleteConfirm">OK</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
           <!-- End Delete Dialogue -->
        </v-row>
      </v-container>
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
        dialogDelete: false,
        headers: [
          {
            text: 'Name',
            align: 'start',
            sortable: true,
            value: 'name',
            width: "%",
            overflow: "hidden",
          },
          {
            text: 'Email',
            value: 'email',
            align: 'center',
            width: "%",
            overflow: "hidden",
          },
          {
            align:'center',
            text: 'Created At',
            value: 'created_at',
            sortable: false,
            width: "%",
            overflow: "hidden",
          },
          {
            align:'center',
            text: 'Action',
            value: 'action',
            sortable: false,
            width: "%",
            overflow: "hidden",
          },
        ],
      users: [],
      mdiPrinter: mdiPrinter,
      dialogDeleteUser: false,

    }),
    created () {
        this.getUsers();
    },

    methods: {
      print () {
        // Pass the element id here
        this.$htmlToPaper('printMe');
      },

      async getUsers() {
        try {
          const {data} = await this.$axios.get('/users');
          this.users = data.users;
        } catch (e) {
          this.error = e.data;
        }
      },

      deleteUser(id){
        this.id = id,
        this.dialogDeleteUser = true
      },

      deleteConfirm () {

        try {
          this.$axios.delete('/users/'+this.id);

          this.closeDelete();
        } catch (e) {
          this.error = e.response
        }
      },

      closeDelete () {
        this.getusers()
        this.dialogDeleteUser = false;

        // this.router.push('users/users');
        // this.deleteItem();
        // this.$nextTick(() => {
        //   this.editedItem = Object.assign({}, this.defaultItem);
        //   this.editedIndex = -1;
        // });
      },
    },

    computed: {
      sizeSm: function () {
        return this.$vuetify.breakpoint.name == 'xs' ? false : true;
      },
      sizeXsm: function () {
        return this.$vuetify.breakpoint.name == 'xs' ? true : false;
      },
    },
  }

</script>
<style>
.v-application a{
  text-decoration: none;
}
</style>
