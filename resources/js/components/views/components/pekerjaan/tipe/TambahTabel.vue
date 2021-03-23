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
              <v-icon>mdi-table-plus</v-icon>
              Detail Request Tabel
            </v-tab>
            <v-tab-item class="mt-3">
              <div class="mb-2 pb-2">
                {{ item.nama }} telah diselesaikan dan dipastikan tampil di
                Website :
              </div>
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
                ></v-select>
              </v-form>
            </v-tab-item>
            <v-tab-item class="mt-3">
              <v-row>
                <v-col>
                  <v-textarea
                    label="Judul"
                    readonly
                    outlined
                    :value="tabelApasi.judul_tabel"
                    hide-details
                    rows="3"
                    auto-grow
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row>
                <v-col md="6" lg="6" sm="6">
                  <v-select
                    item-value="sub_id"
                    item-text="title"
                    :items="subjects"
                    v-model="tabelApasi.subject_id"
                    hide-details
                    dense
                    readonly
                    label="Subjek"
                    outlined
                  ></v-select>
                </v-col>
                <v-col md="6" lg="6" sm="6">
                  <v-select
                    item-value="subcat_id"
                    item-text="title"
                    :items="categories"
                    v-model="tabelApasi.category_id"
                    hide-details
                    dense
                    readonly
                    label="Kategori"
                    outlined
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <v-textarea
                    readonly
                    outlined
                    rows="3"
                    label="Note"
                    hide-details
                    :value="tabelApasi.note"
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <v-text-field
                    disabled
                    outlined
                    label="Unit"
                    hide-details
                    :value="tabelApasi.unit"
                  ></v-text-field>
                </v-col>
                <v-col>
                  File Excel Tabel
                  <v-btn target="_blank" :href="item.pekerjaanable.data.excel">
                    <v-icon class="green--text">
                      mdi-file-excel
                    </v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-tab-item>
          </v-tabs>
          <p class="blue--text font-weight-bold text-center" v-if="confirmed">
            Pastikan di Website Sudah Muncul Tabelnya ya Fellas , Soalnya APASI
            belum bisa memastikan 😄
          </p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="show = false">Batal</v-btn>
          <v-btn color="green darken-1" text @click="kerjakan">
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
  name: "TambahTabelPekerjaan",
  props: ["item", "petugases"],
  data() {
    return {
      baseUrl: "/api/v1/pekerjaan",
      baseUrlTabelDinamis: "/api/v1/tabelDinamis/",
      bpsApiUrl: "https://webapi.bps.go.id/v1/api/",
      show: false,
      tabel: null,
      confirmed: false,
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {
    tabelApasi() {
      return this.$store.state.tabelDinamisStore.tabelDetail.tabel;
    },
    loading() {
      return this.$store.state.tabelDinamisStore.tabelDetail.webLoading;
    },
    subjects() {
      return this.$store.state.tabelDinamisStore.subjects;
    },
    categories() {
      return this.$store.state.tabelDinamisStore.categories;
    }
  },
  mounted() {
    this.$store.dispatch("tabelDinamisStore/stopWebLoading");
    this.$store.dispatch("tabelDinamisStore/setCategories");
    this.$store.dispatch("tabelDinamisStore/setSubjects");
    this.$store.dispatch(
      "tabelDinamisStore/getTabel",
      this.item.pekerjaanable.tabel_dinamis_id
    );
  },
  methods: {
    kerjakan() {
      if (!this.confirmed) {
        this.confirmed = true;
        return true;
      }
      axios
        .post(this.baseUrl + "/do/tambahTabel/", {
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
