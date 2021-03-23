<template>
  <span>
    <v-dialog v-model="show" max-width="700px">
      <v-card>
        <v-card-title>
          <span>
            Konfirmasi Pekerjaan
          </span>
        </v-card-title>
        <v-card-text>
          <v-tabs fixed-tabs>
            <v-tab>
              <v-icon>mdi-note</v-icon>
              Konfirmasi
            </v-tab>
            <v-tab>
              <v-icon>mdi-database</v-icon>
              Request Data
            </v-tab>
            <v-tab-item>
              <div class="mb-2 pb-2">{{ item.nama }} telah diselesaikan :</div>
              <v-form ref="formPetugas">
                <v-select
                  prepend-inner-icon="mdi-account-hard-hat"
                  v-model="item.petugas_id"
                  :items="petugases"
                  item-text="nama_singkat"
                  item-value="id"
                  label="Petugas Pelaksana"
                  outlined
                  dense
                  hide-details
                  class="mb-3"
                  :rules="[rules.required]"
                ></v-select>
              </v-form>
            </v-tab-item>
            <v-tab-item>
              <show-histori-data
                id="tabelApasi"
                :tabelHandsonData="item.pekerjaanable.data.data"
                :tabelHandsonMerged="item.pekerjaanable.data.merged"
              ></show-histori-data>
            </v-tab-item>
          </v-tabs>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="show = false">Batal</v-btn>
          <v-btn color="green darken-1" text @click="kerjakan">
            Selesaikan
          </v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-divider></v-divider>
        <div>
          <p class="red--text text text-center">
            {{ info }}
          </p>
        </div>
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          v-if="loading"
        ></v-progress-linear>
      </v-card>
    </v-dialog>
    <v-btn
      rounded
      x-small
      elevation="2"
      color="light-green"
      @click="show = true"
    >
      <v-icon left>
        mdi-check-bold
      </v-icon>
      Done
    </v-btn>
  </span>
</template>

<script>
import ShowHistoriData from "../../tabelDinamis/ShowHistoriData.vue";
export default {
  components: {
    ShowHistoriData
  },
  name: "TambahDataTabelPekerjaan",
  props: ["item", "petugases"],
  data() {
    return {
      baseUrl: "/api/v1/pekerjaan",
      baseUrlTabelDinamis: "/api/v1/tabelDinamis/",
      bpsApiUrl: "https://webapi.bps.go.id/v1/api/",
      show: false,
      tabel: null,
      info: "",
      deletedConfirm: false,
      loading: false,
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {},
  mounted() {
    this.loadJSLibrary(
      "https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"
    );
    this.loadCSSLibrary(
      "https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css"
    );
  },
  methods: {
    loadJSLibrary(url) {
      let scriptTag = document.createElement("script");
      scriptTag.setAttribute("src", url);
      document.head.appendChild(scriptTag);
    },
    loadCSSLibrary(url) {
      let styleTag = document.createElement("link");
      styleTag.setAttribute("rel", "stylesheet");
      styleTag.setAttribute("href", url);
      document.head.appendChild(styleTag);
    },
    kerjakan() {
      axios
        .post(this.baseUrl + "/do/tambahDataTabel/", {
          pekerjaan_id: this.item.id,
          tabel_id: this.item.pekerjaanable.tabel_dinamis_id,
          petugas_id: this.item.petugas_id
        })
        .then(res => {
          this.loading = false;
          this.show = false;
          this.$store.dispatch("pekerjaanStore/showSnackbar", {
            text: "Sukses Dikerjakan",
            type: "success"
          });
          this.$store.dispatch("pekerjaanStore/init");
        })
        .catch(err => {
          this.loading = false;
          this.show = false;
          this.$store.dispatch("pekerjaanStore/showSnackbar", {
            text: "Ups,Terdapat Kesalahan",
            type: "error"
          });
          console.log(err.response.data);
        });
    }
  }
};
</script>

<style></style>
