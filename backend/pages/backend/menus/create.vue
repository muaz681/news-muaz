<template>
  <v-row>
    <v-col cols="12" md="4">
      <v-card>
        <v-card-title>Create new Menu</v-card-title>
        <v-card-text>
          <v-text-field
            label="Title"
            v-model="menu.name"
          ></v-text-field>
          <v-select
            v-model="menu.type"
            :items="menuTypes"
            item-text="title"
            item-value="type"
            label="Menu Type"
          ></v-select>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            class="white--text"
            color="green darken-1"
            type="submit"
            @click="onMenuSubmit"
            :disabled="showSuccess"
          >
            {{showSuccess ? 'Saving...' : 'Save Menu'}}
            <v-icon right>
              mdi-content-save
            </v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col cols="12" md="8">
      <v-card>
        <v-card-title>Menu Index</v-card-title>
        <v-card-text>

          <v-card flat tile v-for="(menu, i) in menus" :key="i">
            <v-card-title v-text="menu.name"></v-card-title>
            <v-card-subtitle class="pb-0">Type: {{menu.type}}</v-card-subtitle>
            <v-card-subtitle class="pt-0">Items: {{menu.items.length}}</v-card-subtitle>
            <v-divider/>
          </v-card>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "create",
  layout: 'backend',
  data: () => ({
    showSuccess: false,
    afterSave: false,
    loadingMsg: null,
    menus: null,
    error: null,
    menu: {},
    menuTypes: [
      {type: 'main_menu', title: 'Main Menu'},
      {type: 'footer_menu', title: 'Footer Menu'},
      {type: 'social_menu', title: 'Social Menu'},
      {type: 'other', title: 'Other'},
    ],
  }),
  created() {
    this.getMenuIndex();
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
  }

}
</script>

<style scoped>

</style>
