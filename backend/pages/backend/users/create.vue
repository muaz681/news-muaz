<template>
  <v-row ref="form" v-model="valid" lazy-validation>
    <!-- Left Side -->
    <v-col class="col-12">
      <v-container fluid>
        <v-row dense>
          <v-col class="col-12">
            <v-card class="mx-auto">
              <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                <v-toolbar-title>User Information</v-toolbar-title>
              </v-toolbar>

              <v-container fluid>
                <v-row dense>
                  <v-col class="col-12">
                    <v-card>
                      <v-card-text>
                        <v-text-field
                          ref="username"
                          v-model="username"
                          :rules="[() => !!username || 'User Name is required']"
                          :error-messages="errorMessages"
                          label="User Name"
                          placeholder=""
                          required
                          outlined
                        ></v-text-field>
                        <v-text-field
                          label="Email"
                          name="email"
                          :rules="emailRules"
                          v-model="email"
                          required
                          outlined
                        ></v-text-field>
                        <v-text-field
                          label="Password"
                          name="password"
                          v-model="password"
                          :rules="passwordRules"
                          type="password"
                          required
                          outlined
                        ></v-text-field>
                        <v-text-field
                          label="Confirm Password"
                          name="confirm_password"
                          :rules="[confirm_passwordRules, passwordConfirmationRule]"
                          v-model="confirm_password"
                          type="password"
                          required
                          outlined
                        ></v-text-field>

                      </v-card-text>
                      <v-divider class="mt-12"></v-divider>
                      <v-card-actions>
                        <!-- <v-btn text>
                      Cancel
                    </v-btn> -->
                        <v-spacer></v-spacer>
                        <v-btn color="primary" text @click="submit">
                          Submit
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-col>
    <!-- Right Side -->
    <v-col class="">
      <!-- Published Type -->
      <!-- <v-container fluid>
        <v-row dense>
          <v-col class="col-12">
            <v-card class="mx-auto">
              <v-toolbar color="indigo" class="font-weight-regular" dark dense>
                <v-toolbar-title>Published</v-toolbar-title>
              </v-toolbar>
              <v-card class="overflow-auto" max-height="150">
                <v-container fluid>
                  <v-row dense>
                    <v-col class="col-12">
                      <v-switch
                        v-model="status"
                        :label="status ? 'User Active' : 'User Deactive'"
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card>
            </v-card>
          </v-col>
        </v-row>
      </v-container> -->
      <!-- Published Type End -->
    </v-col>
  </v-row>
</template>
<script>
export default {
  layout: 'backend',
  data: () => ({
    valid: true,
    status: true,
    errorMessages: "",
    username: "",
    email: '',
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+/.test(v) || 'E-mail must be valid',
    ],
    password: '',
    confirm_password: '',
    passwordRules: [
      v => !!v || 'Please type password.',
      v => (v && v.length >= 8) || 'minimum 8 characters',
    ],
    confirm_passwordRules: [
      v => !!v || "Confirm Password",
    ],
    formHasErrors: false,
  }),

  computed: {
    passwordConfirmationRule() {
        return () => (this.password === this.confirm_password) || 'Password must match'
    },
  },

  watch: {
    name() {
      this.errorMessages = "";
    },

  },

  methods: {
    submit() {
      if (this.username.length && this.email.length !== 0) {
        var formData = new FormData();
        formData.append("name", this.username);
        formData.append("email", this.email);
        formData.append("password", this.password);
        formData.append("confirm_password", this.confirm_password);

        try {
          this.$axios
          .post("/register", formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then((res) => {
              this.$axios.get("/users").then((res) => {
                if (res.data.length === 0 || res.data.error) {
                  throw { statusCode: 404, message: "Users not found" };
                }
                this.users = res.data.users;
              });
              this.$router.push("/users/users");
            });
        } catch (e) {
          this.error = e.response.data.message;
        }
      } else {
        // this.$refs.form.validate()
        // alert("Please fill the required Fields");
      }
    },

  },
};
</script>
