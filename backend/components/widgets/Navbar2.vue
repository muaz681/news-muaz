<template>
  <v-card class="overflow-hidden navbar-holder">
    <v-app-bar
      app
      fixed
      color="#fcb69f"
      class="d-print-none"
      dark
      shrink-on-scroll
      prominent
      style="background: url('https://picsum.photos/1024/300') no-repeat center center; background-size: cover;"
    >

      <v-app-bar-nav-icon class="hidden-md-and-up" @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-img res v-ripple class="my-auto navbarLogo" src="/assets/images/logo.png" contain max-height="90%" :max-width="'350px'" position="left center" @click="goHome()" style="cursor: pointer"/>

      <v-spacer></v-spacer>

      <!--

            <v-toolbar-items class="hidden-sm-and-down">
              <v-btn
                text
                tile
                color="white"
                dark
                v-for="item in menu"
                :key="item.icon"
                :to="item.link? item.link : '/category/'+item.id"
              >{{ item.title }}</v-btn>
            </v-toolbar-items>
      -->

      <template v-slot:extension v-if="$vuetify.breakpoint.mdAndUp" class="d-none d-md-flex flex-row justify-space-between">
        <v-btn
          text
          color="white"
          dark
          v-for="item in menu"
          class="py-2 px-2"
          style="min-width: initial;"
          :key="item.icon"
          :to="item.link? item.link : '/category/'+item.slug"
        >{{ item.title }}</v-btn>
      </template>

      <v-menu class="hidden-md-and-up">
        <v-list>
          <v-list-tile v-for="item in menu" :key="item.icon" :to="item.link? item.link : '/category/'+item.slug">
            <v-list-tile-content>
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>

      <!--			<v-toolbar-items class="hidden-sm-and-down">
              <v-btn
                text
                v-ripple="false"
                class="disable-hover"
              >
                <div class="g-ytsubscribe" data-channelid="UCVoHqcwxOElQI0Hdvb6whxQ"></div>
              </v-btn>
              <v-btn
                v-ripple="false"
                icon :small="$vuetify.breakpoint.xsOnly" tag="button">
                <v-icon >mdi-facebook</v-icon>
              </v-btn>
            </v-toolbar-items>-->
      <v-container class="px-0">
        <v-row>
          <v-spacer/>
          <v-dialog v-model="dialog" width="500">

            <template v-slot:activator="{ on, attrs }">
              <v-btn icon :small="true" tag="button" v-bind="attrs" v-on="on" class="hidden-md-and-up">
                <v-icon>{{mdiMagnify}}</v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <v-spacer/>
                <v-btn small icon @click="dialog = false"><v-icon>{{mdiClose}}</v-icon></v-btn>
              </v-card-title>
              <v-card-text>
                <v-text-field
                  @submit="submit()"
                  type="search"
                  class="navSearch"
                  name="id"
                  autofocus
                  label="Search"
                  v-model="query"
                  v-on:keydown.enter.prevent="submit()"
                  :append-icon="mdiMagnify"
                  @click:append="submit"
                >
                </v-text-field>
              </v-card-text>
            </v-card>
          </v-dialog>
          <v-slide-x-reverse-transition origin="right">
            <v-col cols="3" md="3" class="pa-0 hidden-sm-and-down" v-show="show">
              <v-form>
                <v-text-field
                  @submit="submit()"
                  type="search"
                  class="navSearch hidden-sm-and-down"
                  name="id"
                  single-line
                  autofocus
                  v-model="query"
                  @blur="hideInput()"
                >
                </v-text-field>
                <button class="d-none" type="submit" @click.stop.prevent="submit()">Submit</button>
              </v-form>
            </v-col>
          </v-slide-x-reverse-transition>

          <v-btn icon tag="button" class="hidden-sm-and-down" v-model="show" @click="showInput()" title="search">
            <v-icon>{{show? mdiClose : mdiMagnify}}</v-icon>
          </v-btn>
          <v-btn icon :small="true" tag="button" to="/archive" class="hidden-md-and-up" title="archive">
            <v-icon>mdi-archive</v-icon>
          </v-btn>
          <v-btn elevation="0" color="transparent" tag="button" to="/archive" class="hidden-sm-and-down" title="archive">
            আর্কাইভ
          </v-btn>
          <v-btn elevation="0" color="transparent" :small="true" tag="button" href="https://voicetv.tv" class="px-1 body-1 hidden-md-and-up" title="English">
            EN
          </v-btn>
          <v-btn elevation="0" color="transparent" tag="button" href="https://voicetv.tv" class="hidden-sm-and-down" title="English">
            English
          </v-btn>
        </v-row>
        <v-row class="hide-on-nav-scroll">
          <v-spacer/>
          <v-btn
            v-for="(item, index) in socialLinks"
            :key="index"
            :class="'mx-2 ' + (index>1? 'hidden-sm-and-down' : '') "
            small
            icon
            :href="item.url"  target="_blank"
            elevation="3"
            tag="button"
            rel="noreferrer"
            :aria-label="item.icon"
            :style="'background: '+ item.background"
          >
            <v-icon>{{ item.icon }}</v-icon>
          </v-btn>
        </v-row>
      </v-container>
    </v-app-bar>

    <v-navigation-drawer app v-if="drawer" v-model="drawer" class="hidden-md-and-up">
      <v-img class="mx-6 my-3" src="/assets/images/logo.png" contain  position="left center" @click="goHome()" style="cursor: pointer"/>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item class="ml-6" v-for="item in menu" :key="item.title" link :to="item.link? item.link : '/category/'+item.slug">
          <v-list-item-content>
            <v-list-item-title class="line-height-2 py-0">{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <v-divider></v-divider>
      <v-list dense nav>
        <v-list-item-title>সংস্করণ</v-list-item-title>
        <v-list-item link class="ml-4" href="https://voicetv.tv">
          <v-list-item-avatar size="24">
            EN
          </v-list-item-avatar>
          <v-list-item-content>English</v-list-item-content>
        </v-list-item>
        <v-list-item link class="active ml-4" href="https://bn.voicetv.tv">
          <v-list-item-avatar size="24">
            BN
          </v-list-item-avatar>
          <v-list-item-content>বাংলা</v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </v-card>
</template>
<script>
import {mdiFacebook, mdiYoutube, mdiClose, mdiMagnify, mdiInstagram, mdiTwitter, mdiLinkedin, mdiArchiveArrowDownOutline} from "@mdi/js";
export default {
  name: "navbar",
  components: {
  },
  data: () => ({
    show: false,
    query: null,
    socialLinks: [
      {icon: mdiFacebook, url: "https://www.facebook.com/voicetvnews/", color: "indigo", background: "#3b5998"},
      {icon: mdiYoutube, url: "https://www.youtube.com/voicetelevision?sub_confirmation=1", color: "red", background: "#ff0000"},
      {icon: mdiInstagram, url: "https://www.instagram.com/voicetelevision/", color: "yellow", background: "radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%)"},
      {icon: mdiTwitter, url: "https://twitter.com/voicetvnews", color: "blue", background: "#1da1f2"},
      {icon: mdiLinkedin, url: "https://www.linkedin.com/company/voicetelevision/", color: "blue darken-3", background: "#007bb5"},
    ],
    categories: [
      { id: 0, name: "প্রচ্ছদ", slug: "home", link: '/'},
      { id: 45, name: "লাইফস্টাইল", slug: 'lifestyle' },
      { id: 46, name: "প্রযুক্তি", slug: 'technology' },
    ],
    menu: [
      { id: 0, title: "প্রচ্ছদ", slug: 'home', link: '/' },
      { id: 16, title: "জাতীয়", slug: 'national' },
      { id: 5, title: "রাজনীতি", slug: 'politics' },
      { id: 4, title: "অপরাধ", slug: 'crime' },
      { id: 13, title: "খেলাধুলা", slug: 'sports-news' },
      { id: 9, title: "শিক্ষাঙ্গন", slug: 'campus' },
      { id: 12, title: "সারাদেশ", slug: 'country-news' },
      { id: 8, title: "মুক্তমত", slug: 'feature' },
      { id: 41, title: "পশ্চিমবঙ্গ", slug: 'west-bangla' },
      { id: 14, title: "প্রবাসী", slug: 'foreigner' },
      { id: 3, title: "বিনোদন", slug: 'entertainment' },
      { id: 7, title: "বিশ্ব", slug: 'world' },
    ],
    drawer: false,
    dialog: false,
    mdiFacebook: mdiFacebook,
    mdiYoutube: mdiYoutube,
    mdiClose: mdiClose,
    mdiMagnify: mdiMagnify,
    mdiArchive: mdiArchiveArrowDownOutline,
  }),

  methods: {
    menuItems() {
      return this.menu;
    },
    goHome() {
      location.href = $config.publicRuntimeConfig;
    },
    showInput() {
      this.show = !this.show;
    },
    hideInput() {
      setTimeout( () => {
        this.show = false;
      }, 300 );
    },
    submit(){
      this.$router.push({ name: "search-id", params: { id: this.query } });
    },
    showDate() {
      var date = new Date();
      var n = date.toDateString();
      var time = date.toLocaleTimeString();
      return n + ' ' + time;
    },
    formatDateHumanReadable(string) {
      const readable = new Date(string);
      let m = readable.getMonth(),
        d = readable.getDate().toLocaleString('bn'),
        y = readable.getFullYear().toLocaleString('bn').replace(',',''),
        //months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        months = ["জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"];
      let monthName = months[m];
      return monthName + " " + d + ", " + y;
    },
    typeWriter(id, txt) {
      let i = 0;
      let speed = 50;
      function typewriter () {
        if (i < txt.length) {
          document.getElementById(id).innerHTML += txt.charAt(i);
          i++;
          setTimeout(typewriter, speed);
        }
      }
    },
  },
  mounted() {
    //console.log(JSON.stringify(this.menu));
  }
};
</script>

<style lang="css">
.v-tabs-bar .v-tab {
  font-size: 18px;
}
.navSearch .v-text-field__details {display: none}
header.v-app-bar--is-scrolled .row {
  transition: all 0.3s;
  opacity: 1;
}
header.v-app-bar {
  background-size: cover;
}
header.v-app-bar--is-scrolled .hide-on-nav-scroll {
  opacity: 0;
}

@media (min-width: 1264px) {
  header.v-app-bar {
    max-height: 224px !important;
  }
}

.v-toolbar__extension .v-btn.v-size--default {
  font-size: 1rem;
}
@keyframes metronome-example {
  from {
    transform: scale(.5);
  }

  to {
    transform: scale(1);
  }
}

.v-avatar--metronome {
  animation-name: metronome-example;
  animation-iteration-count: infinite;
  animation-direction: alternate;
}
.v-toolbar__extension {
  display: flex;
  justify-content: space-around !important;
}

/* Typewriter effect */
.typewriter-text {
  overflow: hidden;
  position: relative;
  width: 20em;
  border-right: 2px solid rgba(255, 255, 255, 0.75);
  font-size: 18px;
  white-space: nowrap;

  animation: typewriter 4s steps(44) 1s 1 normal both,
  blinkTextCursor 400ms steps(44) infinite normal;
}
@keyframes typewriter {
  from {
    width: 0;
  }

  to {
    width: 20em;
  }
}

@keyframes blinkTextCursor {

  from {
    border-right-color: rgba(255, 255, 255, 0.75);
  }

  to {
    border-right-color: transparent;
  }

}
</style>
