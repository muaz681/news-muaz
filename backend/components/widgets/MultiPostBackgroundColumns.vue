<template>
  <div class="grid grid-cols-12 gap-4" v-if="this.postsLoaded !== null">
    <div v-for="(post, i) in postsLoaded" :key="i" class="lg:col-span-3 col-span-12">
      <div class="bg-transparent">
        <NuxtLink :to="{name: 'slug', params: {slug:decodeURIComponent(post.slug)}}">
          <img
            class="object-cover w-full"
            :src="getImageSrc(post.pictures[0].thumbnail)"
            alt=""
          />
          <div class="h-20 p-3 bg-violet-200 border-4 border-transparent border-l-red-500">
            <h5
              class="mb-2 text-lg font-medium tracking-tight text-black line-clamp-2 font-sans"
              v-if="widget_settings.show_title == 1" v-text="post.title"
            ></h5>
            <p class="lg:!block !hidden mb-2 font-normal text-base text-black line-clamp-2 font-serif"
               v-if="widget_settings.show_excerpt == 1" v-text="post.short_description"></p>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
  <div class="grid grid-cols-12 gap-4" v-else>
    <div v-for="(n,i) in data_limit"
         :key="n" class="lg:col-span-3 col-span-12">
      <div class="border border-blue-300 shadow rounded-md p-4 max-w-sm w-full mx-auto">
        <div class="animate-pulse flex space-x-4">
          <div class="rounded-full bg-slate-200 h-10 w-10"></div>
          <div class="flex-1 space-y-6 py-1">
            <div class="h-2 bg-slate-200 rounded"></div>
            <div class="space-y-3">
              <div class="grid grid-cols-3 gap-4">
                <div class="h-2 bg-slate-200 rounded col-span-2"></div>
                <div class="h-2 bg-slate-200 rounded col-span-1"></div>
              </div>
              <div class="h-2 bg-slate-200 rounded"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import qs from "qs";

export default {
  name: "MultiPostBackgroundColumns",
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
      loading: true,
      loaded: false,
      postsLoaded: null,
      postCount: 0,
    };
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
              paramsSerializer: params => {
                return qs.stringify(params)
              }
            }
          )
          .then(response => {
            this.postsLoaded = response.data.data;
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
