<template>
    <v-container>
        <v-row justify="center">
          <v-col
            cols="2"
          >
            <PostRendererLeftImage/>
            <PostRendererLeftImage/>
            <PostRendererLeftImage/>
            <Advertisment/>
            
          </v-col>
          <v-col
            cols="5"
          >
            <HeadingNews/>
            <v-row class="py-2">
              <v-col cols="6" class="lead-below-item">
                <PostRendererLeftImage/>
              </v-col>
              <v-col cols="6">
                <PostRendererLeftImage/>
              </v-col>
            </v-row>
          </v-col>
          <v-col
            cols="2"
          >
          <PrayCorner/>
            <PostRendererTopImage/>
            <Advertisment/>
          </v-col>
        </v-row>
      </v-container>
</template>
<script>
import PostRendererLeftImage from "./frontend/PostRendererLeftImage.vue";
import HeadingNews from "./frontend/HeadingNews.vue";
import PostRendererTopImage from "./frontend/PostRendererTopImage.vue";
import PrayCorner from "./frontend/PrayCorner.vue";
import Advertisment from "./frontend/Advertisment.vue";

  export default {
    components: { PostRendererLeftImage, HeadingNews, PostRendererTopImage, PrayCorner, Advertisment }
}
</script>
<style>
    
      
                            
    
    .main-news-title{
        line-height: 28px !important;
        padding: 2px 0 !important;
        white-space: normal;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        display: -webkit-box;
        text-align: justify;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 1px;
        overflow: hidden;
        font-family: Arial,voice,Sans-Serif;
    }
    .news-title{
        line-height: 25px !important;
        padding: 4px 12px 0px 0px !important;
        white-space: normal;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        display: -webkit-box;
        text-align: justify;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0px;
        overflow: hidden;
        font-family: Arial,voice,Sans-Serif;
    }
    .main-card{
        background-color: transparent !important;
        color: #18191a !important;
        box-shadow: none !important;
        margin-bottom: 10px;
        border-radius: 0px !important;
    }
    .text-card{
      display: block !important;
    }
    .text-card .v-card__subtitle{
      margin-top: 0px !important;
      padding: 1px 0;
      font-size: 16px;
      font-weight: 400;
      text-align: justify;
      line-height: 22px;
      overflow: hidden;
      font-family: Arial,voice,Sans-Serif;
      white-space: normal;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      display: -webkit-box;
    }
    .main-card .v-card__actions{
      padding: 2px !important;
    }
    .advertisement{
      border-radius: 0px !important;
      margin-top: 15px;
    }
    .lead-below-item {
    border-right: 4px solid #c3c3c359;
}
</style>