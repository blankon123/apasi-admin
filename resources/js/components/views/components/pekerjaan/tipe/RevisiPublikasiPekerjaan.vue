<template>
  <span>
    <v-dialog v-model="kerjakanDialog.show" max-width="400px">
      <v-card>
        <v-card-title>
          Konfirmasi Pekerjaan
        </v-card-title>
        <v-card-text>
          <div class="mb-1">
            {{ this.kerjakanDialog.form.nama }}
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
      color="indigo"
      @click="kerjakanDialogShow(item)"
    >
      <v-icon left>
        mdi-hammer
      </v-icon>
      Kerjakan
    </v-btn>
  </span>
</template>

<script>
export default {
  name: "LayoutPublikasiPekerjaan",
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
