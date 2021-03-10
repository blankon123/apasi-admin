<template>
  <span>
    <v-dialog v-model="kerjakanDialog.show" max-width="400px">
      <v-card>
        <v-card-title>
          <span>
            Konfirmasi Pekerjaan
            <v-btn x-small class="ma-1" color="blue" link href="">
              Publikasi
              <v-icon>
                mdi-arrow-right-circle
              </v-icon>
            </v-btn>
          </span>
        </v-card-title>
        <v-card-text>
          <div class="mb-1">
            {{ this.kerjakanDialog.form.nama }} telah diselesaikan oleh :
          </div>
          <v-form ref="formPetugas">
            <v-select
              prepend-inner-icon="mdi-account-hard-hat"
              v-model="kerjakanDialog.form.petugas_id"
              :items="petugases"
              item-text="nama_singkat"
              item-value="id"
              label="Petugas Pelaksana"
              outlined
              hide-details
              class="mb-3"
              :rules="[rules.required]"
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="kerjakanDialogInit"
            >Batal</v-btn
          >
          <v-btn color="blue darken-1" text @click="kerjakan">Lanjutkan</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          v-if="kerjakanDialog.loading"
        ></v-progress-linear>
      </v-card>
    </v-dialog>
    <v-btn
      rounded
      x-small
      elevation="2"
      color="light-green"
      @click="kerjakanDialogShow(item)"
    >
      <v-icon left>
        mdi-check-bold
      </v-icon>
      Selesaikan
    </v-btn>
  </span>
</template>

<script>
export default {
  name: "LayoutPekerjaan",
  props: ["item", "petugases"],
  data() {
    return {
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {
    kerjakanDialog() {
      return this.$store.state.pekerjaanStore.kerjakanDialog;
    }
  },
  methods: {
    kerjakanDialogInit() {
      this.$store.dispatch("pekerjaanStore/kerjakanDialogInit");
    },
    kerjakanDialogShow(item) {
      this.$store.dispatch("pekerjaanStore/kerjakanDialogShow", item);
    },
    kerjakan() {
      if (this.$refs.formPetugas.validate()) {
        this.kerjakanDialog.loading = true;
        this.$store.dispatch("pekerjaanStore/kerjakan");
      }
    }
  }
};
</script>

<style></style>
