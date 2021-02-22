<template>
  <span>
    <v-btn color="teal">
      <v-icon left>
        mdi-flag-checkered
      </v-icon>
      Konfirmasi Upload
    </v-btn>

    <v-dialog v-model="showDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Unggah Draft Publikasi</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col>
                <v-form ref="confirmForm">
                  <v-select
                    :items="petugas"
                    label="Outlined style"
                    outlined
                  ></v-select>
                </v-form>
              </v-col>
            </v-row>
            <v-alert
              text
              v-model="error.status"
              prominent
              type="error"
              icon="mdi-alert-remove"
            >
              {{ error.text }}
            </v-alert>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="showDialog = false">
            Batal
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            :disabled="!setujuDraft"
            @click="action"
          >
            Unggah
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </span>
</template>

<script>
export default {
  name: "ConfirmUpload",
  props: ["publikasi"],
  data() {
    return {
      rules: {
        required: value => !!value || "Harus Terisi.",
        required_text: value => value != "-" || "Harus Terisi."
      },
      showDialog: false
    };
  },
  created() {
    this.$store.dispatch("petugasStore/getPetugasTable");
  },
  computed: {
    error() {
      return this.$store.state.publikasiViewStore.error;
    },
    petugas() {
      return this.$store.state.petugasStore.all;
    }
  }
};
</script>

<style></style>
