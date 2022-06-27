<template>
  <component :is="tag" :class="classes">
    <v-card v-if="label" color="grey lighten-4 fill-height" flat min-height="300px" tile>
      <v-toolbar dense>
        <v-toolbar-title>{{ label }}</v-toolbar-title>

        <v-spacer></v-spacer>
         {{ section_id }}
        <v-btn
          v-if="widget_setting.map(function(e) { return e.widget_space_id; }).indexOf(section_id) < 0"
          icon
          :to="{ name: 'backend-layouts-update-layout_id-widget_space_id-id', params: { layout_id: layout_id, widget_space_id: section_id },}"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>

      <v-card-text class="tree-text">
        <v-row v-for="(data, index) in widget_setting" :key="index">
          <v-card-text v-if="section_id === data.widget_space_id">
            <v-row>
              <v-spacer></v-spacer>
              <v-btn
              icon
              :to="{ name: 'backend-layouts-update-layout_id-widget_space_id-id', params: { layout_id: layout_id, widget_space_id: section_id, id: data.id },}"
            >
              <v-icon small class="tree-text">mdi-pencil</v-icon>
            </v-btn></v-row>
            <br>
            <v-row>
              <v-col class="text-left">
                <v-simple-table dense>
                  <tbody>
                    <tr v-if="data.title">
                      <th>Title</th>
                      <td v-text="data.title"></td>
                    </tr>
                    <tr v-if="data.widget_settings.widget_type">
                      <th>Widget Type</th>
                      <td v-text="data.widget_settings.widget_type"></td>
                    </tr>
                    <tr v-if="data.widget_settings.widget_path">
                      <th>Widget Path</th>
                      <td v-text="data.widget_settings.widget_path"></td>
                    </tr>
                    <tr v-if="data.widget_settings.taxonomy">
                      <th>Taxonomy</th>
                      <td v-text="data.widget_settings.taxonomy"></td>
                    </tr>
                    <tr v-if="data.widget_settings.category_taxonomy">
                      <th>Category Taxonomy</th>
                      <td v-text="data.widget_settings.category_taxonomy"></td>
                    </tr>
                    <tr v-if="data.widget_settings.data_limit">
                      <th>Data Limit</th>
                      <td v-text="data.widget_settings.data_limit"></td>
                    </tr>
                    <tr>
                      <th>Widget Data</th>
                      <td v-text="data.widget_settings.data"></td>
                    </tr>
                    <tr>
                      <th>Category Data</th>
                      <td v-text="data.widget_settings.cat_data"></td>
                    </tr>
                    <tr>
                      <th>Status</th>
                      <td v-text="data.status"></td>
                    </tr>
                  </tbody>
                </v-simple-table>
              </v-col>
            </v-row>

          </v-card-text>
        </v-row>
      </v-card-text>
    </v-card>
    <tree
      v-for="(node, index) in nodes"
      :key="index"
      :layout_id="layout_id"
      :nodes="node.nodes"
      :label="node.label"
      :tag="node.tag"
      :class="node.class"
      :depth="depth + 1"
      :section_id="node.section_id"
      :id="node.section_id"
      :widget_setting="widget_setting"
    >
    </tree>
  </component>
</template>

<script>
export default {

  props: [
    "label",
    "nodes",
    "depth",
    "tag",
    "classes",
    "section_id",
    "layout_id",
    "widget_setting",
  ],
  name: "Tree",
};
</script>

<style scoped>
.tree-text {
  color: black !important;
}
</style>
