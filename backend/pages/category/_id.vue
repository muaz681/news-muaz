<template>
  <div class="post-page-main">
    <Navbar/>
    <v-container >
      <v-row>
        <v-col cols="9">
          <!--          {{items}}-->
          <v-breadcrumbs :items="items" divider="/" class="pl-0">
          </v-breadcrumbs>
          <h1 v-text="this.$route.params.slug" class="my-3"></h1>
          <v-row
            align="center"
            class="spacer"
            no-gutters
            v-if="post"
          >
            <v-col class="flex-grow-0 mr-2">
              <v-avatar size="40">
                <img src="assets/images/logo-avatar.svg" alt="News Desk">
              </v-avatar>
            </v-col>
            <v-col cols="4">
              <div class="d-block">
                <em>নিজস্ব প্রতিবেদক</em><small v-if="post.districts && post.districts[0]">, <nuxt-link :to="{name: 'district-slug', params: {slug: post.districts[0].slug}}">{{post.districts[0].title_bn}}</nuxt-link></small>
              </div>
              <div class="grey--text font-italic text-caption">
                প্রকাশ: <span v-text="formatDateHumanReadable(post.created_at)"></span>
              </div>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <div class="d-print-none">
                <v-btn icon x-small class="mr-1 mr-lg-3">
                  <ShareNetwork
                    network="facebook"
                    :url="getUrl()"
                    :title="post.title"
                    :description="post.excerpt"
                    quote="I found this informative, I think you should read it too"
                    hashtags="VoiceTelevision"
                  >
                    <v-icon color="indigo">{{mdiFacebook}}</v-icon>
                  </ShareNetwork>
                </v-btn>
                <v-btn icon x-small class="mr-1 mr-lg-3">
                  <ShareNetwork
                    network="whatsapp"
                    :url="getUrl()"
                    :title="post.title"
                    :description="post.excerpt"
                    quote="I found this informative, I think you should read it too"
                    hashtags="VoiceTelevision"
                  >
                    <v-icon color="green">{{mdiWhatsapp}}</v-icon>
                  </ShareNetwork>
                </v-btn>
                <v-btn icon x-small class="mr-1 mr-lg-3">
                  <ShareNetwork
                    network="twitter"
                    :url="getUrl()"
                    :title="post.title"
                    :description="post.excerpt"
                    quote="I found this informative, I think you should read it too"
                    hashtags="VoiceTelevision"
                  >
                    <v-icon color="blue">{{mdiTwitter}}</v-icon>
                  </ShareNetwork>
                </v-btn>
                <v-btn icon x-small class="mr-1 mr-lg-3">
                  <ShareNetwork
                    network="linkedin"
                    :url="getUrl()"
                    :title="post.title"
                    :description="post.excerpt"
                    quote="I found this informative, I think you should read it too"
                    hashtags="VoiceTelevision"
                  >
                    <v-icon color="blue darken-3">{{mdiLinkedin}}</v-icon>
                  </ShareNetwork>
                </v-btn>
                <v-btn icon x-small @click="print" class="mr-1 mr-lg-3">
                  <v-icon>{{mdiPrinter}}</v-icon>
                </v-btn>
              </div>
              <div class="float-right d-print-none">
                <v-btn icon x-small class="mr-1 mr-lg-3" @click="postFontSize += 0.1">
                  <v-icon>{{mdiFormatFontSizeIncrease}}</v-icon>
                </v-btn>
                <v-btn icon x-small class="mr-1 mr-lg-3" @click="postFontSize -= 0.1">
                  <v-icon>{{mdiFormatFontSizeDecrease}}</v-icon>
                </v-btn>
              </div>
            </v-col>
          </v-row>
          <v-divider class="my-2"/>
        </v-col>
        <v-col cols="3">
          <advertisment/>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="9">
          <v-row>
            <v-col cols="12">
              <figure
                v-if="post.pictures"
                class="pa-4"
              >
                <img
                  class="white--text align-end d-print-none featured-image"
                  :aspect-ratio="16/9"
                  :src="getImageSrc(post.pictures[0].full)"
                  :alt="post.seo.meta_title"
                >
                <figcaption class="font-italic font-weight-light" style="text-align: left; border-bottom: 1px solid lightgray" v-text="(post.figcaption !== undefined ? post.figcaption : post.seo.meta_title) + ' | ছবি: ' + (post.figImageAuthor !== undefined ? post.figImageAuthor : 'নিজস্ব ডেস্ক')"></figcaption>
              </figure>
              <v-responsive :aspect-ratio="16/9" v-else>
                <v-skeleton-loader type="image" class="fill-height"></v-skeleton-loader>
              </v-responsive>
            </v-col>
            <v-col cols="10" class="mx-auto">
              <v-card id="printMe" v-if="post" flat class="mx-lg-8">
                <!--                <v-system-bar app light color="white" height="100px" class="d-none d-print-block">-->
                <!--                  <v-row class="d-print-flex justify-space-between">-->
                <!--                    <v-col cols="6">-->
                <!--                      <img class="" src="/assets/images/logo.png" style="height: 60px; width: auto;" />-->
                <!--                    </v-col>-->
                <!--                    <v-col cols="6">-->
                <!--                      <span>Printed on {{showDate()}}</span>-->
                <!--                    </v-col>-->
                <!--                  </v-row>-->
                <!--                </v-system-bar>-->
                <img v-if="post.pictures" :src="getImageSrc(post.pictures[0].full)" class="d-print-block d-none" :alt="post.seo.meta_title" style="width: 100%; height: auto;" />
                <!--            <v-img
                              lazy-src="/assets/images/image-placeholder.png"
                              :src="getImageSrc(post.pictures[0].full)"
                              contain
                            ></v-img>-->
                <v-card-text id="main-news-body" v-if="post.description" v-html="post.description" :style="{ fontSize: postFontSize + 'em' }"></v-card-text>
                <v-divider width="200px"/>
                <v-card-title>ট্যাগ ও ক্যাটাগরি</v-card-title>
                <v-card-actions v-if="post.categories.length" class="pl-4">
                  <v-icon title="Categories" class="mr-3">mdi-card-multiple-outline</v-icon>
                  <v-btn :title="cat.title" v-for="(cat, c) in post.categories" outlined :key="c" small color="gray lighten-3 blue--text">
                    {{cat.title}}
                  </v-btn>
                </v-card-actions>
                <v-card-actions v-if="post.tags.length" class="pl-4">
                  <v-icon title="Tags" class="mr-3">mdi-tag-multiple-outline</v-icon>
                  <v-btn v-for="(tag, t) in post.tags" outlined :key="t" small>
                    {{tag.title}}
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="3">
          <MultiPostListImageInset :widget_settings="{show_title: 1, show_excerpt: 1}" :data_limit="4" :title="'এ সম্পর্কিত আরও পড়ুন'" ></MultiPostListImageInset>
          <MultiPostListImageLeft :widget_settings="{show_title: 1, show_excerpt: 1}" :data_limit="5" :title="'এ সম্পর্কিত আরও পড়ুন'" ></MultiPostListImageLeft>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-card-text>
            <MultiPostColumns taxonomy="category_ids" :data="post.categories[0].id" page="2" :data_limit="4" :title="post.categories[0].title + ' থেকে আরও পড়ুন'" :widget_settings="{show_title: 1, show_excerpt: 0}"></MultiPostColumns>
          </v-card-text>
        </v-col>
      </v-row>
    </v-container>
    <Footer/>
  </div>
