<template>
  <div>
    <v-row dense>
      <v-col lg="6" sm="12" xs="12">
        <v-card elevation="2">
          <v-row class="ma-0 pa-0" align="center" justify="center">
            <v-col cols="10">
              <v-card-title class="justify-start text-left">
                Daftar User
              </v-card-title>
              <v-card-subtitle class="justify-start text-left">
                User yaitu Bidang/Sub-Bagian
              </v-card-subtitle>
            </v-col>
            <v-col cols="2" class="justify-center text-center">
              <v-btn color="primary" elevation="8" small>
                <v-icon> mdi-account-multiple-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-data-table
            :headers="users.header"
            :items="users.items"
            item-key="name"
            class="elevation-1"
            :search="searchUser"
            :custom-filter="filterTextUser"
            :loading="users.loading"
          >
            <template v-slot:top>
              <v-text-field
                v-model="searchUser"
                label="Cari disini..."
                class="mx-4"
              ></v-text-field>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
      <v-col lg="6" sm="12" xs="12">
        <v-card elevation="2">
          <v-row class="ma-0 pa-0" align="center" justify="center">
            <v-col cols="10">
              <v-card-title class="justify-start text-left">
                Daftar Petugas
              </v-card-title>
              <v-card-subtitle class="justify-start text-left">
                Petugas Layout atau Upload ke Portal Publikasi
              </v-card-subtitle>
            </v-col>
            <v-col cols="2" class="justify-center text-center">
              <v-btn color="primary" elevation="8" small>
                <v-icon> mdi-account-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-data-table
            :headers="petugasTable.headers"
            :items="petugases"
            item-key="nama"
            class="elevation-2"
            :search="searchPetugas"
            :custom-filter="filterTextPetugas"
            :loading="petugasTable.loading"
          >
            <template v-slot:top>
              <v-text-field
                v-model="searchPetugas"
                label="Cari disini..."
                class="mx-4"
              ></v-text-field>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchPetugas: "",
      searchUser: ""
    };
  },
  created() {
    this.$store.dispatch("userStore/getUserTable");
    this.$store.dispatch("petugasStore/getPetugasTable");
  },
  computed: {
    users() {
      return this.$store.state.userStore.userTable;
    },
    petugases() {
      return this.$store.state.petugasStore.all;
    },
    petugasTable() {
      return this.$store.state.petugasStore.petugasTable;
    }
  },
  methods: {
    filterTextPetugas(value, search, item) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value
          .toString()
          .toLocaleLowerCase()
          .indexOf(search) !== -1
      );
    },
    filterTextUser(value, search, item) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value
          .toString()
          .toLocaleLowerCase()
          .indexOf(search) !== -1
      );
    }
  }
};
</script>

<style></style>
