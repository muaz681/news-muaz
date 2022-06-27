<template>
  <div class="lg:flex sm:inline overflow-auto whitespace-nowrap tag-scroll flex-row py-3 items-center">
    <div
      class="ml-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-transparent text-red-700 rounded-full"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="feather feather-activity mr-2"
      >
        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
      </svg>
      Trending :
    </div>
    <div v-for="(tag, i) in tags" :key="i" v-if="i <= 5">
<!--      {{i}}-->
      <nuxt-link :to="tag.slug" class="font-sans ml-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-red-700 text-white" v-text="tag.title">

      </nuxt-link>
    </div>

  </div>
</template>
<script>
export default {
  data: () => ({
    tags: [],
  }),
  created () {
    this.getTags();
  },
  methods: {
    getTags(){

      this.$axios
        .get(
          this.url ? this.url : '/frontend/tags')
        .then(response => {
          this.tags = response.data.activeTag;
          // console.log(this.tags);
        });
    },
  },
  mounted() {
    // console.log(this.tags);
  },
}
</script>
