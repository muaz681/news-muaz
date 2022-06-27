<template>
  <div>
    <div v-if="title" class="grid grid-cols-1 gap-4 my-4 py-2 px-2 border-y-2 border-zinc-600">
      <div class="col-span-1">
        <a
          href="#"
          class="font-normal text-black text-2xl font-sans" v-text="title"
        >
        </a>
      </div>
    </div>
    <div class="grid grid-cols-12 gap-1">
      <div class="col-span-12">
        <div class="flex justify-center">
          <div class="lg:mb-3 mb-0 xl:w-96 w-full">
            <form action="" method="post">
              <select
                class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                aria-label="Default select example"
              >
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
              <button
                type="button"
                class="w-full justify-center text-white bg-red-700 hover:bg-red-700 focus:ring-4 focus:ring-red-700 focus:outline-none font-medium px-5 py-2 text-center inline-flex items-center dark:focus:ring-red-700 mr-2 mb-2"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                  role="img"
                  width="18px"
                  height="18px"
                  preserveAspectRatio="xMidYMid meet"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396l1.414-1.414l-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8s3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6s-6-2.691-6-6s2.691-6 6-6z"
                  />
                </svg>
                <span class="ml-2 font font-sans text-base">খুঁজুন</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import qs from "qs";

export default {
  name: "DistrictColumns",
  props: [
    // camelCase in JavaScript
    'title',
    'url',
    'widget_settings',
    'data_limit',
    'taxonomy',
    'data',
    'page',
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
