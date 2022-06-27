<template>
  <v-row ref="form">
    <v-col class="col">
      <v-container fluid>
        <v-row dense>
          <v-col class="col-12">
            <v-card
              class="mx-auto"
            >
              <v-toolbar
                color="indigo"
                class="font-weight-regular"
                dark dense
              >
                <nuxt-link
                  :to="{ name: 'backend-layouts-view-id', params: { id: $route.params.layout_id }}">
                  <v-btn color="indigo">
                  <v-icon small class="mr-2">
                    mdi-arrow-left-bold
                  </v-icon>Back</v-btn>
                </nuxt-link>
                <v-spacer></v-spacer>
                <v-btn text> Widget Space : {{$route.params.widget_space_id}} </v-btn>

              </v-toolbar>
              <v-container fluid >
                <v-row dense>
                  <v-col class="col-12">
                    <v-card>
                      <v-card-text>
                        <!-- Widget Select Start -->
                        <v-row align="center" dense>
                          <v-col
                            cols="10"
                          >
                          <!-- {{ widget }} -->
                            <v-select
                              :items="widgets"
                              :item-text="'name'"
                              :item-value="'id'"
                              v-model= "widget_id"
                              label="Select a Widget"
                              @change="changeData(widget_id)"
                              outlined
                            ></v-select>
                          </v-col>
                          <v-col cols="2">
                            <v-switch v-model="widget_status" :false-value="0" :true-value="1" label="Widget Status" class="mt-n4"></v-switch>
                          </v-col>
                        </v-row>
                        <v-row align="center" dense v-if="widgetObject">
<!--                          {{title}}-->
                          <v-col
                            class="col-12"
                            v-if="widgetObject.title_required"
                          >
                            <!-- {{ data_limit }} -->
                            <v-text-field
                              v-model= "title"
                              label="Enter Title"
                              outlined
                            ></v-text-field>
                          </v-col>

                        <!-- Category -->

                          <v-col
                            class="col-6"
                            v-if="widgetObject.selected_category"
                          >
                            <v-select
                              :items="category_taxonomy"
                              :item-text="'title'"
                              :item-value="'key'"
                              v-model= "selected_category"
                              @change="getCategoryTaxonomy"
                              label="Select a Category"
                              outlined
                            ></v-select>
                          </v-col>

                          <v-col
                            class="col-6"
                            v-if="widgetObject.selected_category"
                          >
                            <v-select
                              :items="cat_res"
                              :item-text="'title'"
                              :item-value="'id'"
                              v-model="cat_data"
                              label="Select a Category Type Data"
                              outlined
                              small-chips
                            ></v-select>
                          </v-col>
                        <!-- Category End -->

                          <v-col
                            v-if="widgetObject.taxonomy_selectable"
                          >
                          <!-- {{ taxonomy['1'] }} -->
                            <v-select
                              :items="taxonomy"
                              :item-text="'title'"
                              :item-value="'key'"
                              v-model= "selected_taxonomy"
                              @change="getTaxonomy"
                              label="Select a Taxonomy"
                              outlined
                            ></v-select>
                          </v-col>

                          <v-col
                            v-if="widgetObject.taxonomy_selectable"
                          >
                            <v-select
                              :items="tax_res"
                              :item-text="'title'"
                              :item-value="'id'"
                              :multiple="multiple_select"
                              v-model="data"
                              label="Select a Data"
                              outlined
                              small-chips
                            ></v-select>
                          </v-col>

                          <v-col
                            v-if="widgetObject.limit_required"
                          >
                          <!-- {{ data_limit }} -->
                            <v-text-field
                              v-model= "data_limit"
                              label="Enter Limit"
                              type="number"
                              outlined
                            ></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row align="center" dense v-if="widgetObject">
                          <v-col>
                            <v-switch v-model="show_title" false-value="0" true-value="1" label="Show Title" class="mt-n4"></v-switch>
                          </v-col>
                          <v-col>
                            <v-switch v-model="show_excerpt" false-value="0" true-value="1" label="Show Excerpt" class="mt-n4"></v-switch>
                          </v-col>
                        </v-row>
                        <v-row align="center" dense v-if="widgetObject">
                          <v-col
                            v-if="widgetObject.widget_type==='html'"
                          >
                            <v-textarea
                              v-model= "data"
                              label="Enter HTML Data"
                              placeholder="
                              you can add HTML content here like:
                              <h1> Happy Birthday </h1>
                              <p> Paragraph One </p>"
                              outlined
                            ></v-textarea>
                          </v-col>
                        </v-row>
                      </v-card-text>
                      <v-divider class=""></v-divider>
                      <v-card-actions>

                        <v-spacer></v-spacer>
                        <v-btn
                          color="red"
                          text
                          @click="clear()"
                        >
                          Clear
                        </v-btn>
                        <v-btn
                          color="primary"
                          text
                          @click="submit($route.params.layout_id)"
                        >
                          Submit
                        </v-btn>

                      </v-card-actions>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-col>

    <v-overlay :value="loadingOverlay">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>
  </v-row>
