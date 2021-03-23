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
              <v-icon>mdi-card-account-details</v-icon>
              Request Perubahan
            </v-tab>
            <v-tab-item class="mt-3 mb-2">
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
            <v-tab-item class="mt-3 mb-2">
              <v-row>
                <v-col md="6" sm="12" class="text-center">
                  Tampil Web
                  <v-icon>
                    mdi-web
                  </v-icon>
                </v-col>
                <v-col md="6" sm="12" class="text-center">
                  Perubahan
                  <v-icon>
                    mdi-pencil-box
                  </v-icon>
                </v-col>
              </v-row>
              <v-row>
                <v-col md="6" sm="12">
                  <v-textarea
                    label="Judul"
                    :disabled="!tabelWeb.label"
                    :readOnly="tabelWeb.label"
                    :value="tabelWeb.label"
                    hide-details
                    rows="3"
                    auto-grow
                    outlined
                  ></v-textarea>
                </v-col>
                <v-col md="6" sm="12">
                  <v-textarea
                    label="Judul"
                    disabled
                    outlined
                    :value="tabelApasi.judul_tabel"
                    hide-details
                    rows="3"
                    auto-grow
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row>
                <v-col md="6" sm="12">
                  <v-text-field
                    v-model="tabelWeb.subj"
                    :disabled="!tabelWeb.subj"
                    :readOnly="tabelWeb.subj"
                    hide-details
                    dense
                    label="Subjek"
                    outlined
                  ></v-text-field>
                </v-col>
                <v-col md="6" sm="12">
                  <v-select
                    item-value="sub_id"
                    item-text="title"
                    :items="subjects"
                    v-model="tabelApasi.subject_id"
                    hide-details
                    dense
                    disabled
                    label="Subjek"
                    outlined
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col md="6" sm="12">
                  <v-textarea
                    rows="3"
                    outlined
                    label="Note"
                    hide-details
                    :disabled="!tabelWeb.note"
                    :readOnly="tabelWeb.note"
                    v-model="tabelWeb.note"
                  ></v-textarea>
                </v-col>
                <v-col md="6" sm="12">
                  <v-textarea
                    disabled
                    outlined
                    rows="3"
                    label="Note"
                    hide-details
                    v-model="tabelApasi.note"
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row>
                <v-col md="6" sm="12">
                  <v-text-field
                    :disabled="!tabelWeb.unit"
                    :readOnly="tabelWeb.unit"
                    outlined
                    label="Unit"
                    hide-details
                    v-model="tabelWeb.unit"
                  ></v-text-field>
                </v-col>
                <v-col md="6" sm="12">
                  <v-text-field
                    disabled
                    outlined
                    label="Unit"
                    hide-details
                    v-model="tabelApasi.unit"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-textarea
                  disabled
                  label="Keterangan Perubahan"
                  outlined
                  hide-details
                  rows="2"
                  :value="item.keterangan"
                ></v-textarea>
              </v-row>
              <v-row>
                <v-col
                  v-if="tabelWeb.label"
                  class="red--text text-center font-weight-bold"
                >
                  Pastikan yang ada di Web(Kolom Kiri) sudah Sesuai
                  Permintaan(Kolom Kanan)
                </v-col>
              </v-row>
            </v-tab-item>
          </v-tabs>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="show = false">Batal</v-btn>
          <v-btn v-if="!isShow" color="green darken-1" text @click="cekWeb">
            Cek Web
          </v-btn>
          <v-btn v-else color="green darken-1" text @click="kerjakan">
            Selesaikan
          </v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
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
  components: {},
  name: "EditTabelPekerjaan",
  props: ["item", "petugases"],
  data() {
    return {
      baseUrl: "/api/v1/pekerjaan",
      baseUrlTabelDinamis: "/api/v1/tabelDinamis/",
      bpsApiUrl: "https://webapi.bps.go.id/v1/api/",
      show: false,
      loading: false,
      tabel: null,
      isShow: false,
      tabelApasi: {},
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {
    tabelWeb() {
      return this.$store.state.tabelDinamisStore.tabelDetail.tabelDataWeb
        .var[0];
    },
    subjects() {
      return this.$store.state.tabelDinamisStore.subjects;
    }
  },
  mounted() {
    this.$store.dispatch("tabelDinamisStore/stopWebLoading");
    this.$store.dispatch("tabelDinamisStore/setSubjects");
    this.getTabel(this.item.pekerjaanable.tabel_dinamis_id);
  },
  methods: {
    getTabel(id) {
      this.loading = true;
      axios
        .get(this.baseUrlTabelDinamis + id)
        .then(res => {
          this.tabelApasi = res.data[0];
          this.loading = false;
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
      axios
        .post(this.baseUrl + "/do/editTabel/", {
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
    },
    cekWeb() {
      this.$store.dispatch(
        "tabelDinamisStore/getTabelWeb",
        this.tabelApasi.tabel_web_id
      );
      this.isShow = true;
    }
  }
};
</script>

<style></style>
