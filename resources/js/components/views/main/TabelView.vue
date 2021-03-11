<template>
  <div>
    <v-snackbar
      v-model="snackbar.show"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
    >
      {{ snackbar.text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar.show = false">
          Tutup
        </v-btn>
      </template>
    </v-snackbar>
    <v-card fluid class="mb-2" elevation="2">
      <v-card-title class="text-left mb-0 pb-0">
        {{ tabel.judul_tabel ? tabel.judul_tabel : "" }}
        <v-spacer></v-spacer>
        <v-btn small @click="refresh" icon :loading="loading">
          <v-icon>mdi-refresh</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text class="text-left">
        <span
          ><v-chip small outlined :color="colorize(tabel.category_id)">{{
            categoryName(tabel.category_id)
          }}</v-chip></span
        >
        <span
          ><v-chip small outlined :color="colorize(tabel.category_id)">{{
            subjectName(tabel.subject_id)
          }}</v-chip></span
        >
        <span
          ><v-chip small :color="userColor">{{ username }}</v-chip></span
        >
        <div class="text--primary"></div>
      </v-card-text>
      <v-progress-linear
        indeterminate
        color="cyan"
        rounded
        v-if="loading"
      ></v-progress-linear>
    </v-card>
    <v-row>
      <v-col md="5">
        <tabel-data
          :loading="webLoading"
          :tabelData="tabelDataWeb"
        ></tabel-data>
      </v-col>
      <v-col md="5">
        <v-card>
          This is Second Card
        </v-card>
      </v-col>
      <v-col md="2">
        <v-card>
          This is Third Card
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import TabelData from "./../components/tabelDinamis/TabelData";

export default {
  components: {
    TabelData
  },
  data: () => ({}),
  created() {
    this.$store.dispatch("userStore/getBidang");
    this.$store.dispatch("tabelDinamisStore/setCategories");
    this.$store.dispatch("tabelDinamisStore/setSubjects");
    this.$store.dispatch("tabelDinamisStore/setTableData");
    this.$store.dispatch("tabelDinamisStore/getTabel", this.$route.params.id);
    this.$store.dispatch(
      "tabelDinamisStore/getTabelWeb",
      this.$route.params.web_id
    );
  },
  computed: {
    tabelDataWeb() {
      return this.$store.state.tabelDinamisStore.tabelDetail.tabelDataWeb;
    },
    loading() {
      return this.$store.state.tabelDinamisStore.tabelDetail.loading;
    },
    webLoading() {
      return this.$store.state.tabelDinamisStore.tabelDetail.webLoading;
    },
    username() {
      return this.tabel.user?.name;
    },
    userColor() {
      return this.tabel.user?.color + " darken-2";
    },
    tabel() {
      return this.$store.state.tabelDinamisStore.tabelDetail.tabel;
    },
    subjects() {
      return this.$store.state.tabelDinamisStore.subjects;
    },
    categories() {
      return this.$store.state.tabelDinamisStore.categories;
    },
    bidangs() {
      return this.$store.state.userStore.users;
    },
    snackbar() {
      return this.$store.state.tabelDinamisStore.snackbar;
    }
  },
  mounted() {},
  methods: {
    colorize(val) {
      let color = ["gray", "blue", "orange", "green"];
      return color[val];
    },
    refresh() {
      this.$store.dispatch("tabelDinamisStore/getTabel", this.$route.params.id);
    },
    subjectName(val) {
      return this.subjects.find(obj => {
        return obj.sub_id == val;
      })?.title;
    },
    categoryName(val) {
      return this.categories.find(obj => {
        return obj.subcat_id == val;
      })?.title;
    }
  }
};
</script>

<style></style>
