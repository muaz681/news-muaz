<template>
  <div>
    <div class="grid grid-cols-12">
      <div class="col-span-12">
          <div v-if="title" class="bg-red-600 py-1">
            <h5
              class="text-2xl font-medium tracking-tight text-white font-sans text-center font-sans"
              v-text="title"
            >
            </h5>
          </div>
      </div>
      <!-- List Section -->
      <div class="col-span-12">
        <div v-if="this.posts !== null"
             class="grid grid-cols-12 bg-violet-200 p-2 divide-y divide-zinc-300 max-h-96 overflow-y-scroll menu-scroll"
        >
          <div v-for="(post, i) in postsLoaded" v-if="data_limit >= 0 && data_limit <= 10" :key="i" class="col-span-12 lg:col-span-12 py-2 lg:px-0 px-1">
            <NuxtLink :to="{name: 'slug', params: {slug:decodeURIComponent(post.slug)}}" class="flex flex-row items-center md:flex-row md:max-w-xl">
              <img
                v-if="post.pictures[0].thumbnail !== undefined"
                class="object-cover w-auto h-14 md:h-auto md:w-24"
                :src="getImageSrc(post.pictures[0].thumbnail)"
                alt=""
              />
              <div class="flex flex-col justify-between px-2 leading-normal">
                <h5 v-if="widget_settings.show_title == 1" class="text-sm font-medium tracking-tight text-black hover:text-red-700 line-clamp-2 font-sans" v-html="post.title">
                </h5>
                <p v-if="widget_settings.show_excerpt == 1" class="font-normal text-xs text-black dark:text-gray-400 line-clamp-3 font-sans" v-html="post.short_description">
                </p>
              </div>
            </NuxtLink>
          </div>
        </div>
        <div v-else class="grid grid-cols-12 bg-violet-200 p-2 divide-y divide-zinc-300 max-h-96 overflow-y-scroll menu-scroll">
          <div v-for="(n,i) in 6"
               :key="n" class="col-span-12 lg:col-span-12 py-2 lg:px-0 px-1">
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
      </div>
      <!-- Archive Section -->
      <div class="col-span-12">
        <div class="bg-red-600 py-1">
          <h5
            class="text-2xl font-medium tracking-tight text-white font-sans text-center"
          >
            <a href="#" class="font-sans"> আর্কাইভ </a>
          </h5>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import qs from "qs";

export default {
  name: "MultiPostListImageInset",
  props: [
    // camelCase in JavaScript
    'title',
    'url',
    'widget_settings',
    'data_limit',
    'taxonomy',
    'data',
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
