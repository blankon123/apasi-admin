<template>
  <div>
    <v-btn color="blue darken-2" @click="showDialog = !showDialog">
      <v-icon left>
        mdi-pencil
      </v-icon>
      ISI SPRP
    </v-btn>
    <v-dialog v-model="showDialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-list-item-content>
            <v-list-item-title class="headline">
              Detail Publikasi
            </v-list-item-title>
            <v-list-item-subtitle>
              Pengisian Detail Rancangan Publikasi
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-card-title>
        <v-card-text v-if="publikasi.judul_publikasi && true" class="text-left">
          <v-form ref="formSPRP">
            <v-row>
              <v-col cols="12">
                <v-text-field
                  :value="publikasi.judul_publikasi"
                  label="Judul Publikasi"
                  outlined
                  disabled
                  hide-details
                  prepend-inner-icon="mdi-book"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="6" class="py-0 my-0">
                <v-select
                  prepend-inner-icon="mdi-stretch-to-page"
                  v-model="publikasi.ukuran"
                  :items="ukuran"
                  item-text="show"
                  item-value="kode"
                  label="Ukuran"
                  outlined
                  hide-details
                  class="mb-3"
                  :rules="[rules.required]"
                ></v-select>
                <v-select
                  prepend-inner-icon="mdi-text-to-speech"
                  v-model="publikasi.bahasa"
                  :items="bahasa"
                  item-text="show"
                  item-value="kode"
                  label="Bahasa"
                  outlined
                  hide-details
                  class="mb-3"
                  :rules="[rules.required]"
                ></v-select>
                <v-select
                  prepend-inner-icon="mdi-crop-rotate"
                  v-model="publikasi.orientasi"
                  :items="orientasi"
                  item-text="show"
                  item-value="kode"
                  label="Orientasi"
                  outlined
                  class="mb-3"
                  hide-details
                  :rules="[rules.required]"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="12" md="6" class="py-0 my-0">
                <v-select
                  prepend-inner-icon="mdi-account-supervisor"
                  v-model="publikasi.diterbitkan_untuk"
                  :items="diterbitkan_untuk"
                  item-text="show"
                  item-value="kode"
                  label="Peruntukan Rilis"
                  outlined
                  class="mb-3"
                  hide-details
                  :rules="[rules.required]"
                ></v-select>
                <v-text-field
                  v-model="publikasi.numbering"
                  :value="
                    publikasi.numbering == null ? '-' : publikasi.numbering
                  "
                  label="ISSN/ISBN"
                  outlined
                  prepend-inner-icon="mdi-barcode"
                  class="mb-3"
                  hide-details
                  :rules="[rules.required, rules.required_text]"
                ></v-text-field>
                <v-select
                  prepend-inner-icon="mdi-format-paint"
                  v-model="publikasi.cover_oleh"
                  :items="cover_oleh"
                  item-text="show"
                  item-value="kode"
                  label="Pembuat Cover"
                  hide-details
                  outlined
                  class="mb-3"
                  :rules="[rules.required]"
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  v-model="publikasi.abstraksi"
                  outlined
                  hide-details
                  auto-grow
                  label="Abstraksi"
                  :rules="[rules.required, rules.required_text]"
                  :value="
                    publikasi.abstraksi == null ? '-' : publikasi.abstraksi
                  "
                ></v-textarea>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="showDialog = false">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="action">
            Simpan
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "IsiSPRP",
  props: ["publikasi"],
  data() {
    return {
      rules: {
        required: value => !!value || "Harus Terisi.",
        required_text: value => value != "-" || "Harus Terisi."
      },
      showDialog: true,
      ukuran: [
        { kode: "1", show: "A5" },
        { kode: "2", show: "A4" },
        { kode: "3", show: "B5 JIS" },
        { kode: "4", show: "B5 ISO" },
        { kode: "5", show: "Lainnya" }
      ],
      bahasa: [
        { kode: "1", show: "Indonesia" },
        { kode: "2", show: "Inggris" },
        { kode: "3", show: "Indonesia & Inggris" }
      ],
      orientasi: [
        { kode: "1", show: "Portrait" },
        { kode: "2", show: "Landscape" }
      ],
      diterbitkan_untuk: [
        { kode: "1", show: "Eksternal" },
        { kode: "2", show: "Internal" }
      ],
      cover_oleh: [
        { kode: "1", show: "IPDS" },
        { kode: "2", show: "SM" }
      ]
    };
  },
  methods: {
    action() {
      if (this.$refs.formSPRP.validate()) {
        this.$store.dispatch("publikasiViewStore/sendSPRP", this.publikasi);
        this.showDialog = false;
      }
    },
    dateForHuman(arcDate) {
      let dateResult = moment(arcDate);
      return dateResult.isValid()
        ? dateResult.locale("id").calendar(null, {
            lastDay: "[Kemarin, ] D MMMM yyyy",
            sameDay: "[Hari Ini,] D MMMM yyyy",
            nextDay: "[Besok,] dddd",
            lastWeek: "dddd [Kemarin]",
            nextWeek: "dddd, D MMMM yyyy",
            sameElse: "dddd, D MMMM yyyy"
          })
        : "-";
    }
  }
};
</script>

<style></style>
