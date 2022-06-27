<template>
  <v-app id="inspire">
    <v-system-bar app color="background3">
      <v-spacer></v-spacer>

      <v-icon>mdi-wifi-strength-4</v-icon>
      <v-icon>mdi-signal-cellular-outline</v-icon>
      <v-icon>mdi-battery</v-icon>
      <clock></clock>
    </v-system-bar>

    <app-bar-top :settings="settings" :website_name="website_name"/>
    <navigation-drawer :settings="settings" :website_name="website_name"/>

    <v-main>
      <Nuxt />
    </v-main>

  </v-app>
</template>

<script>
import Clock from "@/components/backend/Clock";
import DarkModeToggle from "@/components/DarkModeToggle";
import AppBarTop from "@/components/backend/AppBarTop";
import NavigationDrawer from "@/components/backend/NavigationDrawer";

export default {
  components: {
    Clock,
    DarkModeToggle,
    NavigationDrawer,
    AppBarTop
  },
  data: () => ({
    drawer: null,
    settings: null,
    website_name: null,
  }),
  created () {
    this.getSetting();
  },
  methods: {
    getSetting() {
      this.$axios
        .get("/settings")
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw {
              statusCode: 404,
              message: "Posts not found",
            };
          }
          this.settings = res.data.data;
          this.website_name = res.data.data.find(obj => obj.key == 'website_name').value;
        })
    }
  }
}
</script>
