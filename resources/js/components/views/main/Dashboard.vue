<template>
  <v-app id="inspire">
    <v-main>
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
                <span> <v-icon size="80"> mdi-emoticon </v-icon> </span>
              </div>
              <div class="text-h6 mb-5">
                Selamat Datang di APASI
              </div>
              <v-divider></v-divider>
              <v-btn color="primary" small class="mt-5" href="/tabelDinamis">
                <v-icon>
                  mdi-table
                </v-icon>
                Tabel Dinamis
              </v-btn>
              <v-btn color="primary" small class="mt-5" href="/publikasi">
                <v-icon>
                  mdi-book
                </v-icon>
                Publikasi
              </v-btn>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {};
  },
  created() {},
  methods: {}
};
</script>

<style></style>
