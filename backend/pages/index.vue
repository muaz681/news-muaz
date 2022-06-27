<template>
  <div>
    <div v-if="layout">
      <StructureTree :nodes="layout.structure.nodes" :tag="layout.structure.tag" :class="layout.structure.class" :posts="posts"></StructureTree>
    </div>
  </div>
</template>

<script>
import StructureTree from "~/components/frontend/StructureTree";
export default {
  components: {StructureTree},
  auth: false,
  layout: 'frontend',
  name: 'IndexPage',
  data: () => ({
    layout: null,
    posts: null,
    layouts: null,
  }),
  created() {
    this.getLayout();
    this.getPosts();
  },
  methods: {
    getLayout() {
      this.$axios
        .get('/frontend/formatted')
        .then(response => {
          this.layout = response.data;
        });
    },

    getPosts() {
      this.$axios
        .get('/frontend/posts')
        .then(response => {
          this.posts = response.data;
        });
    },

  },
  // async asyncData ({ $axios }) {
  //   const [layoutRes, postsRes] = await Promise.all([
  //     $axios.get('/frontend/formatted'),
  //     $axios.get('/frontend/posts'),
  //   ])

  //   return {
  //     layout: layoutRes.data,
  //     posts: postsRes.data,
  //   }
  // },
/*  asyncData(context) {
    return (
      context.$axios
        .get("/frontend/formatted")
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw {
              statusCode: 404,
              message: "layouts not found",
            };
          }
          // console.log(res.data.data);
          return {layout: res.data};
        })
        .catch((error) => {
          if (error.response) {
            // Request made and server responded
            //this.errorResponse = error.response.data;
            this.errorCode = error.response.status;
          } else if (error.request) {
            // The request was made but no response was received
            //console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            return { error: error.message, };
          }
        })
    );
  },*/
}
</script>
