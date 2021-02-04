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
    <v-dialog v-model="deleteDialog.show" max-width="500px">
      <v-card>
        <v-card-title>
          Konfirmasi Hapus
        </v-card-title>
        <v-card-text>
          Apakah anda Yakin akan Menghapus publikasi
          {{ deleteDialog.form.name }} Beserta seluruh File-nya dari aplikasi ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="deleteDialogInit"
            >Batal</v-btn
          >
          <v-btn color="blue darken-1" text @click="deleteDialogAction"
            >Hapus</v-btn
          >
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="importDialog.show" max-width="500px">
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
          <v-btn color="red darken-1" text @click="importDialog.show = false">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="importDialogAction">
            Import
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog.show" max-width="500px">
      <v-card>
        <v-card-title>
          <p class="headline mb-0">{{ publikasi.title }}</p>

          <p class="caption mt-0">
            {{ publikasi.subtitle }}
          </p>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-form ref="formPublikasi">
              <v-row class="mb-0">
                <v-col class="pa-0">
                  <v-textarea
                    label="Judul Publikasi"
                    prepend-icon="mdi-notebook"
                    :rules="[rules.required, rules.includeTahun]"
                    v-model="publikasi.judul_publikasi"
                    rows="1"
                    row-height="15"
                    auto-grow
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row class="my-0">
                <v-col md="4" class="pa-0">
                  <v-select
                    v-model="publikasi.arc"
                    :items="jenis_arcs"
                    :rules="[rules.required]"
                    item-text="jenis_arc"
                    item-value="id"
                    label="Jenis ARC"
                    prepend-icon="mdi-calendar-star"
                    mandatory
                  ></v-select>
                </v-col>
                <v-col md="8" class="pa-0">
                  <v-menu
                    ref="menu"
                    v-model="dateShow"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="showDatePicker"
                        label="Tanggal Perkiraan Terbit "
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="publikasi.tanggal_arc"
                      @input="dateShow = false"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row class="my-0">
                <v-col class="pa-0">
                  <v-select
                    v-model="publikasi.bidang"
                    :disabled="user.role != 'ADMIN'"
                    :items="bidangs"
                    :rules="[rules.required]"
                    item-text="nama_bidang"
                    item-value="id"
                    label="Bidang"
                    prepend-icon="mdi-account-group"
                    mandatory
                  ></v-select>
                </v-col>
              </v-row>
              <v-alert
                text
                v-model="dialog.errorStatus"
                prominent
                type="error"
                icon="mdi-alert-remove"
              >
                {{ dialog.errorText }}
              </v-alert>
            </v-form>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="dialogInit">
            Batal
          </v-btn>
          <v-btn
            color="blue darken-1"
            :loading="dialog.loading"
            text
            @click="dialogAction"
          >
            {{ publikasi.actionButton }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-data-table
      :headers="publikasiTable.headers"
      :items="publikasiTable.publikasiList"
      :itemsPerPage="publikasiTable.itemsPerPage"
      class="elevation-3"
      :loading="publikasiTable.loading"
      loading-text="Memuat... Mohon Tunggu"
      hide-default-footer
      disable-sort
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Daftar Publikasi</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-tooltip bottom>
            <template v-slot:activator="{ on: tooltip }">
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
            v-model="keySearchPublikasi"
            hide-details
            flat
            label="Cari publikasi disini..."
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-tooltip bottom v-if="user.role == 'ADMIN'">
            <template
              v-slot:activator="{ on: tooltip }"
              v-bind="attrs"
              v-on="on"
            >
              <v-btn
                small
                color="primary"
                class="mb-2"
                v-on="{ ...tooltip }"
                @click="importDialog.show = true"
              >
                <v-icon>mdi-book-plus-multiple</v-icon>
              </v-btn>
            </template>
            <span>Import Publikasi dari Excel</span>
          </v-tooltip>
          <v-divider class="mx-4" inset vertical></v-divider>
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
                v-on="{ ...tooltip }"
                @click="dialog.show = true"
              >
                <v-icon>mdi-book-plus</v-icon>
              </v-btn>
            </template>
            <span>Tambah Publikasi</span>
          </v-tooltip>
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-btn icon :href="viewItem(item)" target="_blank" class="mr-2">
          <v-icon small>
            mdi-eye
          </v-icon>
        </v-btn>
        <v-icon small class="mr-2" @click="editDialogShow(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="deleteDialogShow(item)">
          mdi-delete
        </v-icon>
      </template>
      <template class="text-left" v-slot:no-data>
        Ups, Tidak ada daftar publikasi
      </template>
      <template v-slot:[`item.judul`]="{ item }">
        <span>
          <div
            style="width: 7px;height:35px;float:left;margin:4px;border-radius:3px"
            :class="item.user.color"
          ></div>
          <div>
            <div :class="[`font-weight-bold `]">
              {{ item.judul_publikasi }}
            </div>
            <span :class="[`font-weight-black ${item.user.color}--text`]">
              {{ item.user.nama_bidang }}
            </span>
            Rilis : {{ dateForHuman(item.arc) }}
          </div>
        </span>
      </template>
      <template v-slot:[`item.batas_uploadHuman`]="{ item }">
        <div class="font-weight-medium">
          {{ dateForHuman(item.batas_upload) }}
        </div>
      </template>
    </v-data-table>
    <v-pagination
      v-model="currentPage"
      :length="publikasiTable.pageLength"
      total-visible="8"
    ></v-pagination>
  </div>
</template>

<script>
export default {
  data: () => ({
    jenis_arcs: [
      { jenis_arc: "ARC", id: 1 },
      { jenis_arc: "Non-ARC", id: 2 }
    ],
    currentPage: 1,
    keySearchPublikasi: "",
    dateShow: false,
    publikasi: {
      targetUrl: "publikasiStore/addPublikasi",
      actionButton: "Simpan",
      title: "Tambah Publikasi",
      subtitle: "Penambahan Publikasi BPS Provinsi Kalimantan Tengah",
      judul_publikasi: "",
      arc: "",
      tanggal_arc: "",
      bidang: "",
      id: ""
    },
    rules: {
      required: value => !!value || "Harus Terisi.",
      includeTahun: value =>
        value.toLocaleLowerCase().indexOf("20") != -1 ||
        "Harus Menyertakan Tahun"
    }
  }),

  computed: {
    publikasiTable() {
      return this.$store.state.publikasiStore.publikasiTable;
    },
    bidangs() {
      return this.$store.state.userStore.users;
    },
    snackbar() {
      return this.$store.state.publikasiStore.snackbar;
    },
    importDialog() {
      return this.$store.state.publikasiStore.importDialog;
    },
    dialog() {
      return this.$store.state.publikasiStore.dialog;
    },
    deleteDialog() {
      return this.$store.state.publikasiStore.deleteDialog;
    },
    user() {
      return this.$store.state.userStore.user;
    },
    showDatePicker() {
      return this.dateForHuman(this.publikasi.tanggal_arc);
    }
  },

  watch: {
    currentPage(val) {
      this.$store.dispatch("publikasiStore/setTableData", val);
    },
    user() {
      this.publikasi.bidang = this.user.id;
    }
  },

  created() {
    this.$store.dispatch("publikasiStore/setYearNull");
    this.$store.dispatch("publikasiStore/setTableData", 1);
    this.$store.dispatch("userStore/getBidang");
  },

  methods: {
    deleteDialogShow(item) {
      this.$store.dispatch("publikasiStore/deleteDialogShow", item);
    },

    deleteDialogInit() {
      this.$store.dispatch("publikasiStore/deleteDialogInit");
    },

    dialogInit() {
      this.$store.dispatch("publikasiStore/dialogInit");
      this.publikasi = {
        targetUrl: "publikasiStore/addPublikasi",
        actionButton: "Simpan",
        title: "Tambah Publikasi",
        subtitle: "Penambahan Publikasi BPS Provinsi Kalimantan Tengah",
        judul_publikasi: "",
        arc: 0,
        tanggal_arc: "",
        bidang: this.user.id
      };
    },

    deleteDialogAction() {
      this.$store.dispatch("publikasiStore/deletePublikasi");
    },

    editDialogShow(item) {
      this.dialog.show = true;
      this.publikasi = {
        targetUrl: "publikasiStore/editPublikasi",
        actionButton: "Ubah",
        title: "Perubahan Publikasi",
        subtitle: "Perubahan Publikasi BPS Provinsi Kalimantan Tengah",
        judul_publikasi: item.judul_publikasi,
        arc: item.jenis_arc,
        tanggal_arc: item.arc,
        id: item.id,
        bidang: item.user_id
      };
    },

    importDialogAction() {
      this.$store.dispatch("publikasiStore/importPublikasi");
    },

    searchPublikasi() {
      this.$store.dispatch("publikasiStore/setSearch", this.keySearchPublikasi);
      this.currentPage = 1;
    },

    refreshTable() {
      this.$store.dispatch("publikasiStore/refreshTable");
      this.currentPage = 1;
      this.keySearchPublikasi = "";
    },

    dateForHuman(arcDate) {
      let dateResult = moment(arcDate, "YYYY-MM-DD");
      return dateResult.isValid()
        ? dateResult.locale("id").calendar(null, {
            lastDay: "[Kemarin]",
            sameDay: "[Hari Ini,] D MMMM yyyy",
            nextDay: "[Besok,] dddd",
            lastWeek: "dddd [Kemarin]",
            nextWeek: "dddd, D MMMM yyyy",
            sameElse: "dddd, D MMMM yyyy"
          })
        : "-";
    },

    dialogAction() {
      if (this.$refs.formPublikasi.validate()) {
        this.$store.dispatch(this.publikasi.targetUrl, this.publikasi);
        this.dialogInit();
      }
    },
    viewItem(item) {
      return "/publikasi/" + item.id;
    }
  }
};
</script>

<style></style>
