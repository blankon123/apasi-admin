<template>
  <div>
    <v-tooltip bottom>
      <template v-slot:activator="{ on: tooltip }">
        <v-btn
          small
          class="mx-1"
          color="primary"
          @click="simpanTabelShow"
          v-on="{ ...tooltip }"
          :disabled="disabled"
        >
          <v-icon>mdi-content-save</v-icon>
        </v-btn>
      </template>
      <span>Simpan Tabel</span>
    </v-tooltip>
    <v-dialog v-model="show" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Konfirmasi Perubahan</span>
        </v-card-title>

        <v-card-text>
          <v-form ref="keteranganForm">
            <v-textarea
              label="Keterangan Perubahan"
              v-model="komentar"
              hint="Misal: Perubahan pada tahun x, Penambahan data pada tahun y"
              outlined
              :rules="[rules.required]"
              rows="4"
            >
            </v-textarea>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="show = false">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="simpanTabelAction">
            Simpan
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["disabled"],
  data() {
    return {
      rules: {
        required: value => !!value || "Harus Terisi."
      },
      komentar: "",
      show: false
    };
  },
  watch: {
    targetTabel() {
      console.log(this.targetTabel);
    }
  },
  methods: {
    simpanTabelShow() {
      this.show = true;
    },
    simpanTabelAction() {
      if (this.$refs.keteranganForm.validate()) {
        this.$emit("saveApasiTabel", this.komentar);
        this.show = false;
      }
    }
  }
};
</script>

<style></style>
