<template>
  <v-card>

    <v-dialog
      v-model="showSuccess"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text class="pt-2">
          {{loadingMsg ? loadingMsg : 'Please wait...'}}
          <v-row no-gutters dense>
            <v-col class="my-auto">
              <v-progress-linear
                indeterminate
                color="white"
                class="mb-0"
              >
              </v-progress-linear>
            </v-col>
            <v-col class="flex-grow-0">
              <v-btn class="indigo lighten-1" icon @click="showSuccess = false" small>
                <v-icon small>mdi-close</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="afterSave"
      hide-overlay
      class="transparent"
      persistent
      close-delay="300"
      width="300"
    >
      <v-alert
        type="success"
        prominent
        dismissible
      >
        <v-card-text>
          {{ afterSaveMsg || 'Menu Items saved Successfully!' }}
        </v-card-text>
      </v-alert>
    </v-dialog>
    <v-toolbar
      color="background3"
      flat
    >
      <v-icon>mdi-format-list-group</v-icon>
      <v-toolbar-title>Menus</v-toolbar-title>
    </v-toolbar>
    <v-sheet class="px-3">
      <v-row>
        <v-col cols="12" md="4">
          <v-toolbar flat dense color="background1">
            <small>Select a menu below</small>
          </v-toolbar>
          <v-divider/>
          <v-card flat tile @click="selectMenu(i)" v-for="(menu, i) in menus" :key="i">
            <v-card-title v-text="menu.name"></v-card-title>
            <v-card-subtitle class="pb-0">Type: {{menu.type}}</v-card-subtitle>
            <v-card-subtitle class="pt-0">Items: {{menu.items.length}}</v-card-subtitle>
            <v-divider/>
          </v-card>

          <v-card-actions class="background1">
            <v-btn color="primary" small @click.stop="menuDialog = true">+ Add New Menu</v-btn>
          </v-card-actions>

          <v-dialog
            v-model="menuDialog"
            max-width="400"
          >

            <v-card>
              <v-card-title>Create new Menu</v-card-title>
              <v-card-text>
                <v-text-field
                  label="Title"
                  v-model="menu.name"
                  :rules="[rules.required]"
                ></v-text-field>
                <v-select
                  v-model="menu.type"
                  :items="menuTypes"
                  item-text="title"
                  item-value="type"
                  label="Menu Type"
                  :rules="[rules.required]"
                ></v-select>
              </v-card-text>
              <v-card-actions>

                <v-btn
                  color="green darken-1"
                  text
                  @click="menuDialog = false , menu = {}"
                >
                  Close
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                  class="white--text"
                  color="green darken-1"
                  type="submit"
                  @click="onMenuSubmit"
                  :disabled="showSuccess || (!menu.name || !menu.type ? true : false)"
                >
                  {{(showSuccess) ? 'Saving...' : 'Save Menu'}}
                  <v-icon right>
                    mdi-content-save
                  </v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-col>
        <v-divider vertical></v-divider>
        <v-col cols="12" md="8">
          <v-card v-if="menuID">
            <v-card-title>Menu Items</v-card-title>
            <v-card-text v-if="menus[menuIndex].items.length">

              <draggable
                tag="transition-group"
                :list="menuItems"
                :componentData="componentData"
                :animation="200"
                class="list-group"
                draggable=".item"
                handle=".handle"
                ghost-class="ghost"
                :move="checkMove"
                @start="dragging = true"
                @end="endMove"
              >
                <v-row v-for="(item, i) in menuItems" :key="i" class="item">
                  <v-col cols="11">
                    <v-expansion-panels>
                      <v-expansion-panel>
                        <v-expansion-panel-header color="background1">
                          <v-icon small class="flex-grow-0 pr-3 handle">mdi-cursor-move</v-icon>
                          <span>{{ item.title ? item.title : item.model_title }}</span>
                          <v-spacer/>
                          <div class="flex-grow-0 mr-4">
                            <small v-if="menuItems[i].taxonomy" v-text="menuItems[i].taxonomy"></small>
                          </div>
                          <template v-slot:actions>
                            <v-icon class="icon">$expand</v-icon>
                          </template>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>

                          <v-text-field
                            label="Title"
                            v-model="menuItems[i].title"
                          ></v-text-field>
                          <v-select
                            v-model="menuItems[i].menuable_type"
                            :items="taxonomies"
                            item-text="title"
                            item-value="model"
                            label="Menu Item Type"
                          ></v-select>
                          <div v-if="menuItems[i].menuable_type">
                            <v-select
                              v-model="menuItems[i].model_id"
                              v-if="menuItems[i].menuable_type.split(/[\\]+/).pop() === 'Category'"
                              :items="categories.allCategory"
                              item-text="title"
                              item-value="id"
                              label="Select Category"
                            ></v-select>
                            <v-select
                              v-model="menuItems[i].model_id"
                              v-if="menuItems[i].menuable_type.split(/[\\]+/).pop() === 'Tag'"
                              :items="tags.allTag"
                              item-text="title"
                              item-value="id"
                              label="Select Tag"
                            ></v-select>
                            <v-divider/>
                            <v-sheet v-if="menuItems[i].menuable_type.split(/[\\]+/).pop() === 'Post'" outlined>
                              <v-select
                                v-model="menuItems[i].model_id"
                                :items="posts.data"
                                item-text="title"
                                item-value="id"
                                label="Select a Post"
                              ></v-select>
                              <v-list v-if="!loading" dense>
                                <v-list-item v-for="(post, index) in posts.data" :key="index" @click="selectPost(i, post.id, post.title)">
                                  <v-list-item-subtitle class="line-height-2" v-if="post.title !== undefined" v-text="post.title"></v-list-item-subtitle>
                                </v-list-item>
                              </v-list>
                              <v-skeleton-loader
                                v-else
                                type="list-item@4"
                              ></v-skeleton-loader>

                              <v-pagination
                                v-if="posts && posts.total > 0"
                                v-model="posts.current_page"
                                :length="posts.last_page"
                                total-visible="8"
                                circle
                                @input="next"
                              ></v-pagination>
                            </v-sheet>
                          </div>
                          <v-text-field
                            v-else
                            label="Link"
                            :rules="[rules.required, rules.url]"
                            v-model="menuItems[i].link"
                          ></v-text-field>
                        </v-expansion-panel-content>
                      </v-expansion-panel>
                    </v-expansion-panels>
                  </v-col>
                  <v-col>
                    <v-btn icon @click="removeAt(i)">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
                <div
                  slot="footer"
                  class="row"
                  key="footer"
                >
                  <v-col>
                    <v-btn color="accent" @click="add" small>
                      <v-icon>mdi-plus</v-icon>
                      Add More
                    </v-btn>
                  </v-col>
                </div>
              </draggable>
            </v-card-text>
            <v-card-text v-else>
              <p class="my-2">No items. Please add some items to the menu</p>
              <v-btn color="accent" @click="addFirst" small>
                <v-icon>mdi-plus</v-icon>
                Add First
              </v-btn>
              <v-divider class="my-3"/>
              <p class="my-2">Or you may delete this menu</p>
              <v-btn color="error" @click="deleteMenu(menus[menuIndex].id)" x-small>
                <v-icon x-small>mdi-close</v-icon>
                Delete
              </v-btn>
            </v-card-text>
            <v-card-actions>
              <v-btn
                color="secondary"
                @click="resetMenu()"
                small
              >
                <v-icon>mdi-history</v-icon>
                Reset
              </v-btn>
              <v-spacer/>
              <v-btn
                class="white--text"
                color="green darken-1"
                type="submit"
                @click="onSubmit"
                :disabled="showSuccess || menuItems.length < 1"
                small
              >
                {{showSuccess ? 'Saving...' : 'Save Menu Items'}}
                <v-icon right>
                  mdi-content-save
                </v-icon>
              </v-btn>
            </v-card-actions>

          </v-card>
        </v-col>
      </v-row>
    </v-sheet>
    <v-divider></v-divider>
  </v-card>
