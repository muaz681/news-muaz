<template>
  <div class="mt-3">
    <div class="sectionTitle">
      <v-card-title v-if="title" v-text="title"></v-card-title>
    </div>
    <v-card tile class="rounded-lg" flat>
      <v-list v-if="this.posts !== null">
        <v-list-item-group color="primary">
          <v-list-item two-line v-for="(post, i) in posts" :key="i" :to="{name: 'slug', params: {slug:decodeURIComponent(post.slug)}}" class="my-3 border-bottom">
            <v-list-item-avatar height="100%" :size="$vuetify.breakpoint.xsOnly ? 54 : 74" tile v-if="post.pictures[0].thumbnail !== undefined">
              <v-img :src="getImageSrc(post.pictures[0].thumbnail)"></v-img>
            </v-list-item-avatar>

            <v-list-item-avatar v-else size="74" tile color="primary">
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title class="text-wrap" v-html="post.title"></v-list-item-title>
              <v-list-item-subtitle class="text-wrap" v-html="post.short_description"></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <v-skeleton-loader
        v-for="(n,i) in 5"
        :key="i"
        class="mt-5"
        tile
        type="list-item-avatar-two-line"
        v-else
        :loading="loading"
        transition="fade-transition"
      ></v-skeleton-loader>

    </v-card>
  </div>
</template>
<script>
export default {
  name: "MultiPostListImageLeft",
  props: [
    // camelCase in JavaScript
    'title',
    'url',
    'widget_settings',
    'data_limit',
    'taxonomy',
    'data',
    'page',
    'posts'
  ],
  components: {
    // Create a new component that
    // extends v-skeleton-loader
    VBoilerplate: {
      functional: true,

      render (h, { data, props, children }) {
        return h('v-skeleton-loader', {
          ...data,
          props: {
            boilerplate: true,
            elevation: 2,
            ...props,
          },
        }, children)
      },
    },
  },
  data() {
    return {
      posts: null,
      loading: true,
      loaded: false
    };
  },
  methods: {
    getPosts() {
      let category_ids, tag_ids;
      if(this.widget_settings) {
        if(this.widget_settings.taxonomy === 'category') {
          category_ids = this.widget_settings.data.split(',');
        } else if (this.widget_settings.taxonomy === 'tag') {
          tag_ids = this.widget_settings.data.split(',');
        }
      }
      this.$axios
        .get(
          this.url ? this.url : '/frontend/posts', {
            params: {
              per_page: this.data_limit === 'null' ? 10 : this.data_limit,
              page: this.page,
              category_ids: category_ids,
              tag_ids: tag_ids,
            },
          }
        )
        .then(response => {
          this.posts = response.data.data;
          this.loading = false;
          this.loaded = true;
        });
    },
    findThumbSrc(post) {
      let img = post.media_sizes && post.media_sizes.file;
      if (img == null) {
        return '/assets/logo-square.svg';
      }
      let thumb = post.media_sizes.sizes && post.media_sizes.sizes.thumbnail && post.media_sizes.sizes.thumbnail.file;
      let urlparts = post.image.split('/');
      urlparts.pop();
      return urlparts.join('/') + '/' + thumb;
    },
    getImageSrc(src) {
      if (/^(?:[a-z]+:)?\/\//i.test(src)) {
        return src;
      }
      return this.$config.apiBaseURL + '/storage' + src;
    }
  },
  mounted() {
    this.getPosts();
  }
}
</script>

