<template>
  <span>
    <v-dialog v-model="show" max-width="400px">
      <v-card>
        <v-card-title>
          <span>
            Konfirmasi Pekerjaan
            <v-btn
              x-small
              class="ma-1"
              color="blue"
              link
              target="_blank"
              :href="'/publikasi/' + item.pekerjaanable.publikasi_id"
            >
              Publikasi
              <v-icon>
                mdi-arrow-right-circle
              </v-icon>
            </v-btn>
          </span>
        </v-card-title>
        <v-card-text>
          <div class="mb-1">{{ item.nama }} telah diselesaikan :</div>
          <v-form ref="formPetugas">
            <v-file-input
              label="File Draft"
              v-model="file.draft"
              outlined
              prepend-icon="mdi-book"
              dense
              show-size
              :rules="[rules.required]"
            ></v-file-input>
            <v-file-input
              label="File Desain"
              v-model="file.desain"
              outlined
              prepend-icon="mdi-format-paint"
              dense
              show-size
              :rules="[rules.required]"
            ></v-file-input>
            <v-file-input
              label="File Rilis"
              v-model="file.rilis"
              outlined
              prepend-icon="mdi-book-check"
              dense
              show-size
              :rules="[rules.required]"
            ></v-file-input>
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
          <v-btn color="blue darken-1" text @click="kerjakan">Selesaikan</v-btn>
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
  name: "LayoutPekerjaan",
  props: ["item", "petugases"],
  data() {
    return {
      baseUrl: "/api/v1/pekerjaan",
      show: false,
      loading: false,
      file: {
        draft: null,
        desain: null,
        rilis: null
      },
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {},
  methods: {
    kerjakan() {
      if (this.$refs.formPetugas.validate()) {
        this.loading = true;
        let url =
          this.baseUrl +
          "/do/layout/" +
          this.item.id +
          "/" +
          this.item.petugas_id +
          "/" +
          this.item.pekerjaanable.publikasi_id;
        let formData = new FormData();
        formData.append("draft", this.file.draft);
        formData.append("desain", this.file.desain);
        formData.append("rilis", this.file.rilis);
        axios
          .post(url, formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
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
            state.show = false;
            this.$store.dispatch("pekerjaanStore/showSnackbar", {
              text: "Ups,Terdapat Kesalahan",
              type: "error"
            });
            console.log(err.response.data);
          });
      }
    }
  }
};
</script>

<style></style>