</template>

<script>
import Vue from 'vue';
import MultiPostListImageLeft from "~/components/widgets/MultiPostListImageLeft";
import MultiPostListImageInset from "~/components/widgets/MultiPostListImageInset";
import Advertisment from "~/components/frontend/Advertisment";
import {mdiFacebook, mdiYoutube, mdiTwitter, mdiInstagram, mdiLinkedin, mdiWhatsapp, mdiTagMultipleOutline, mdiPrinter, mdiPencil, mdiCalendar, mdiFormatFontSizeIncrease, mdiFormatFontSizeDecrease} from "@mdi/js";
import VueSocialSharing from 'vue-social-sharing'
Vue.use(VueSocialSharing);

import VueHtmlToPaper from 'vue-html-to-paper';
import Navbar2 from "@/components/widgets/Navbar2";
import MultiPostColumns from "@/components/widgets/MultiPostColumns";
import Navbar from "~/components/widgets/Navbar";
import Footer from "~/components/widgets/Footer";
const options = {
  name: 'blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css'
  ]
}
Vue.use(VueHtmlToPaper, options);

export default {
  name: "Slug",
  components: {Navbar, Footer, MultiPostColumns, Navbar2, Advertisment, MultiPostListImageInset, MultiPostListImageLeft},
  layout: 'frontend',
  data() {
    return {
      postFontSize: 1.2,
      post: null,
      errorResponse: null,
      loading: true,
      loaded: false,
      mdiTagMultipleOutline: mdiTagMultipleOutline,
      mdiLinkedin: mdiLinkedin,
      mdiYoutube: mdiYoutube,
      mdiTwitter: mdiTwitter,
      mdiInstagram: mdiInstagram,
      mdiFacebook: mdiFacebook,
      mdiPrinter: mdiPrinter,
      mdiWhatsapp: mdiWhatsapp,
      mdiCalendar: mdiCalendar,
      mdiPencil: mdiPencil,
      mdiFormatFontSizeIncrease: mdiFormatFontSizeIncrease,
      mdiFormatFontSizeDecrease: mdiFormatFontSizeDecrease,
      items: [
        {
          text: 'প্রচ্ছদ',
          disabled: false,
          to: '/',
        },
        {
          text: 'Category',
          disabled: true,
          href: '#',
        },
        {
          text: this.$route.params.slug,
          disabled: false,
          to: {name: 'slug', params: {id: this.$route.params.slug}},
        }
      ]
    };
  },
  methods: {
    getUrl() {
      return this.$config.baseURL+'/'+ this.$route.fullPath;
    },
    print() {
      // Pass the element id here
      this.$htmlToPaper('printMe');
    },
    showDate() {
      var date = new Date();
      var n = date.toDateString();
      var time = date.toLocaleTimeString();
      return n + ' ' + time;
    },
    formatDate(string) {
      var str = new Date(string).toLocaleString('bn-BD');
      return str.split(" ")[0];
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
    replaceIframe(str) {
      const regex = /(<iframe.*?>.*?<\/iframe>)/g;
      return str.replace(regex, "");
    },
    formatForWeb(str) {
      str = this.replaceIframe(str);
      return str;
    },
    getImageSrc(src) {
      if (/^(?:[a-z]+:)?\/\//i.test(src)) {
        return src;
      }
      return this.$config.apiBaseURL + '/storage' + src;
    }
  },
  asyncData(context) {
    return context.$axios
      .get(
        '/frontend/get-post', {
          params: {
            slug: decodeURI(context.params.slug)
          }
        }
      )
      .then((res) => {
        if (res.data.length === 0 || res.data.error) {
          throw({ statusCode: 404, message: 'Post not found' })
        }

        let items = [
          {
            text: 'প্রচ্ছদ',
            disabled: false,
            href: '/',
          },
          {
            text: res.data.categories ? res.data.categories[0].title : 'শ্রেণী বহির্ভূত',
            disabled: false,
            to: {name: 'category-id', params: {id: res.data.categories[0].slug}},
          },
          {
            text: res.data.title,
            disabled: false,
            to: {name: 'slug', params: {id: context.params.slug}},
          },
        ];
        return { post: res.data, items: items }
      })
      .catch(error => {
        if (error.response) {
          // Request made and server responded
          //this.errorResponse = error.response.data;
          //this.errorCode = error.response.status;
        } else if (error.request) {
          // The request was made but no response was received
          //console.log(error.request);
        } if (error.statusCode === 404) {
          //return context.error({ statusCode: 404, message: error.message })
        } else {
          // Something happened in setting up the request that triggered an Error
          //return {error: error.message};
          //return context.error({ statusCode: 500, message: error.message })
        }
      });
  },
}
</script>

<style scoped>
*,body {
  font-family: SolaimanLipi;
}
h1 {
  font-size: 2.4em;
}
#main-news-body img,  img.featured-image {
  width: 100%;
  height: auto;
}
.v-card__subtitle, .v-card__text {
  line-height: 1.5;
}

</style>
