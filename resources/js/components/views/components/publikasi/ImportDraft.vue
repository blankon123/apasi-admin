<template>
  <span>
    <v-btn
      :color="color"
      @click="showDialog = true"
      :disabled="!!parseInt(this.disable)"
    >
      <v-icon left>
        {{ icon }}
      </v-icon>
      {{ label }}
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
                <v-form ref="formDraft">
                  <v-file-input
                    label="File Draft"
                    v-model="draft.draft"
                    outlined
                    dense
                    show-size
                    :rules="[rules.required]"
                  ></v-file-input>
                  <v-file-input
                    label="File Desain"
                    v-model="draft.desain"
                    outlined
                    prepend-icon="mdi-format-paint"
                    dense
                    show-size
                    :rules="[rules.required]"
                  ></v-file-input>
                  <v-file-input
                    label="File Rilis"
                    v-model="draft.rilis"
                    outlined
                    prepend-icon="mdi-book-check"
                    dense
                    show-size
                    :rules="[rules.required]"
                  ></v-file-input>
                  <v-file-input
                    label="File Erata"
                    v-model="draft.erata"
                    outlined
                    prepend-icon="mdi-book-cog"
                    dense
                    show-size
                    v-if="parseInt(this.revisi)"
                    :rules="[rules.required]"
                  ></v-file-input>
                  <v-checkbox
                    v-model="setujuDraft"
                    :label="kalimatPersetujuan"
                    v-if="!parseInt(this.revisi)"
                  ></v-checkbox>
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
  name: "ImportDraft",
  props: ["publikasi", "user", "label", "color", "icon", "revisi", "disable"],
  data() {
    return {
      rules: {
        required: value => !!value || "Harus Terisi"
      },
      setujuDraft: true,
      showDialog: false,
      kalimatPersetujuan: `Dengan Mengunggah dokumen-dokumen terkait, ${this.user.name} menyatakan bahwa softcopy publikasi merupakan softcopy final dan dapat dirilis di website .`
    };
  },
  created() {
    if (!parseInt(this.revisi)) {
      this.setujuDraft = false;
    }
  },
  computed: {
    error() {
      return this.$store.state.publikasiViewStore.error;
    },
    draft() {
      return this.$store.state.publikasiViewStore.draft;
    },
    tanggal() {
      return moment()
        .locale("id")
        .format("dddd , D MMMM YYYY");
    }
  },
  methods: {
    action() {
      if (parseInt(this.revisi)) {
        this.$store.dispatch("publikasiViewStore/sendDraft", this.revisi);
        this.showDialog = false;
      } else if (this.$refs.formDraft.validate()) {
        this.$store.dispatch("publikasiViewStore/sendDraft", this.revisi);
        this.showDialog = false;
      }
    }
  }
};
</script>

<style></style>
