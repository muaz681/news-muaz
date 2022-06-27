<template>

  <div>

    <!--  Start -->
    <div v-if="title" class="grid grid-cols-6 gap-4 my-4 py-2 px-2 border-y-2 border-zinc-600">
      <div class="col-start-1 col-end-3">
        <a
          href="#"
          class="font-normal text-black text-2xl dark:text-gray-400 font-sans"
          v-text="title"
        >
        </a>
      </div>
      <div class="col-end-10 col-span-3 lg:block hidden">
        <a
          href="#"
          class="text-gray-800 text-sm font-medium inline-flex items-center rounded mr-2 dark:bg-gray-700 dark:text-gray-300"
        >
          <span class="pr-2"> আরও পড়ুন </span>

          <svg
            version="1.1"
            id="Capa_1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px"
            y="0px"
            width="18px"
            height="18px"
            viewBox="0 0 93.934 93.934"
            style="enable-background: new 0 0 93.934 93.934"
            xml:space="preserve"
          >
                  <g>
                    <path
                      d="M46.967,0C21.029,0,0,21.028,0,46.967c0,25.939,21.029,46.967,46.967,46.967c25.939,0,46.967-21.027,46.967-46.967
                      C93.934,21.027,72.906,0,46.967,0z M55.953,66.295V54.301H18.652V39.633h37.303V27.639l19.326,19.328L55.953,66.295z"
                    />
                  </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
                </svg>
        </a>
      </div>
    </div>
    <div v-if="this.postsLoaded !== null" class="grid grid-cols-12 gap-3">
      <div v-for="(post, i) in postsLoaded" :key="i" class="col-span-12 lg:col-span-4">
        <div class="bg-transparent">
          <nuxt-link :to="{name: 'slug', params: {slug:decodeURIComponent(post.slug)}}">
            <img
              class=""
              :src="getImageSrc(post.pictures[0].thumbnail)"
              alt=""
            />
            <div class="pt-3">
              <h5 class="mb-2 text-xl font-medium tracking-tight text-black hover:text-red-700 line-clamp-2 font-sans"
                v-if="widget_settings.show_title == 1" v-text="post.title"></h5>
              <p class="lg:!block !hidden mb-2 font-normal text-sm text-black line-clamp-2 font-serif"
                v-if="widget_settings.show_excerpt == 1" v-text="post.short_description"></p>
              <!-- End -->
            </div>
          </nuxt-link>
        </div>
      </div>
    </div>
    <div v-else class="grid grid-cols-12 gap-3">
      <div v-for="(n,i) in 12"
           :key="n" class="col-span-12 lg:col-span-4">
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
<!--  End -->
</template>
<script>
import qs from "qs";

export default {
  name: "MultiPostColumns",
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

<style scoped>
.sectionTitle {
  border-top: 2px solid black;
  border-bottom: 1px solid lightgray;
}
</style>
