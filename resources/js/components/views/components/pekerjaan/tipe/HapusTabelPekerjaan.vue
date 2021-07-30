<template>
  <span>
    <v-dialog v-model="show" max-width="400px">
      <v-card>
        <v-card-title>
          <span>
            Konfirmasi Pekerjaan
          </span>
        </v-card-title>
        <v-card-text>
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
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="show = false">Batal</v-btn>
          <v-btn
            v-if="!deletedConfirm"
            color="blue darken-1"
            text
            @click="cekWeb"
          >
            Cek Web
          </v-btn>
          <v-btn v-else color="green darken-1" text @click="kerjakan">
            Selesaikan
          </v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-divider></v-divider>
        <div>
          <p :class="infoColor + '--text text text-center'">
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
export default {
  name: "HapusTabelPekerjaan",
  props: ["item", "petugases"],
  data() {
    return {
      baseUrl: "/api/v1/pekerjaan",
      baseUrlTabelDinamis: "/api/v1/tabelDinamis/",
      bpsApiUrl: "https://webapi.bps.go.id/v1/api/",
      show: false,
      tabel: null,
      info: "",
      infoColor: "red",
      deletedConfirm: false,
      loading: false,
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {},
  methods: {
    cekWeb() {
      this.loading = true;
      axios
        .get(
          this.baseUrlTabelDinamis + this.item.pekerjaanable.tabel_dinamis_id
        )
        .then(res => {
          this.tabel = res.data[0];
          if (!this.tabel.tabel_web_id) {
            this.loading = false;
            this.deletedConfirm = true;
            this.infoColor = "green";
            this.info = "Tabel tidak ada di Web";
            return true;
          } else {
            this.komparasiWeb();
          }
        })
        .catch(err => {
          this.loading = false;
          this.$store.dispatch("pekerjaanStore/showSnackbar", {
            text: "Ups, Terjadi Kesalahan",
            type: "error"
          });
          console.log(err.message);
        });
    },
    komparasiWeb() {
      const bpsApiAxios = require("axios").create();
      bpsApiAxios
        .get(this.bpsApiUrl + "list/", {
          params: {
            model: "data",
            domain: process.env.MIX_WEBAPI_BPS_KODE,
            key: process.env.MIX_WEBAPI_BPS_KEY,
            var: this.tabel.tabel_web_id
          }
        })
        .then(res => {
          this.loading = false;
          if (res.data["data-availability"] == "available") {
            this.info = "Tabel masih ada di Web";
          } else {
            this.infoColor = "green";
            this.info = "Tabel tidak ada di Web";
            this.deletedConfirm = true;
          }
        })
        .catch(err => {
          this.loading = false;
          this.$store.dispatch("pekerjaanStore/showSnackbar", {
            text: "Ups, Terjadi Kesalahan",
            type: "error"
          });
          console.log(err.message);
        });
    },
    kerjakan() {
      console.log(this.item);
      console.log(this.tabel);
      axios
        .post(this.baseUrl + "/do/hapusTabel/", {
          pekerjaan_id: this.item.id,
          petugas_id: this.item.petugas_id,
          tabel_id: this.tabel.id
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
