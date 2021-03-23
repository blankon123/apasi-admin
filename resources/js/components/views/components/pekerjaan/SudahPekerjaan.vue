<template>
  <div>
    <v-dialog v-model="ubahPetugasDialog.show" max-width="500px">
      <v-card>
        <v-card-title>
          Perubahan Petugas
        </v-card-title>
        <v-card-text>
          <div>Perubahan Petugas {{ ubahPetugasDialog.form.nama }}</div>
          <v-form ref="formPetugas" class="pt-2 mt-2">
            <v-select
              prepend-inner-icon="mdi-account-hard-hat"
              v-model="ubahPetugasDialog.petugas_id"
              :items="petugases"
              item-text="nama_singkat"
              item-value="id"
              label="Petugas Pelaksana"
              outlined
              dense
              hide-details
              class="mb-3"
              :rules="[rules.required]"
            >
            </v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="ubahPetugasDialogInit"
            >Batal</v-btn
          >
          <v-btn color="blue darken-1" text @click="ubahPetugas()">Ubah</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          v-if="ubahPetugasDialog.loading"
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
                  color="orange"
                  @click="ubahPetugasDialogShow(item)"
                >
                  <v-icon left>
                    mdi-account-hard-hat
                  </v-icon>
                  Ubah Petugas
                </v-btn>
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
import LayoutPekerjaan from "./tipe/LayoutPekerjaan.vue";
import RevisiPublikasiPekerjaan from "./tipe/RevisiPublikasiPekerjaan.vue";

export default {
  components: { LayoutPekerjaan, RevisiPublikasiPekerjaan },
  name: "SudahPekerjaan",
  props: ["pekerjaan", "loading", "title", "color", "icon"],
  data() {
    return {
      keywordPekerjaan: "",
      limitJumlah: true,
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  watch: {
    limitJumlah(val) {
      this.$store.dispatch("pekerjaanStore/filterSudahPekerjaan", {
        keyword: this.keywordPekerjaan,
        limit: val
      });
    }
  },
  computed: {
    ubahPetugasDialog() {
      return this.$store.state.pekerjaanStore.ubahPetugasDialog;
    },
    petugases() {
      return this.$store.state.petugasStore.all;
    }
  },
  methods: {
    findPetugas(petugas_id) {
      return this.petugases.filter(item => item.id == petugas_id)[0]
        ?.nama_singkat;
    },
    cariPekerjaan() {
      this.$store.dispatch("pekerjaanStore/filterSudahPekerjaan", {
        keyword: this.keywordPekerjaan,
        limit: this.limitJumlah
      });
    },
    ubahPetugasDialogInit() {
      this.$store.dispatch("pekerjaanStore/ubahPetugasDialogInit");
    },
    ubahPetugasDialogShow(item) {
      this.$store.dispatch("pekerjaanStore/ubahPetugasDialogShow", item);
    },
    ubahPetugas() {
      if (this.$refs.formPetugas.validate()) {
        this.$store.dispatch("pekerjaanStore/ubahPetugas");
      }
    }
  }
};
</script>

<style></style>
