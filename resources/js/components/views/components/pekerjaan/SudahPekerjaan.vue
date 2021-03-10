<template>
  <div>
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
    <v-dialog v-model="deleteDialog.show" max-width="500px">
      <v-card>
        <v-card-title>
          Konfirmasi Hapus
        </v-card-title>
        <v-card-text>
          Apakah anda Yakin akan Menghapus Pekerjaan
          {{ deleteDialog.form.nama }} ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="deleteDialogInit"
            >Batal</v-btn
          >
          <v-btn color="blue darken-1" text @click="hapus(deleteDialog.form.id)"
            >Hapus</v-btn
          >
          <v-spacer></v-spacer>
        </v-card-actions>
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          v-if="deleteDialog.loading"
        ></v-progress-linear>
      </v-card>
    </v-dialog>
    <v-card-title>
      {{ this.title }}
      <v-spacer></v-spacer>
      <v-icon :color="this.color">{{ this.icon }}</v-icon>
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
                  v-if="
                    nonDelete.some(f => {
                      return f !== item.tipe_pekerjaan;
                    })
                  "
                  rounded
                  x-small
                  elevation="2"
                  color="red"
                  @click="deleteDialogShow(item)"
                >
                  <v-icon left>
                    mdi-trash-can
                  </v-icon>
                  Hapus
                </v-btn>
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
              </div>
            </div>

            <v-card-subtitle
              v-text="item.nama"
              class="text-start py-0 my-0"
            ></v-card-subtitle>
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
export default {
  name: "SudahPekerjaan",
  props: ["pekerjaan", "loading", "title", "color", "icon"],
  data() {
    return {
      nonDelete: ["layout"],
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {
    deleteDialog() {
      return this.$store.state.pekerjaanStore.deleteDialog;
    },
    kerjakanDialog() {
      return this.$store.state.pekerjaanStore.kerjakanDialog;
    },
    petugases() {
      return this.$store.state.petugasStore.all;
    }
  },
  methods: {
    deleteDialogInit() {
      this.$store.dispatch("pekerjaanStore/deleteDialogInit");
    },
    kerjakanDialogInit() {
      this.$store.dispatch("pekerjaanStore/kerjakanDialogInit");
    },

    deleteDialogShow(item) {
      this.$store.dispatch("pekerjaanStore/deleteDialogShow", item);
    },
    kerjakanDialogShow(item) {
      this.$store.dispatch("pekerjaanStore/kerjakanDialogShow", item);
    },

    hapus(item) {
      this.deleteDialog.loading = true;
      this.$store.dispatch("pekerjaanStore/hapus", item);
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
