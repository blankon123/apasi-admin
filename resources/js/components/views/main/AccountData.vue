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
    <v-dialog v-model="userDialog.show" persistent max-width="400px">
      <v-card>
        <v-card-title>
          <p class="headline mb-0">{{ userDialog.title }}</p>
          <br />
          <p class="subtitle-2 my-0">{{ userDialog.subtitle }}</p>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="formUser">
              <v-row>
                <v-text-field
                  v-model="userDialog.form.username"
                  label="akun*"
                  placeholder="Misal: ipds6200"
                  :rules="[rules.required]"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-text-field
                  v-model="userDialog.form.name"
                  label="Nama Bidang*"
                  placeholder="Misal: Bidang Integrasi Pengolahan..."
                  :rules="[rules.required]"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-text-field
                  v-model="userDialog.form.nama_bidang"
                  label="Singkatan Bidang*"
                  placeholder="Misal: IPDS"
                  :rules="[rules.required]"
                ></v-text-field>
              </v-row>
              <v-row v-if="userDialog.showPassword">
                <v-text-field
                  v-model="userDialog.form.password"
                  label="Password*"
                  type="password"
                  :rules="[rules.required, rules.minimal]"
                ></v-text-field>
              </v-row>
              <v-row v-if="userDialog.confirmPassword.show">
                <v-text-field
                  v-model="userDialog.confirmPassword.confirmPass"
                  label="Konfirmasi Password*"
                  type="password"
                  :rules="[
                    rules.required,
                    rules.minimal,
                    rules.confirmPassword
                  ]"
                ></v-text-field>
              </v-row>
            </v-form>
          </v-container>
          <small>*Harus Diisi</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="userDialogInit">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="userDialogAction">
            {{ userDialog.actionTitle }}
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
    <v-dialog v-model="resetPasswordDialog.show" persistent max-width="400">
      <v-card>
        <v-card-title>
          <p class="headline mb-0">Reset Password</p>
          <br />
          <p class="subtitle-2 my-0">
            Ganti Password User {{ resetPasswordDialog.form.name }}
          </p>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="formPassword">
              <v-row>
                <v-text-field
                  v-model="resetPasswordDialog.form.password"
                  label="Password*"
                  type="password"
                  :rules="[rules.required, rules.minimal]"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-text-field
                  v-model="resetPasswordDialog.form.confirmPass"
                  label="Konfirmasi Password*"
                  type="password"
                  :rules="[
                    rules.required,
                    rules.minimal,
                    rules.confirmPasswordRe
                  ]"
                ></v-text-field>
              </v-row>
            </v-form>
          </v-container>
          <small>*Harus Diisi</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="resetPasswordDialogInit">
            Batal
          </v-btn>
          <v-btn color="blue darken-1" text @click="resetPasswordDialogAction">
            Reset
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
              <v-icon small class="mr-2" @click="userDialogEditShow(item)">
                mdi-pencil
              </v-icon>
              <v-icon small class="mr-2" @click="userDialogDeleteShow(item)">
                mdi-delete
              </v-icon>
              <v-icon small @click="resetPasswordDialogShow(item)">
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
          id: "",
          nama: "",
          nama_singkat: ""
        },
        targetUrl: "",
        actionTitle: ""
      },
      userDialog: {
        title: "",
        subtitle: "",
        show: false,
        showPassword: false,
        form: {
          id: null,
          username: "",
          nama_bidang: "",
          name: "",
          password: ""
        },
        confirmPassword: {
          show: true,
          confirmPass: ""
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
      resetPasswordDialog: {
        show: false,
        form: {
          password: "",
          name: "",
          confirmPass: "",
          id: null
        },
        targetUrl: "userStore/resetUserPassword"
      },
      search: {
        petugas: "",
        user: ""
      },
      rules: {
        required: value => !!value || "Harus Terisi.",
        minimal: value => value.length >= 6 || "Min 6 Karakter",
        confirmPassword: value =>
          value === this.userDialog.form.password || "Password tidak sama",
        confirmPasswordRe: value =>
          value === this.resetPasswordDialog.form.password ||
          "Password tidak sama"
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
    resetPasswordDialogInit() {
      this.resetPasswordDialog = {
        show: false,
        form: {
          password: "",
          name: "",
          confirmPass: "",
          id: null
        }
      };
    },
    resetPasswordDialogShow(item) {
      this.resetPasswordDialog = {
        show: true,
        form: {
          password: "",
          name: item.name,
          confirmPass: "",
          id: item.id
        },
        targetUrl: "userStore/resetUserPassword"
      };
    },
    resetPasswordDialogAction() {
      if (this.$refs.formPassword.validate()) {
        this.$store.dispatch(
          this.resetPasswordDialog.targetUrl,
          this.resetPasswordDialog.form
        );
        this.resetPasswordDialogInit();
      }
    },
    petugasDialogInit() {
      this.petugasDialog = {
        title: "",
        subtitle: "",
        show: false,
        form: {
          id: "",
          nama: "",
          nama_singkat: ""
        },
        targetUrl: "userStore/resetUserPassword"
      };
    },
    userDialogInit() {
      this.userDialog = {
        title: "",
        subtitle: "",
        show: false,
        showPassword: false,
        form: {
          id: null,
          username: "",
          nama_bidang: "",
          name: "",
          password: ""
        },
        confirmPassword: {
          show: false,
          confirmPass: ""
        },
        targetUrl: "",
        actionTitle: ""
      };
    },
    userDialogAddShow() {
      this.userDialog = {
        title: "Tambah User",
        subtitle: "Penambahan User atau Bidang Terkait",
        show: true,
        showPassword: true,
        form: {
          id: null,
          username: "",
          nama_bidang: "",
          name: "",
          password: ""
        },
        confirmPassword: {
          show: true,
          confirmPass: ""
        },
        targetUrl: "userStore/addUser",
        actionTitle: "Tambah"
      };
    },
    petugasDialogAddShow() {
      this.petugasDialog = {
        title: "Tambah Petugas",
        subtitle: "Penambahan Petugas Layout dan Upload",
        show: true,
        showPassword: true,
        form: {
          id: "",
          nama: "",
          nama_singkat: ""
        },
        targetUrl: "petugasStore/addPetugas",
        actionTitle: "Tambah",
        confirmPassword: {
          show: false,
          value: ""
        }
      };
    },
    userDialogEditShow(item) {
      this.userDialog = {
        title: "Edit User",
        subtitle: "Perubahan User atau Bidang Terkait",
        show: true,
        showPassword: false,
        form: {
          id: item.id,
          username: item.username,
          nama_bidang: item.nama_bidang,
          name: item.name,
          password: item.password
        },
        confirmPassword: {
          show: false,
          confirmPass: ""
        },
        targetUrl: "userStore/editUser",
        actionTitle: "Edit"
      };
    },
    petugasDialogEditShow(item) {
      this.petugasDialog = {
        title: "Edit Petugas",
        subtitle: "Perubahan deskripsi Petugas Layout dan Upload",
        show: true,
        form: {
          id: item.id,
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
    userDialogAction() {
      if (this.$refs.formUser.validate()) {
        this.$store.dispatch(this.userDialog.targetUrl, this.userDialog.form);
        this.userDialogInit();
      }
    },
    petugasDialogDeleteShow(item) {
      this.deleteDialog = {
        type: "Petugas",
        show: true,
        name: item.nama,
        targetUrl: "petugasStore/deletePetugas",
        form: {
          id: item.id,
          nama: item.nama,
          nama_singkat: item.nama_singkat
        }
      };
    },
    userDialogDeleteShow(item) {
      this.deleteDialog = {
        type: "User",
        show: true,
        name: item.name,
        targetUrl: "userStore/deleteUser",
        form: {
          id: item.id,
          username: item.username,
          nama_bidang: item.nama_bidang,
          name: item.name,
          password: item.password
        }
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
      this.$store.dispatch(this.deleteDialog.targetUrl, this.deleteDialog.form);
      this.deleteDialogInit();
    }
  }
};
</script>

<style></style>
