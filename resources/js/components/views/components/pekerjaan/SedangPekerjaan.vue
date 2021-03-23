<template>
  <div>
    <v-dialog v-model="batalDialog.show" max-width="500px">
      <v-card>
        <v-card-title>
          Konfirmasi Batal
        </v-card-title>
        <v-card-text>
          Apakah anda Yakin akan Membatalkan Pekerjaan
          {{ batalDialog.form.nama }} ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="batalDialogInit"
            >Tidak</v-btn
          >
          <v-btn color="blue darken-1" text @click="batal(batalDialog.form.id)"
            >Ya</v-btn
          >
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          v-if="batalDialog.loading"
        ></v-progress-linear>
      </v-card>
    </v-dialog>
    <v-card-title>
      {{ this.title }}
      <v-spacer></v-spacer>
      <v-icon :color="this.color">{{ this.icon }}</v-icon>
      <v-row class="pb-0 mb-0">
        <v-col md="8" sm="12" class="pb-0 mb-0">
          <v-text-field
            clearable
            @keyup.enter="cariPekerjaan"
            v-model="keywordPekerjaan"
            label="Cari Pekerjaan"
            hide-details
          ></v-text-field>
        </v-col>
        <v-col md="4" sm="12" class="pb-0 mb-0">
          <v-switch
            v-model="limitJumlah"
            :label="this.limitJumlah ? 'Limit 10' : 'Limit Off'"
          ></v-switch>
        </v-col>
      </v-row>
    </v-card-title>
    <v-divider></v-divider>
    <div v-if="this.pekerjaan && this.pekerjaan.length">
      <div v-for="(item, i) in this.pekerjaan" :key="i">
        <v-card outlined class="ma-2 pa-0" :color="item.color">
          <div>
            <div class="d-flex flex-no-wrap justify-space-between">
              <v-card-title
                class="headline font-weight-bold text-uppercase my-0 py-0"
                v-text="item.tipe_pekerjaan"
              >
              </v-card-title>
              <div>
                <v-btn
                  rounded
                  x-small
                  elevation="2"
                  color="red"
                  @click="batalDialogShow(item)"
                >
                  <v-icon left>
                    mdi-trash-can
                  </v-icon>
                  Batalkan
                </v-btn>
                <layout-pekerjaan
                  v-if="item.tipe_pekerjaan == 'layout'"
                  :petugases="petugases"
                  :item="item"
                ></layout-pekerjaan>
                <revisi-publikasi-pekerjaan
                  v-if="item.tipe_pekerjaan == 'revisi'"
                  :petugases="petugases"
                  :item="item"
                >
                </revisi-publikasi-pekerjaan>
                <hapus-tabel-pekerjaan
                  v-if="item.tipe_pekerjaan == 'hapus tabel'"
                  :petugases="petugases"
                  :item="item"
                >
                </hapus-tabel-pekerjaan>
                <tambah-data-tabel-pekerjaan
                  v-if="item.tipe_pekerjaan == 'data tabel'"
                  :petugases="petugases"
                  :item="item"
                ></tambah-data-tabel-pekerjaan>
                <edit-tabel-pekerjaan
                  v-if="item.tipe_pekerjaan == 'edit tabel'"
                  :petugases="petugases"
                  :item="item"
                >
                </edit-tabel-pekerjaan>
                <tambah-tabel
                  v-if="item.tipe_pekerjaan == 'tambah tabel'"
                  :petugases="petugases"
                  :item="item"
                ></tambah-tabel>
              </div>
            </div>

            <v-card-subtitle class="text-start py-0 my-0">
              {{ item.nama }} oleh
              <span class="font-weight-bold">
                {{ findPetugas(item.petugas_id) }}</span
              >
            </v-card-subtitle>
          </div>
        </v-card>
      </div>
    </div>
    <div v-else>
      <v-card-text class="gray--text text--darken-1">
        Tidak Ada Pekerjaan
      </v-card-text>
    </div>
  </div>
</template>

<script>
import EditTabelPekerjaan from "./tipe/EditTabelPekerjaan.vue";
import HapusTabelPekerjaan from "./tipe/HapusTabelPekerjaan.vue";
import LayoutPekerjaan from "./tipe/LayoutPekerjaan.vue";
import RevisiPublikasiPekerjaan from "./tipe/RevisiPublikasiPekerjaan.vue";
import TambahTabel from "./tipe/TambahTabel.vue";

export default {
  components: {
    LayoutPekerjaan,
    RevisiPublikasiPekerjaan,
    HapusTabelPekerjaan,
    EditTabelPekerjaan,
    TambahTabel
  },
  name: "SedangPekerjaan",
  props: ["pekerjaan", "loading", "title", "color", "icon"],
  data() {
    return {
      limitJumlah: false,
      keywordPekerjaan: ""
    };
  },
  watch: {
    limitJumlah(val) {
      this.$store.dispatch("pekerjaanStore/filterSedangPekerjaan", {
        keyword: this.keywordPekerjaan,
        limit: val
      });
    }
  },
  computed: {
    batalDialog() {
      return this.$store.state.pekerjaanStore.batalDialog;
    },
    petugases() {
      return this.$store.state.petugasStore.all;
    }
  },
  methods: {
    cariPekerjaan() {
      this.$store.dispatch("pekerjaanStore/filterSedangPekerjaan", {
        keyword: this.keywordPekerjaan,
        limit: this.limitJumlah
      });
    },
    findPetugas(petugas_id) {
      return this.petugases.filter(item => item.id == petugas_id)[0]
        ?.nama_singkat;
    },
    batalDialogInit() {
      this.$store.dispatch("pekerjaanStore/batalDialogInit");
    },
    batalDialogShow(item) {
      this.$store.dispatch("pekerjaanStore/batalDialogShow", item);
    },
    batal(item) {
      this.batalDialog.loading = true;
      this.$store.dispatch("pekerjaanStore/batal", item);
    }
  }
};
</script>

<style></style>
