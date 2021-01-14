<template>
  <div>
    <v-snackbar
      v-model="snackbar.show"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
    >
      {{ snackbar.text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Tutup
        </v-btn>
      </template>
    </v-snackbar>
    <v-data-table
      :headers="headers"
      :items="desserts"
      sort-by="calories"
      class="elevation-1"
      :loading="table.loading"
      loading-text="Memuat... Mohon Tunggu"
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Daftar Publikasi</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-tooltip bottom>
            <template
              v-slot:activator="{ on: tooltip }"
              v-bind="attrs"
              v-on="on"
            >
              <v-btn small color="primary" class="mb-2" v-on="{ ...tooltip }">
                <v-icon @click="initialize">mdi-refresh</v-icon>
              </v-btn>
            </template>
            <span>Refresh Konten</span>
          </v-tooltip>
          <v-spacer></v-spacer>
          <v-dialog v-model="importDialog.dialog" max-width="500px">
            <template v-slot:activator="{ on: dialog }">
              <v-tooltip bottom>
                <template
                  v-slot:activator="{ on: tooltip }"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-btn
                    small
                    color="primary"
                    class="mb-2"
                    v-on="{ ...tooltip, ...dialog }"
                  >
                    <v-icon>mdi-book-plus-multiple</v-icon>
                  </v-btn>
                </template>
                <span>Import Publikasi dari Excel</span>
              </v-tooltip>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Import Publikasi</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col>
                      <v-file-input
                        accept=".xlsx"
                        label="File Import Excel"
                        v-model="importDialog.file"
                        outlined
                        dense
                        show-size
                        :loading="importDialog.loading"
                      ></v-file-input>
                    </v-col>
                  </v-row>
                  <v-alert
                    text
                    v-model="importDialog.errorStatus"
                    prominent
                    type="error"
                    icon="mdi-alert-remove"
                  >
                    {{ importDialog.errorText }}
                  </v-alert>
                  <a href="" target="_blank" rel="noopener noreferrer"
                    >File Template Import
                  </a>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="importDialog.dialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="importExcel">
                  Import
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-divider class="mx-4" inset vertical></v-divider>

          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on: dialog }">
              <v-tooltip bottom>
                <template
                  v-slot:activator="{ on: tooltip }"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-btn
                    small
                    color="primary"
                    class="mb-2"
                    v-on="{ ...tooltip, ...dialog }"
                  >
                    <v-icon>mdi-book-plus</v-icon>
                  </v-btn>
                </template>
                <span>Tambah Publikasi</span>
              </v-tooltip>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Import Publikasi</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-file-input
                        accept="image/*"
                        label="File input"
                        outlined
                        dense
                      ></v-file-input>
                      <a href="" target="_blank" rel="noopener noreferrer"
                        >File Template Import
                      </a>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="importExcel">
                  Import
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="headline"
                >Are you sure you want to delete this item?</v-card-title
              >
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDelete"
                  >Cancel</v-btn
                >
                <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                  >OK</v-btn
                >
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="viewItem(item)">
          mdi-eye
        </v-icon>
        <v-icon small class="mr-2" @click="editItem(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="deleteItem(item)">
          mdi-delete
        </v-icon>
      </template>
      <template class="text-left" v-slot:no-data>
        Ups, Tidak ada daftar publikasi
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  data: () => ({
    snackbar: {
      show: false,
      timeout: 3000,
      color: "success",
      text: ""
    },
    importDialog: {
      dialog: false,
      loading: false,
      errorStatus: false,
      errorText: "",
      file: null
    },
    table: {
      loading: false
    },
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        text: "Judul Publikasi",
        align: "start",
        sortable: false,
        value: "name"
      },
      { text: "Rilis", value: "rilis" },
      { text: "Status", value: "status" },
      { text: "", value: "actions", sortable: false }
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
      name: "",
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0
    },
    defaultItem: {
      name: "",
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0
    }
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Tambah Publikasi" : "Detail Publikasi";
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    }
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {},

    showSnackbar(text, type) {
      this.snackbar.color = type;
      this.snackbar.text = text;
      this.snackbar.show = true;
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    importExcel() {
      let formData = new FormData();
      formData.append("file", this.importDialog.file);
      this.importDialog.loading = true;
      axios
        .post("/api/v1/publikasi/import", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(() => {
          this.importDialog.loading = false;
          this.importDialog.dialog = false;
          this.showSnackbar("Sukses Import List", "success");
        })
        .catch(err => {
          this.importDialog.loading = false;
          this.importDialog.errorStatus = true;
          this.importDialog.errorText =
            "Ups terdapat kesalahan saat Import Publikasi, Pastikan Isian sudah sesuai Template";
        });
    }
  }
};
</script>

<style></style>
