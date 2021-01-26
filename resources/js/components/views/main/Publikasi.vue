<template>
  <div>
    <v-snackbar
      v-model="snackbar.show"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
    >
      {{ snackbar.text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Tutup
        </v-btn>
      </template>
    </v-snackbar>
    <v-data-table
      :headers="table.headers"
      :items="table.publikasiList"
      :itemsPerPage="table.itemsPerPage"
      class="elevation-3"
      :loading="table.loading"
      loading-text="Memuat... Mohon Tunggu"
      hide-default-footer
      disable-sort
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
                <v-icon @click="refreshTable">mdi-refresh</v-icon>
              </v-btn>
            </template>
            <span>Refresh Konten</span>
          </v-tooltip>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-text-field
            clearable
            @keyup.enter="searchPublikasi"
            v-model="table.searchKey"
            hide-details
            flat
            label="Cari publikasi disini..."
          ></v-text-field>
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

          <v-dialog v-model="addDialog.dialog" max-width="500px">
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
                <span class="headline">Tambah Publikasi</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col>
                      <v-file-input
                        accept=".xlsx"
                        label="File Import Excel"
                        v-model="addDialog.file"
                        outlined
                        dense
                        show-size
                        :loading="addDialog.loading"
                      ></v-file-input>
                    </v-col>
                  </v-row>
                  <v-alert
                    text
                    v-model="addDialog.errorStatus"
                    prominent
                    type="error"
                    icon="mdi-alert-remove"
                  >
                    {{ addDialog.errorText }}
                  </v-alert>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="addDialog.dialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="addPublikasi">
                  Simpan
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="deleteDialog" max-width="500px">
            <v-card>
              <v-card-title>
                Konfirmasi Hapus
              </v-card-title>
              <v-card-text>
                Apakah anda Yakin akan Menghapus publikasi Beserta seluruh
                File-nya dari aplikasi ?
              </v-card-text>
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
      <template v-slot:[`item.actions`]="{ item }">
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
      <template v-slot:[`item.judul`]="{ item }">
        <div :class="[`font-weight-bold `]">
          {{ item.judul_publikasi }}
        </div>
        <span :class="[`font-weight-black ${item.user.color}--text`]">
          {{ item.user.nama_bidang }}
        </span>
        Rilis : {{ dateForHuman(item.arc) }}
      </template>
      <template v-slot:[`item.batas_uploadHuman`]="{ item }">
        <div class="font-weight-medium">
          {{ dateForHuman(item.batas_upload) }}
        </div>
      </template>
    </v-data-table>
    <v-pagination
      v-model="currentPage"
      :length="table.pageLength"
      total-visible="8"
    ></v-pagination>
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
    addDialog: {
      dialog: false,
      loading: false,
      errorStatus: false,
      errorText: ""
    },
    currentPage: 1,
    dialog: false,
    deleteDialog: false,
    editedIndex: -1,
    keySearchPublikasi: "",
    editedItem: {
      judul_publikasi: "",
      jenis_judul_publikasi: "",
      arc: 0,
      tanggal_arc: "",
      bidang: 0,
      tanggal_upload: 0,
      arc: 0,
      tanggal_arc: "",
      bidang: 0,
      tanggal_upload: 0
    },
    defaultItem: {
      judul_publikasi: "",
      jenis_arc: 0,
      tanggal_arc: "",
      bidang: 0,
      tanggal_upload: 0
    }
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Tambah Publikasi" : "Detail Publikasi";
    },

    table() {
      return this.$store.state.publikasiStore.table;
    },

    user() {
      return this.$store.state.userStore.user;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
    currentPage(val) {
      this.initialize(val);
    }
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize(requestedPage = 1) {
      this.$store.dispatch("publikasiStore/setLoading", true);
      axios
        .get(this.table.baseUrl, {
          params: {
            page: requestedPage,
            total: this.table.itemsPerPage
          }
        })
        .then(res => {
          this.$store.dispatch("publikasiStore/setTableData", res);
        })
        .catch(err => console.log(err));
    },

    showSnackbar(text, type) {
      this.snackbar.show = true;
      this.snackbar.color = type;
      this.snackbar.text = text;
    },

    editItem(item) {
      this.editedIndex = this.table.publikasiList.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.table.publikasiList.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.deleteDialog = true;
    },

    viewItem(item) {
      this.editedIndex = this.publikasiList.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      axios
        .delete("/api/v1/publikasi/", {
          data: { id: this.editedItem.id }
        })
        .then(res => {
          this.initialize();
          this.closeDelete();
          this.showSnackbar("Sukses Hapus Publikasi", "success");
        })
        .catch(err => {
          this.showSnackbar(err.response.data, "error");
        });
    },

    closeAdd() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.deleteDialog = false;
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
          this.initialize();
        })
        .catch(err => {
          this.importDialog.loading = false;
          this.importDialog.errorStatus = true;
          this.importDialog.errorText = err.response.data;
        });
    },

    dateForHuman(arcDate) {
      return moment(arcDate, "YYYY-MM-DD")
        .locale("id")
        .calendar(null, {
          lastDay: "[Kemarin]",
          sameDay: "[Hari Ini,] D MMMM yyyy",
          nextDay: "[Besok,] dddd",
          lastWeek: "dddd [Kemarin]",
          nextWeek: "dddd, D MMMM yyyy",
          sameElse: "dddd, D MMMM yyyy"
        });
    },

    searchPublikasi() {
      this.$store.dispatch(
        "publikasiStore/setSearch",
        "/api/v1/publikasi/search?key=" + this.keySearchPublikasi
      );
      this.initialize();
    },

    refreshTable() {
      this.$store.dispatch("publikasiStore/refreshTable");
      this.keySearchPublikasi = "";
      this.initialize();
      this.currentPage = 1;
    },

    addPublikasi() {}
  }
};
</script>

<style></style>
