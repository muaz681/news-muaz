<template>
  <v-row ref="form">
    <v-container fluid>
    <v-col class="col-">

      <v-card
        class="mx-auto"
        height=""
      >
        <v-toolbar
          color="indigo"
          class="font-weight-regular"
          dark
          dense
        >
          <v-toolbar-title>Import Wordpress XML</v-toolbar-title>
        </v-toolbar>
        <v-container fluid>
          <v-alert
            dense
            outlined
            type="warning"
            dismissible
            v-model="alert_value"
          >
            Please Select a File
          </v-alert>
          <v-alert
            dense
            outlined
            :type="color"
            dismissible
            v-model="alert_info"
          >
            {{ res }}
          </v-alert>
          <v-row dense>
            <v-col class="col-8">
              <v-card class="mt- mb-5">
                <v-card-title>
                    Step 1: Select Yout XML File
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="">
                  <v-file-input
                    prepend-icon="mdi-xml"
                    v-model="xml"
                    label="Select a wordpress xml file"
                    accept=".xml"
                    required
                    outlined
                  ></v-file-input>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                  <!-- <v-btn text>
                    Back
                  </v-btn> -->
                  <v-spacer></v-spacer>
                  <v-btn
                    color="primary"
                    @click="submit"
                  >
                    Upload File
                  </v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-col>
            <v-col
              class="col-8"
            >
            <v-card class="mt- mb-5">

              <v-alert
                dense
                outlined
                :type="migrate_alert_color"
                dismissible
                v-model="migrate_alert"
              >
                {{ migrate_alert_res }}
              </v-alert>
                <v-card-title>
                  Step 2: Store data which has been uploaded <small> (You can Run Migration if File already Uploaded) </small>
                </v-card-title>

                <v-divider></v-divider>
                <v-card-text >
                  <v-checkbox
                    v-model="check"
                    color="red"
                    class="mb-4"
                    label="Check this if you want to remove previous data!!"
                  ></v-checkbox>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                  color="success"
                  @click="migrate">
                  Migrate
                </v-btn>
                <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-col>
    </v-container>
  </v-row>
</template>
<script>
export default {
  layout: 'backend',
  data: () => ({

    xml: null,
    color: "info",
    check: null,
    xmlRules: [
        v => !!v || 'Please Select a File',
      ],
    alert_value: false,
    alert_info: false,
    res: '',
    migrate_alert_res: false,
    migrate_alert: false,
    migrate_alert_color: 'info'
  }),

  methods: {
    submit(){
      // console.log(this.xml);
      if (this.xml !== null) {
        var formData = new FormData();
        formData.append("file", this.xml);
        // console.log(formData);
        try {
          this.alert_value = false;
          this.res = "Please Wait for Upload!";
          this.alert_info = true;

          this.$axios.post('xml-store', formData, {
            // headers: {
            //   'Content-Type': 'multipart/form-data',
            // }
        })
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw({ statusCode: 404, message: 'Social Link not found' })
          }
          // console.log(res.data);
          this.res = res;
          this.color = "success";
          this.alert_info = true;
          return { res: res.data[0] }
        })
          // this.$router.push('/seo/seo')
        } catch (e) {
          // this.error = e.response.data.message
          this.res = "Error found";
          this.color = "danger";
          this.alert_info = true;
          // console.log(e);
        }
      } else {
        this.alert_value = true;
      }
    },
    migrate(){
      this.alert_value = false;
      this.res = "Please wait for complete storing Data!";
      this.alert_info = false;
      this.color = 'info';

      try {
        var data = this.check == true?1:0;
        this.$axios.post('run-query/'+ data, {
            // headers: {
            //   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // }
        })
        .then((res) => {
          if (res.data.length === 0 || res.data.error) {
            throw({ statusCode: 404, message: 'Error found' })
          }
          // console.log('Response from API: ', res.data[0]);
          this.migrate_alert = true,
          this.migrate_alert_res = res.data[0];
          this.migrate_alert_color = "success";
        })
      } catch (e) {
        // this.error = e.response.data.message
        this.res = "Error found";
        this.color = "error";
        this.alert_info = true;
        // console.log(e);
      }
    }
  },
}
</script>
<style>
.row{
  justify-content: center;
}
</style>
