<template>
  <v-navigation-drawer
    v-model="$store.state.drawer.hamburger"
    app
    width="300"
    color="background3"
  >
    <v-navigation-drawer
      v-model="$store.state.drawer.hamburger"
      absolute
      mini-variant
      color="background2"
    >
      <v-avatar
        class="d-block text-center mx-auto mt-4"
        color="indigo"
        size="36"
      >
        <v-icon dark>
          mdi-account-circle
        </v-icon>
      </v-avatar>

      <v-divider class="mx-3 my-5"></v-divider>

      <v-avatar
        v-for="n in 6"
        :key="n"
        class="d-block text-center mx-auto mb-9"
        color="grey lighten-1"
        size="28"
      ></v-avatar>
    </v-navigation-drawer>

    <v-sheet
      color="background4"
      height="128"
      width="100%"
      class="pl-14"
    >

      <div v-if="settings">
        <v-img v-if="settings.find(obj => obj.key == 'site_logo')" :src="settings.find(obj => obj.key == 'site_logo').value" contain>
        </v-img>
      </div>
    </v-sheet>

    <v-list v-for="(men, m) in menu" :key="m" dense>

      <v-list-group
        no-action
        class="pl-14"
        v-if="men.items.length"
      >
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title>
              <v-icon>{{ men.icon }}</v-icon>{{men.name}}
            </v-list-item-title>
          </v-list-item-content>
        </template>

        <v-list
          class="pl-10"
          dense
          v-if="men.items.length"
        >
          <v-list-item
            v-for="(item, i) in men.items"
            :key="i"
            :to="item.to"
            router
            dense
            class="pl-0"
            exact
          >
            <v-list-item-icon class="mr-2">
              <v-icon small>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="item.title"></v-list-item-title>
          </v-list-item>
        </v-list>
      </v-list-group>

      <v-list
        v-else
        class="pl-14"
        shaped
      >
        <v-list-item
          :to="men.to"
          class="pl-3"
          exact
        >
          <v-list-item-action class="mr-7 pl-1">
            <v-icon>{{ men.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="men.name" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-list>

<!--    <v-list
      class="pl-14"
      shaped
    >
      <v-list-item
        v-for="n in 5"
        :key="n"
        link
      >
        <v-list-item-content>
          <v-list-item-title>
            Item {{ n }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>-->
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "NavigationDrawer",
  props: [
    'website_name',
    'settings'
  ],
  data: () => ({
    drawer: true,
    menu: [
      {
        name: "Layouts",
        icon: "mdi-layers",
        items: [],
        to: "/backend/layouts",
      },
      {
        icon: "mdi-widgets",
        name: "Widgets",
        to: "/backend/widgets",
        items: []
      },
      {
        name: "Posts",
        icon: "mdi-post",
        items: [
          {
            icon: "mdi-note-plus",
            title: "Write Post",
            //to: "/backend/posts/create",
            to: {name: 'backend-posts-create'}
          },
          {
            icon: "mdi-view-list",
            title: "All Posts",
            //to: "/backend/posts/index",
            to: {name: 'backend-posts'}
          },
        ]
      },
      {
        name: "Categories",
        icon: "mdi-tag-multiple",
        items: [
          {
            icon: "mdi-tag-plus",
            title: "Create",
            to: "/backend/categories/create",
          },
          {
            icon: "mdi-animation",
            title: "Index",
            to: "/backend/categories/",
          },
        ],
      },
      {
        name: "Tags",
        icon: "mdi-tag-multiple-outline",
        items: [
          {
            icon: "mdi-tag-plus",
            title: "Create",
            to: "/backend/tags/create",
          },
          {
            icon: "mdi-tag",
            title: "Index",
            to: "/backend/tags/",
          },
        ],
      },
      {
        name: "Menus",
        icon: "mdi-format-list-group",
        items: [
          {
            icon: "mdi-playlist-plus",
            title: "Create",
            to: "/backend/menus/create",
          },
          {
            icon: "mdi-format-list-group",
            title: "Index",
            to: "/backend/menus/",
          },
        ],
      },
      {
        name: "Settings",
        icon: "mdi-cog",
        to: "/backend/settings",
        items: []
      },
      {
        name: "Wordpress XML Import",
        icon: "mdi-database-import-outline",
        to: "/backend/xmlImport",
        items: []
      },
      {
        name: "Images",
        icon: "mdi-image",
        to: "/backend/image",
        items: []
      },
      {
        name: "SEO",
        icon: "mdi-web-plus",
        items: [
          {
            icon: "mdi-web-plus",
            title: "Index",
            to: "/backend/seo",
          },
          {
            icon: "mdi-web-plus",
            title: "Create",
            to: "/backend/seo/create",
          },
        ],
      },
      {
        name: "Users",
        icon: "mdi-account-cog-outline",
        items: [
          {
            icon: "mdi-account-details-outline",
            title: "Index",
            to: "/backend/users",
          },
          {
            icon: "mdi-account-plus-outline",
            title: "Create",
            to: "/backend/users/create",
          },
        ],
      },
    ],
  }),

  watch: {
    drawer (oldval, newval) {
      this.handleDrawer()
    }
  },
  created () {
    if (process.browser) {
      if (localStorage.getItem('Drawer')) {
        const cookieValue = localStorage.getItem('Drawer') === 'true'
        this.drawer = cookieValue
      } else {
        this.handleDrawer()
      }
    }
  },
  methods: {
    handleDrawer () {
      if (process.browser) {
        if (this.drawer === true) {
          this.$vuetify.theme.dark = true
          localStorage.setItem('Drawer', true)
        } else if (this.drawer === false) {
          this.$vuetify.theme.dark = false
          localStorage.setItem('Drawer', false)
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
