<template>
  <div>
    <v-tooltip bottom>
      <template v-slot:activator="{ on: tooltip }">
        <v-btn
          small
          class="mx-1"
          color="primary"
          @click="showDialog()"
          :disabled="tabel.is_revisi == 1"
          v-on="{ ...tooltip }"
        >
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </template>
      Edit Detail
    </v-tooltip>

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
              <v-list-item v-if="userRole == 'ADMIN'">
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
              <v-list-item>
                <v-text-field
                  hide-details
                  dense
                  label="Unit/Satuan"
                  v-model="unit"
                  outlined
                >
                </v-text-field>
              </v-list-item>
              <v-list-item>
                <v-textarea
                  :rules="[rules.required]"
                  v-model="note"
                  dense
                  label="Keterangan"
                  hint="Ex: Sumber: Badan Pusat Statistik"
                  outlined
                  rows="3"
                ></v-textarea>
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
  </div>
</template>

<script>
export default {
  name: "EditTabel",
  props: ["subjects", "categories", "bidangs", "tabel", "tabelWeb", "userRole"],
  data() {
    return {
      show: false,
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  computed: {
    subjectsFiltered: {
      get: function() {
        return this.subjects.filter(obj => {
          return obj.subcat_id === this.tabel.category_id;
        });
      },

      set: val => {}
    },
    unit: {
      get: function() {
        return this.tabel.unit
          ? this.tabel.unit
          : this.tabelWeb.var
          ? this.tabelWeb.var[0].unit
          : "";
      },

      set: function(val) {
        this.tabel.unit = val;
      }
    },
    note: {
      get: function() {
        return this.tabel.note
          ? this.tabel.note
          : this.tabelWeb.var
          ? this.tabelWeb.var[0].note
          : "";
      },

      set: function(val) {
        this.tabel.note = val;
      }
    }
  },
  watch: {
    "tabel.category_id": function(val) {
      this.subjectsFiltered = this.subjects.filter(obj => {
        return obj.subcat_id === val;
      });
    }
  },
  methods: {
    showDialog() {
      this.show = true;
    },
    action() {
      if (this.$refs.formEdit.validate()) {
        this.$store.dispatch("tabelDinamisStore/editDetailTabel", {
          tabel: this.tabel,
          unit: this.unit,
          note: this.note
        });
        this.show = false;
      }
    }
  }
};
</script>

<style></style>
