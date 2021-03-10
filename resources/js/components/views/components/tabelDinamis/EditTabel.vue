<template>
  <span>
    <v-icon small class="mr-2" @click="showDialog()">
      mdi-pencil
    </v-icon>
    <v-dialog v-model="show" max-width="600px">
      <v-card>
        <v-card-title>
          <v-list-item-content>
            <v-list-item-title class="headline">
              Perubahan Tabel
            </v-list-item-title>
            <v-list-item-subtitle>
              Permintaan Perubahan Detail Publikasi
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form ref="formEdit">
            <v-list>
              <v-list-item>
                <v-textarea
                  label="Judul Tabel"
                  v-model="tabel.judul_tabel"
                  hint="Tanpa Tahun, Harus Mencantumkan Area (Misal 'Provinsi Kalimantan Tengah')"
                  outlined
                  :rules="[rules.required]"
                  rows="3"
                ></v-textarea>
              </v-list-item>
              <v-list-item>
                <v-select
                  item-value="subcat_id"
                  item-text="title"
                  :items="categories"
                  :rules="[rules.required]"
                  v-model="tabel.category_id"
                  hide-details
                  dense
                  label="Kategori"
                  outlined
                ></v-select>
              </v-list-item>
              <v-list-item>
                <v-select
                  item-value="sub_id"
                  item-text="title"
                  :items="subjectsFiltered"
                  v-model="tabel.subject_id"
                  :rules="[rules.required]"
                  hide-details
                  dense
                  label="Subjek"
                  outlined
                >
                  <template v-slot:no-data>
                    Silahkan Pilih Kategori Terlebih Dahulu
                  </template>
                </v-select>
              </v-list-item>
              <v-list-item>
                <v-select
                  item-value="id"
                  item-text="nama_bidang"
                  :items="bidangs"
                  :rules="[rules.required]"
                  v-model="tabel.user_id"
                  hide-details
                  dense
                  label="Bidang"
                  outlined
                ></v-select>
              </v-list-item>
            </v-list>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="show = false">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="action">
            Simpan
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </span>
</template>

<script>
export default {
  name: "EditTabel",
  props: ["subjects", "categories", "bidangs", "tabel"],
  data() {
    return {
      show: false,
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {
    subjectsFiltered() {
      return this.subjects.filter(obj => {
        return obj.subcat_id === this.tabel.category_id;
      });
    }
  },
  watch: {
    "tabel.category_id": function(val) {
      this.subjectsFiltered = this.subjects.filter(obj => {
        return obj.subcat_id === val;
      });
      this.tabel.subject_id = null;
    }
  },
  methods: {
    showDialog() {
      this.show = true;
    },
    action() {
      if (this.$refs.formEdit.validate()) {
        this.$store.dispatch("tabelDinamisStore/editTabel", this.tabel);
        this.show = false;
      }
    }
  }
};
</script>

<style></style>
