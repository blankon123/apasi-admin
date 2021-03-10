<template>
  <v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" flat>
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
              <v-card-text class="text-center">
                <div class="text-h1">
                  <span>
                    4<v-icon size="80">
                      mdi-alert-octagram-outline </v-icon
                    >4
                  </span>
                </div>
                <div class="text-h6 mb-5">
                  {{ title }}
                </div>
                <v-divider></v-divider>
                <v-btn color="primary" small class="mt-5" href="/dashboard">
                  <v-icon>
                    mdi-home
                  </v-icon>
                  Dashboard
                </v-btn>
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
      title: "Ups, URL Tidak Ditemukan"
    };
  },
  created() {},
  methods: {}
};
</script>

<style></style>