</template>

<script>
import qs from "qs";
import draggable from 'vuedraggable'
export default {
  name: "index",
  layout: 'backend',
  components: {
    draggable,
  },
  data: () => ({
    showSuccess: false,
    afterSave: false,
    afterSaveMsg: false,
    loadingMsg: null,
    error: null,
    menus: [],
    menuID: null,
    menuIndex: null,
    dragging: false,
    categories: null,
    tags: null,
    posts: null,
    loading: false,
    menuItems: null,
    response: null,
    menu: {},
    menuDialog: false,
    menuTypes: [
      {type: 'main_menu', title: 'Main Menu'},
      {type: 'footer_menu', title: 'Footer Menu'},
      {type: 'social_menu', title: 'Social Menu'},
      {type: 'other', title: 'Other'},
    ],
    componentData: {
      props: {
        type: "transition",
        name: "flip-list"
      }
    },
    taxonomies: [
      { model: 'App\\Models\\Post', title: 'Post' },
      { model: 'App\\Models\\Category', title: 'Category' },
      { model: 'App\\Models\\Tag', title: 'Tag' },
      { model: null, title: 'Link' },
    ],
    rules: {
      required: value => !!value || 'Required.',
      email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        return pattern.test(value) || 'Invalid e-mail.'
      },
      url: value => {
        const pattern = /^(ftp|http|https):\/\/[^ "]+$/
        return pattern.test(value) || 'Invalid URL.'
      },
    },
  }),
  created() {
    this.getMenuIndex();
    this.getCategories();
    this.getTags();
    this.getPostTitles();
  },
  methods: {
    async getMenuIndex() {
      this.showSuccess = true;
      this.loadingMsg = 'Fetching Menu Items...';
      try {
        const {data} = await this.$axios.get('/menus');
        this.menus = data.data;
        this.showSuccess = false;
      } catch (e) {
        this.error = e.data;
      }
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
    async getPostTitles(page=1, search= null) {
      this.loading = true;
      this.$axios
        .get("/frontend/post-titles",{
          params: {
            page: page,
            per_page: 4,
            search: search,
          }
        })
        .then((response) => {
          this.posts = response.data;
          this.loading = false;
        })
        .catch();
    },
    async upsert(params) {
      this.showSuccess = true;
      this.loadingMsg = 'Saving Data...';
      this.$axios
        .post("/menu_items/upsert",{
          params,
          paramsSerializer: params => {
            return qs.stringify(params)
          }
        })
        .then((response) => {
          this.response = response.data;
          this.showSuccess = false;
          this.afterSave = true;
          setTimeout(()=> {
            this.afterSave = false;
          }, 1000);
        })
        .catch();
    },
    async deleteMenu(id) {
      this.showSuccess = true;
      this.loadingMsg = 'Deleting...';
      this.$axios
        .post('/menus/'+id,{_method: 'delete'})
        .then((response) => {
          this.response = response.data;
          this.showSuccess = false;
          this.afterSaveMsg = 'Deleted Successfully';
          this.afterSave = true;
          this.menus = response.data.allMenu;
          setTimeout(()=> {
            this.afterSave = false;
          }, 1000);
        })
        .catch();
    },
    next (page) {
      console.log(page);
      this.getPostTitles(page);
    },
    selectPost(index, id, title) {
      this.menuItems[index].model_id = id;
      this.menuItems[index].model_title = title;
    },
    selectMenu(i) {
      this.menuIndex = i;
      this.menuID = this.menus[i].id;
      if(this.menus[i])
      this.menuItems = this.menus[i].items;
    },
    resetMenu() {
      this.getMenuIndex();
      setTimeout(()=> {
        if(this.menus[this.menuIndex]) {
          this.menuItems = this.menus[this.menuIndex].items;
        } else this.menuItems = [];
      }, 500);
    },
    add: function() {
      let lastItem = this.menuItems[this.menuItems.length - 1];
      this.menuItems.push({id: null, title: null, menu_id: lastItem.menu_id, link: null, menuable_type: null, menuable_id: null, order: lastItem.order+1, parameters: null, parent: null, taxonomy: null});
    },
    addFirst: function() {
      const items = [{id: null, title: null, menu_id: this.menuID, link: null, menuable_type: null, menuable_id: null, order: 1, parameters: null, parent: null, taxonomy: null}];
      this.menuItems = this.menus[this.menuIndex].items = items;
    },
    removeAt(idx) {
      this.menuItems.splice(idx, 1);
    },

    onSubmit() {
      const reformattedArray = this.menuItems.map((el, index, arr) => {
        return {id: el.id, title: el.title, menu_id:el.menu_id, link: el.link, menuable_type: el.menuable_type, menuable_id: el.model_id, order: el.order, parameters: el.parameters, parent: el.parent};
      });
      //this.menuItems = reformattedArray;
      this.upsert(reformattedArray);
    },

    async onMenuSubmit() {
      this.showSuccess = true;
      this.loadingMsg = 'Saving Data...';
      this.$axios
        .post("/menus",{
          name: this.menu.name,
          type: this.menu.type,
          paramsSerializer: params => {
            return qs.stringify(params)
          }
        })
        .then((response) => {
          this.response = response.data;
          this.showSuccess = false;
          this.afterSave = true;
          setTimeout(()=> {
            this.afterSave = false;
          }, 1000);
        })
        .catch();
    },

    replace: function() {
      this.list = [{ name: "Edgard", id: id++ }];
    },
    checkMove: function(e) {
      this.menuItems[e.draggedContext.index].order = e.draggedContext.futureIndex;
      // window.console.log("Current index: " + e.draggedContext.index);
      // window.console.log("Future index: " + e.draggedContext.futureIndex);
    },
    endMove: function(e) {
      this.dragging = false;
      this.menuItems.map((item, index) => {
        item.order = index + 1;
      })
    }
  }
}
</script>

<style scoped>
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}
.flip-list-move {
  transition: transform 0.5s;
}
.no-move {
  transition: transform 0s;
}
.line-height-2 {
  line-height: 2 !important;
}
</style>
