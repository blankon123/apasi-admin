<template>
  <v-app id="inspire" class="purple">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12" :loading="isLoading">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Aplikasi Pembantu Diseminasi</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-btn icon large target="_blank" v-on="on">
                      <v-icon>mdi-bookshelf</v-icon>
                    </v-btn>
                  </template>
                  <span>[APASI]</span>
                </v-tooltip>
              </v-toolbar>
              <v-card-text>
                <v-form @submit.prevent="doLogin">
                  <v-text-field
                    label="Username"
                    name="username"
                    prepend-icon="mdi-account"
                    type="text"
                    v-model="form.username"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="form.password"
                  ></v-text-field>
                  <v-alert
                    text
                    v-if="errors.length > 0"
                    prominent
                    type="error"
                    icon="mdi-alert-remove"
                  >
                    {{ errors }}
                  </v-alert>
                  <v-col class="text-right mt-3">
                    <v-btn color="primary" type="submit">Login</v-btn>
                  </v-col>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      isLoading: false,
      form: {
        username: "ipds6200",
        password: "rahasia",
        device_name: "browser"
      },
      errors: {}
    };
  },
  created() {},
  methods: {
    doLogin() {
      this.isLoading = "white";
      axios
        .post("api/v1/login", this.form)
        .then(response => {
          localStorage.setItem("apasi_cred", response.data.token);
          this.$store.dispatch("userStore/setUser", response.data.user);
          this.$router.push("/dashboard");
        })
        .catch(errors => {
          console.log(errors);
          this.errors = errors.response;
        })
        .finally(() => {
          this.isLoading = false;
        });
    }
  }
};
</script>

<style></style>
