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
      </template> </v-snackbar
    ><v-fab-transition>
      <v-btn color="blue" fab absolute bottom right @click="refresh">
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </v-fab-transition>
    <v-row>
      <v-col lg="4" sm="12" xs="12">
        <v-card :loading="this.loading">
          <belum-pekerjaan
            title="Belum Dikerjakan"
            icon="mdi-progress-alert"
            color="red darken-2"
            :pekerjaan="this.pekerjaan.belum"
          ></belum-pekerjaan>
          <v-divider></v-divider>
        </v-card>
      </v-col>

      <v-col lg="4" sm="12" xs="12">
        <v-card :loading="this.loading">
          <sedang-pekerjaan
            title="Sedang Dikerjakan"
            icon="mdi-progress-clock"
            color="blue darken-2"
            :pekerjaan="this.pekerjaan.sedang"
          >
          </sedang-pekerjaan>
          <v-divider></v-divider>
        </v-card>
      </v-col>

      <v-col lg="4" sm="12" xs="12">
        <v-card :loading="this.loading">
          <sudah-pekerjaan
            title="Sudah Dikerjakan"
            icon="mdi-progress-check"
            color="green darken-2"
            :pekerjaan="this.pekerjaan.sudah"
          >
          </sudah-pekerjaan>
          <v-divider></v-divider>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import BelumPekerjaan from "../components/pekerjaan/BelumPekerjaan.vue";
import SedangPekerjaan from "../components/pekerjaan/SedangPekerjaan.vue";
import SudahPekerjaan from "../components/pekerjaan/SudahPekerjaan.vue";

export default {
  components: { BelumPekerjaan, SedangPekerjaan, SudahPekerjaan },
  props: {},
  data() {
    return {
      filterJumlah: [5, 10, 50, "Semua"]
    };
  },
  computed: {
    snackbar() {
      return this.$store.state.pekerjaanStore.snackbar;
    },
    loading() {
      return this.$store.state.pekerjaanStore.isLoading;
    },
    pekerjaan() {
      return this.$store.state.pekerjaanStore.pekerjaan;
    }
  },
  created() {
    this.$store.dispatch("pekerjaanStore/init");
    this.$store.dispatch("petugasStore/getPetugasTable");
  },
  methods: {
    refresh() {
      this.$store.dispatch("pekerjaanStore/init");
    }
  }
};
</script>

<style></style>
