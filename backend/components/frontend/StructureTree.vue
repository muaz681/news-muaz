<template>
  <component :is="tag" :class="classes">
    <div v-if="label" color="grey lighten-4 fill-height" flat tile>
      <v-card-title v-if="!widget">{{ label }}</v-card-title>
      <LazyNuxtDynamic v-if="widget" :component="widget.widget_settings.widget_path" :widget_settings="widget.widget_settings" :title="widget.title" :data_limit="widget.widget_settings.data_limit" :posts="posts" />
    </div>
    <StructureTree
      v-for="(node, index) in nodes"
      :key="index"
      :nodes="node.nodes"
      :label="node.label"
      :tag="node.tag"
      :class="node.class"
      :widget="node.widget"
      :posts="posts"
    >
    </StructureTree>
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
    "widget",
    "posts",
  ],
  name: "StructureTree"
}
</script>

<style scoped>

</style>
