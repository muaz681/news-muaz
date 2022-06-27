<template>

  <div>
    <!--  Start -->
      <div v-if="title" class="col-start-1 col-end-3">
        <a
          href="#"
          class="font-normal text-black text-2xl font-sans"
          v-text="title"
        >
        </a>
      </div>
<!--      <div class="col-end-10 col-span-3 lg:block hidden">-->
<!--        <a-->
<!--          href="#"-->
<!--          class="text-gray-800 text-sm font-medium inline-flex items-center rounded mr-2 dark:bg-gray-700 dark:text-gray-300"-->
<!--        >-->
<!--          <span class="pr-2"> আরও পড়ুন </span>-->

<!--          <svg-->
<!--            version="1.1"-->
<!--            id="Capa_1"-->
<!--            xmlns="http://www.w3.org/2000/svg"-->
<!--            xmlns:xlink="http://www.w3.org/1999/xlink"-->
<!--            x="0px"-->
<!--            y="0px"-->
<!--            width="18px"-->
<!--            height="18px"-->
<!--            viewBox="0 0 93.934 93.934"-->
<!--            style="enable-background: new 0 0 93.934 93.934"-->
<!--            xml:space="preserve"-->
<!--          >-->
<!--                <g>-->
<!--                  <path-->
<!--                    d="M46.967,0C21.029,0,0,21.028,0,46.967c0,25.939,21.029,46.967,46.967,46.967c25.939,0,46.967-21.027,46.967-46.967-->
<!--                    C93.934,21.027,72.906,0,46.967,0z M55.953,66.295V54.301H18.652V39.633h37.303V27.639l19.326,19.328L55.953,66.295z"-->
<!--                  />-->
<!--                </g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--            <g></g>-->
<!--              </svg>-->
<!--        </a>-->
<!--      </div>-->

  </div>
  <!--  End -->
</template>
<script>
import qs from "qs";

export default {
  name: "HeadingRow",
  props: [
    // camelCase in JavaScript
    'title',
    'url',
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
            paramsSerializer: params => {
              return qs.stringify(params)
            }
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

<style scoped>
.sectionTitle {
  border-top: 2px solid black;
  border-bottom: 1px solid lightgray;
}
</style>