</template>
<script>
export default {
  layout: 'backend',
  data: () => ({
    widget: null,
    widgets: [],
    widgetObject: {},
    taxonomy: [
        {"title" : "Category", 'key' : 'category'},
        {"title" : "Tag", 'key' : 'tag'},
        {"title" : "Post", 'key' : 'post'},
        {"title" : "Video", 'key' : 'video'},
      ],
    category_taxonomy: [
        {"title" : "Category", 'key' : 'category'},
        {"title" : "Post", 'key' : 'post'},
      ],
    selected_taxonomy: null,
    selected_category: null,
    taxonomy_value:null,
    data_limit:'',
    title: '',
    tax_res: [],
    cat_res: [],
    data:[],
    layout_id:'',
    header_id:'',
    widget_id: null,
    selected_post:'',
    selected_image:'',
    tag_id:'',
    tag_limit:'',
    category_id:'',
    category_limit:'',
    single_post_id:'',
    post: [],
    widget_status: 1,
    widget_type:'',
    current_widget: null,
    show_excerpt: 0,
    show_title: 1,
    autoUpdate: true,
    multiple_select: true,
    loadingOverlay: false,
    cat_data: [],
  }),

  mounted() {
    this.getWidgets();
    // this.getCategoryTaxonomy();
  },

  methods: {
    changeData(widget_id){
      this.selected_post = null;
      this.data = null;
      this.cat_data = null;
      this.data_limit = null;
      this.show_excerpt = null;
      this.show_title = null;
      this.data_type = null;
      this.title = null;
      this.widgetObject = this.widgets.find(obj => obj.id == this.widget_id);

      this.widget_type = this.widgetObject.widget_type;
      if (this.widgetObject.widget_type == 'html') {
        this.selected_taxonomy = 'html'
        this.selected_category = 'html'
      } else{
        this.selected_taxonomy = null;
        this.selected_category = null;
      }
    },
    getTaxonomy(selected_taxonomy){
        this.$axios
        .get("/l-w-s",{ params: { taxonomy: selected_taxonomy} })
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw { statusCode: 404, message: "Taxonomy not found",};
          }
          // console.log(res.data);
          this.tax_res =  res.data;
          this.data = [];
        })
    },
    getCategoryTaxonomy(selected_category){
        this.$axios
        .get("/l-w-s",{ params: { category_taxonomy: selected_category} })
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw { statusCode: 404, message: "Category Taxonomy not found",};
          }
          // console.log(res.data);
          this.cat_res =  res.data;
          this.cat_data = [];
        })
    },
    getWidgets(){
      this.loadingOverlay = true;
      this.$axios
      .get("/widgets", {})
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw { statusCode: 404, message: "widgets not found", };
        }
        this.widgets =  res.data.data;

        this.getCurrentWidget();
        this.loadingOverlay = false;
      })
    },
    getCurrentWidget(){
      this.loadingOverlay = true;
      if(this.$route.params.id == null) {

      } else
      {
        this.$axios
          .get('/layout-widgets/'+this.$route.params.id+'/edit', { })
          .then((res) => {
            // console.log("Layout-widgets: ",res.data.data.widget_id);
            if (res.data.length === 0 || res.data.error) {
              throw { statusCode: 404, message: "widgets not found",};
            }
            this.current_widget = res.data.data;
            this.current_widget = res.data.cat_data;
            this.widget_id = res.data.data.widget_id;
            this.widgetObject = this.widgets.find(obj => obj.id == this.widget_id);
            this.widget_type = res.data.data.widget_settings.widget_type;
            this.data_limit = res.data.data.widget_settings.data_limit;
            this.show_excerpt = res.data.data.widget_settings.show_excerpt;
            this.show_title = res.data.data.widget_settings.show_title;
            this.title = res.data.data.title;
            this.widget_status = res.data.data.status;
            this.selected_taxonomy = res.data.data.widget_settings.taxonomy;
            this.selected_category = res.data.data.widget_settings.category_taxonomy;
            const tax = res.data.data.widget_settings.taxonomy;
            const tax_cat = res.data.data.widget_settings.category_taxonomy;

            if(Array.isArray(res.data.data.widget_settings.data)){
              const res_data = res.data.data.widget_settings.data;
              res_data.forEach(element => {
                this.data.push(parseInt(element));
              });

            }else {
              const res_data = res.data.data.widget_settings.data;
              var d = res_data.split(',');
              d.forEach(element => {
                this.data.push(parseInt(element));
              });
            }
            if (tax != 'null') {
              this.$axios
              .get("/l-w-s",{ params: { taxonomy: tax} })
              .then((res) => {
                if (res.data.length === 0 || res.data.error) {
                  throw { statusCode: 404, message: "Taxonomy not found",};
                }
                this.tax_res =  res.data;
                // console.log(this.tax_res);
              })
            }
            //Start
            if(Array.isArray(res.data.data.widget_settings.cat_data)){
              const res_data = res.data.data.widget_settings.cat_data;
              res_data.forEach(element => {
                this.data.push(parseInt(element));
              });

            }else {
              const res_data = res.data.data.widget_settings.cat_data;
              var cat_d = res_data.split(',');
              cat_d.forEach(element => {
                this.data.push(parseInt(element));
              });
            }
            if (tax_cat != 'null') {
              this.$axios
                .get("/l-w-s",{ params: { category_taxonomy: tax_cat} })
                .then((res) => {
                  if (res.data.length === 0 || res.data.error) {
                    throw { statusCode: 404, message: "Category Taxonomy not found",};
                  }
                  this.cat_res =  res.data;
                  console.log(this.cat_res);
                })
            }
            //End
            this.loadingOverlay = false;
          })
      }
    },
    submit () {

      if (this.widgetObject.widget_type == 'html') {
        this.selected_taxonomy = 'html';
        this.selected_category = 'html';
      }
      var formData = new FormData();
      formData.append("id", this.$route.params.id);
      formData.append("layout_id", this.$route.params.layout_id);
      formData.append("widget_space_id", this.$route.params.widget_space_id);
      formData.append("widget_id", this.widgetObject.id?this.widgetObject.id:this.widget_id);

      formData.append("widget_type", this.widgetObject.widget_type);
      formData.append("widget_path", this.widgetObject.path);
      formData.append("status", this.widget_status);
      formData.append("taxonomy", this.selected_taxonomy);
      formData.append("category_taxonomy", this.selected_category);
      formData.append("data_limit", this.data_limit);
      formData.append("show_title", this.show_title);
      formData.append("show_excerpt", this.show_excerpt);
      formData.append("title", this.title);

      if(this.selected_taxonomy == 'post'){
        if(this.data.length > 1){
          this.data.forEach(function(element) {
            formData.append("data[]", element);
          });
        }else{
          formData.append("data[]", this.data);
        }
      }else{
        formData.append("data", this.data);
      }

      try {
        if(this.$route.params.id) {
          formData.append("_method", 'PUT');

          this.$axios.post('/layout-widgets/'+ this.$route.params.id , formData, {})
          this.$router.push( { name: 'backend-layouts-view-id', params: { id: this.$route.params.layout_id}} );
        } else {

          if(this.selected_taxonomy == 'post'){
            if(this.data.length > 1){
              this.data.forEach(function(element) {
                formData.append("data[]", element);
              });
            }else{
              formData.append("data[]", this.data);
            }
          }else{
            formData.append("data", this.data);
          }
          this.$axios.post('/layout-widgets', formData, {})
          this.$router.push( { name: 'backend-layouts-view-id', params: { id: this.$route.params.layout_id}} );
        }
      } catch (e) {
        // this.error = e.response.data.message
        // console.log("Error Found");
      }
    // Category Start
      if(this.category_taxonomy == 'post'){
        if(this.data.length > 1){
          this.data.forEach(function(element) {
            formData.append("cat_data[]", element);
          });
        }else{
          formData.append("cat_data[]", this.cat_data);
        }
      }else{
        formData.append("cat_data", this.cat_data);
      }

      try {
        if(this.$route.params.id) {
          formData.append("_method", 'PUT');

          this.$axios.post('/layout-widgets/'+ this.$route.params.id , formData, {})
          this.$router.push( { name: 'backend-layouts-view-id', params: { id: this.$route.params.layout_id}} );
        } else {

          if(this.category_taxonomy == 'post'){
            if(this.data.length > 1){
              this.data.forEach(function(element) {
                formData.append("cat_data[]", element);
              });
            }else{
              formData.append("cat_data[]", this.cat_data);
            }
          }else{
            formData.append("cat_data", this.cat_data);
          }
          this.$axios.post('/layout-widgets', formData, {})
          this.$router.push( { name: 'backend-layouts-view-id', params: { id: this.$route.params.layout_id}} );
        }
      } catch (e) {
        // this.error = e.response.data.message
        // console.log("Error Found");
      }
    // Category End
    },
    clear (){
      this.data = ''
      this.cat_data = ''
      this.widget = ''
      this.widget_id = ''
      this.widget_type = ''
      this.taxonomy = ''
      this.category_taxonomy = ''
      this.data_limit = ''
      this.show_excerpt = ''
      this.show_title = ''
      this.title = ''
    }
  },
}
</script>
