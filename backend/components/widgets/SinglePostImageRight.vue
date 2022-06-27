<template>
  <div>
    <v-card tile class="rounded-0" flat>
      <v-list v-if="postsLoaded" three-line>
        <v-list-item v-for="(post, i) in postsLoaded" :key="i" :to="{name: 'slug', params: {slug:decodeURIComponent(post.slug)}}" class="border-bottom px-0">
          <v-list-item-content>
            <v-list-item-title class="text-wrap font-1_25x" v-html="post.title"></v-list-item-title>
            <v-row no-gutters class="flex flex-row">
              <v-col class="flex-grow-1">
                <v-list-item-subtitle class="text-wrap pr-2 mt-2" v-html="post.short_description"></v-list-item-subtitle>
              </v-col>
              <v-col class="flex-grow-0">
                <v-list-item-avatar class="ma-0" :width="$vuetify.breakpoint.xsOnly ? 72 : 116" :height="$vuetify.breakpoint.xsOnly ? 46 : 76" tile v-if="post.pictures[0].thumbnail !== undefined">
                  <v-img contain lazy-src="/assets/images/image-placeholder.png" :src="getImageSrc(post.pictures[0].thumbnail)"></v-img>
                </v-list-item-avatar>
              </v-col>
            </v-row>
          </v-list-item-content>
        </v-list-item>
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
import {mdiPlay} from "@mdi/js";
import qs from "qs";
export default {
  name: "SinglePostImageRight",
  props: [
    'title',
    'url',
    'widget_settings',
    'data_limit',
    'taxonomy',
    'data',
    'posts',
  ],
  data() {
    return {
      postsLoaded: null,
      loading: true,
      loaded: false,
      postCount: 0,
      mdiPlay: mdiPlay,
      hasData: false,
    }
  },
  methods: {
    getPosts() {
      if (this.posts && this.widget_settings.taxonomy == 'null') {
        this.loading = false;
        this.loaded = true;
        this.postCount = this.posts.data.length;
        this.postsLoaded = this.posts.data;
      } else {
        let category_ids, tag_ids;
        if(this.widget_settings.taxonomy === 'category') {
          category_ids = this.widget_settings.data.split(',');
        } else if (this.widget_settings.taxonomy === 'tag') {
          tag_ids = this.widget_settings.data.split(',');
        }
        this.$axios
          .get(
            this.url ? this.url : '/frontend/posts', {
              params: {
                per_page: this.data_limit === 'null' ? 1 : this.data_limit,
                category_ids: category_ids,
                tag_ids: tag_ids,
              },
              paramsSerializer: params => {
                return qs.stringify(params)
              }
            }
          )
          .then(response => {
            this.postsLoaded = response.data.data;
            if(response.data.data.length) {
              this.hasData = true;
            }
            /*.reduce(function (res, current, index, array) {
            return res.concat([current, current]);
          }, []);*/
            this.loading = false;
            this.loaded = true;
            this.postCount = response.data.data.length;
          });
      }
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

<style scoped>
.v-card__title, .v-list-item__title {
  font-family: SolaimanLipi;
}
.font-1_25x {
  font-size: 1.25em;
}
.v-list--three-line .v-list-item .v-list-item__subtitle, .v-list-item--three-line .v-list-item__subtitle {
  -webkit-line-clamp: 4;
}
</style>
