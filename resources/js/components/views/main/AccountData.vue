<template>
  <div>
    <v-dialog v-model="petugasDialog.show" persistent max-width="400px">
      <v-card>
        <v-card-title>
          <p class="headline mb-0">{{ petugasDialog.title }}</p>
          <p class="subtitle-2 my-0">{{ petugasDialog.subtitle }}</p>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="formPetugas">
              <v-row>
                <v-text-field
                  v-model="petugasDialog.form.nama"
                  label="Nama Panjang*"
                  :rules="[rules.required]"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-text-field
                  v-model="petugasDialog.form.nama_singkat"
                  label="Nama Singkat*"
                  :rules="[rules.required]"
                ></v-text-field>
              </v-row>
            </v-form>
          </v-container>
          <small>*Harus Diisi</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="petugasDialogInit">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="petugasDialogAction">
            {{ petugasDialog.actionTitle }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteDialog.show" persistent max-width="400">
      <v-card>
        <v-card-title class="headline">
          Yakin Menghapus?
        </v-card-title>
        <v-card-text>
          Apakah anda yakin menghapus {{ deleteDialog.type }}
          {{ deleteDialog.name }} ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="deleteDialogInit">
            Batal
          </v-btn>
          <v-btn color="green darken-1" text @click="deleteDialogAction">
            Yakin
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-row dense>
      <v-col lg="6" sm="12" xs="12">
        <v-card elevation="2">
          <v-row class="ma-0 pa-0" align="center" justify="center">
            <v-col cols="10">
              <v-card-title class="justify-start text-left">
                Daftar User
              </v-card-title>
              <v-card-subtitle class="justify-start text-left">
                User yaitu Bidang/Sub-Bagian
              </v-card-subtitle>
            </v-col>
            <v-col cols="2" class="justify-center text-center">
              <v-tooltip bottom>
                <template
                  v-slot:activator="{ on: tooltip }"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-btn
                    color="primary"
                    elevation="8"
                    small
                    v-on="{ ...tooltip }"
                    @click.stop="userDialogAddShow"
                  >
                    <v-icon> mdi-account-multiple-plus</v-icon>
                  </v-btn>
                </template>
                <span>Tambah User</span>
              </v-tooltip>
            </v-col>
          </v-row>

          <v-data-table
            :headers="users.header"
            :items="users.items"
            item-key="name"
            class="elevation-1"
            :search="search.user"
            :custom-filter="filterTextUser"
            :loading="users.loading"
          >
            <template v-slot:top>
              <v-text-field
                v-model="search.user"
                label="Cari disini..."
                class="mx-4"
              ></v-text-field>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon small class="mr-2" @click="editUser(item)">
                mdi-pencil
              </v-icon>
              <v-icon small class="mr-2" @click="deleteUser(item)">
                mdi-delete
              </v-icon>
              <v-icon small @click="resetPasswordUser(item)">
                mdi-lock-reset
              </v-icon>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
      <v-col lg="6" sm="12" xs="12">
        <v-card elevation="2">
          <v-row class="ma-0 pa-0" align="center" justify="center">
            <v-col cols="10">
              <v-card-title class="justify-start text-left">
                Daftar Petugas
              </v-card-title>
              <v-card-subtitle class="justify-start text-left">
                Petugas Layout atau Upload ke Portal Publikasi
              </v-card-subtitle>
            </v-col>
            <v-col cols="2" class="justify-center text-center">
              <v-tooltip bottom>
                <template
                  v-slot:activator="{ on: tooltip }"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-btn
                    color="primary"
                    elevation="8"
                    small
                    v-on="{ ...tooltip }"
                    @click.stop="petugasDialogAddShow"
                  >
                    <v-icon> mdi-account-plus</v-icon>
                  </v-btn>
                </template>
                <span>Tambah Petugas</span>
              </v-tooltip>
            </v-col>
          </v-row>

          <v-data-table
            :headers="petugasTable.headers"
            :items="petugases"
            class="elevation-2"
            :search="search.petugas"
            :custom-filter="filterTextPetugas"
            :loading="petugasTable.loading"
          >
            <template v-slot:top>
              <v-text-field
                v-model="search.petugas"
                label="Cari disini..."
                class="mx-4"
              ></v-text-field>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon small class="mr-2" @click="petugasDialogEditShow(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="petugasDialogDeleteShow(item)">
                mdi-delete
              </v-icon>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      petugasDialog: {
        title: "",
        subtitle: "",
        show: false,
        form: {
          nama: "",
          nama_singkat: ""
        },
        targetUrl: "",
        actionTitle: ""
      },
      deleteDialog: {
        type: "",
        show: false,
        name: "",
        targetUrl: "",
        itemId: null
      },
      search: {
        petugas: "",
        user: ""
      },
      rules: {
        required: value => !!value || "Harus Terisi."
      }
    };
  },
  created() {
    this.$store.dispatch("userStore/getUserTable");
    this.$store.dispatch("petugasStore/getPetugasTable");
  },
  computed: {
    users() {
      return this.$store.state.userStore.userTable;
    },
    petugases() {
      return this.$store.state.petugasStore.all;
    },
    petugasTable() {
      return this.$store.state.petugasStore.petugasTable;
    }
  },
  methods: {
    filterTextPetugas(value, search, item) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value
          .toString()
          .toLocaleLowerCase()
          .indexOf(search) !== -1
      );
    },
    filterTextUser(value, search, item) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value
          .toString()
          .toLocaleLowerCase()
          .indexOf(search) !== -1
      );
    },
    petugasDialogInit() {
      let petugasDialogDefault = {
        title: "",
        subtitle: "",
        show: false,
        form: {
          nama: "",
          nama_singkat: ""
        },
        targetUrl: ""
      };
      this.petugasDialog = petugasDialogDefault;
    },
    petugasDialogAddShow() {
      let petugasDialogAdd = {
        title: "Tambah Petugas",
        subtitle: "Penambahan Petugas Layout dan Upload",
        show: true,
        form: {
          nama: "",
          nama_singkat: ""
        },
        targetUrl: "petugasStore/addPetugas",
        actionTitle: "Tambah"
      };
      this.petugasDialog = petugasDialogAdd;
    },
    petugasDialogEditShow(item) {
      this.petugasDialog = {
        title: "Edit Petugas",
        subtitle: "Perubahan Nama dan/atau Nama Panjang Petugas",
        show: true,
        form: {
          nama: item.nama,
          nama_singkat: item.nama_singkat
        },
        targetUrl: "petugasStore/editPetugas",
        actionTitle: "Edit"
      };
    },
    petugasDialogAction() {
      if (this.$refs.formPetugas.validate()) {
        this.$store.dispatch(
          this.petugasDialog.targetUrl,
          this.petugasDialog.form
        );
        this.petugasDialogInit();
      }
    },
    petugasDialogDeleteShow(item) {
      this.deleteDialog = {
        type: "Petugas",
        show: true,
        name: item.nama,
        targetUrl: "petugasStore/deletePetugas",
        itemId: item.id
      };
    },
    deleteDialogInit() {
      this.deleteDialog = {
        type: "",
        show: false,
        name: "",
        targetUrl: "",
        itemId: null
      };
    },
    deleteDialogAction() {
      this.$store.dispatch(
        this.deleteDialog.targetUrl,
        this.deleteDialog.itemId
      );
      this.deleteDialogInit();
    }
  }
};
</script>

<style></style>
