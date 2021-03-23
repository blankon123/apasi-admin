<template>
  <div>
    <v-snackbar
      v-model="snackbar.show"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
    >
      {{ snackbar.text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar.show = false">
          Tutup
        </v-btn>
      </template>
    </v-snackbar>
    <delete-tabel
      @unshow="hapus.show = false"
      :show="hapus.show"
      :tabel="hapus.tabel"
    ></delete-tabel>
    <v-progress-linear
      v-model="syncStatus.progress"
      striped
      height="25"
      color="light-blue"
      v-if="syncStatus.loading"
    >
      <strong>{{ Math.ceil(syncStatus.progress) }}%</strong>
    </v-progress-linear>
    <v-data-table
      :headers="tabelTable.headers"
      :items="tabelTable.data"
      :itemsPerPage="tabelTable.itemsPerPage"
      class="elevation-3"
      :loading="tabelTable.loading"
      dense
      loading-text="Memuat... Mohon Tunggu"
      disable-sort
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Daftar Tabel</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-tooltip bottom>
            <template v-slot:activator="{ on: tooltip }">
              <v-btn small color="primary" class="mb-2" v-on="{ ...tooltip }">
                <v-icon @click="refreshTable">mdi-refresh</v-icon>
              </v-btn>
            </template>
            <span>Refresh Tabel</span>
          </v-tooltip>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-text-field
            clearable
            v-model="tabelTable.search"
            @keyup.enter="searchTabel"
            hide-details
            flat
            label="Cari tabel disini..."
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-tooltip bottom>
            <template v-slot:activator="{ on: tooltip }">
              <v-btn
                small
                color="primary"
                class="mb-2"
                v-on="tooltip"
                :loading="syncStatus.loading"
                :disabled="syncStatus.loading"
              >
                <v-icon @click="syncTabel">mdi-cloud-sync</v-icon>
              </v-btn>
            </template>
            <span>Sync Tabel Web</span>
          </v-tooltip>
          <v-divider class="mx-4" inset vertical></v-divider>
          <add-tabel
            :subjects="subjects"
            :categories="categories"
            :bidangs="bidangs"
            :user="user"
          ></add-tabel>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-tooltip bottom>
            <template
              v-slot:activator="{ on: tooltip }"
              v-bind="attrs"
              v-on="on"
            >
              <v-menu
                bottom
                :offset-x="true"
                :offset-y="true"
                :close-on-content-click="false"
              >
                <template v-slot:activator="{ on: menu, attrs }">
                  <v-btn
                    small
                    v-bind="attrs"
                    class="mb-2"
                    :color="
                      subjectId != '' || categoryId != '' || bidangId != ''
                        ? 'red'
                        : 'primary'
                    "
                    v-on="{ ...tooltip, ...menu }"
                  >
                    <v-icon>mdi-filter-menu</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item>
                    Kategori
                  </v-list-item>
                  <v-list-item>
                    <v-select
                      item-text="title"
                      item-value="subcat_id"
                      :items="tabelTable.categories"
                      v-model="categoryId"
                      hide-details
                      dense
                      label="Kategori"
                      outlined
                    ></v-select>
                  </v-list-item>
                  <v-list-item>
                    Subjek
                  </v-list-item>
                  <v-list-item>
                    <v-select
                      item-text="title"
                      item-value="sub_id"
                      :items="tabelTable.subjects"
                      v-model="subjectId"
                      hide-details
                      dense
                      label="Subjek"
                      outlined
                    ></v-select>
                  </v-list-item>
                  <v-list-item v-if="(user.role == 'ADMIN') == 'ADMIN'">
                    Bidang
                  </v-list-item>
                  <v-list-item v-if="(user.role == 'ADMIN') == 'ADMIN'">
                    <v-select
                      item-text="nama_bidang"
                      item-value="id"
                      :items="bidangs"
                      v-model="bidangId"
                      hide-details
                      dense
                      label="Subjek"
                      outlined
                    ></v-select>
                  </v-list-item>
                  <v-list-item>
                    <v-btn color="primary" small @click="resetFilter">
                      Reset
                      <v-icon>
                        mdi-cog-refresh
                      </v-icon>
                    </v-btn>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
            <span>Filter Tabel</span>
          </v-tooltip>
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }" class="text-left">
        <v-row>
          <v-col>
            <v-btn icon :href="viewItem(item)" target="_blank" class="mr-2">
              <v-icon
                v-if="item.tabel_web_id"
                small
                :disabled="item.user_id == null"
              >
                mdi-eye
              </v-icon>
            </v-btn>
            <edit-tabel
              :tabel="item"
              :subjects="subjects"
              :categories="categories"
              :bidangs="bidangs"
            ></edit-tabel>
            <v-icon
              small
              @click="deleteDialogShow(item)"
              :disabled="item.is_deleted == 1"
            >
              mdi-delete
            </v-icon>
          </v-col>
          <v-col v-if="user.role == 'ADMIN'">
            <v-select
              item-value="id"
              item-text="nama_bidang"
              :items="bidangs"
              v-model="item.user_id"
              hide-details
              dense
              v-on:change="changeUser(item)"
            ></v-select>
          </v-col>
        </v-row>
      </template>
      <template v-slot:[`item.judul`]="{ item }" class="text-left">
        <span>
          <div
            style="width: 7px;height:35px;float:left;margin:4px;border-radius:3px"
            :class="colorize(item.category_id)"
          ></div>
          <div>
            <div :class="[`font-weight-bold `]">
              {{ item.judul_tabel }}
            </div>
            <span
              :class="[`font-weight-black ${colorize(item.category_id)}--text`]"
            >
              {{ subjectName(item.subject_id) }}
              <v-chip
                v-if="item.tabel_web_id == null"
                x-small
                color="purple"
                outlined
              >
                Belum Upload
              </v-chip>
              <v-chip v-if="item.is_revisi == 1" x-small color="teal" outlined>
                Proses Revisi
              </v-chip>
              <v-chip v-if="item.is_deleted == 1" x-small color="red" outlined>
                Proses Hapus
              </v-chip>
            </span>
          </div>
        </span>
      </template>
      <template class="text-left" v-slot:no-data>
        Ups, Tidak ada daftar tabel
      </template>
    </v-data-table>
    <v-pagination
      v-model="currentPage"
      :length="tabelTable.pageLength"
      total-visible="8"
    ></v-pagination>
  </div>
</template>

<script>
import EditTabel from "./../components/tabelDinamis/EditTabel";
import AddTabel from "./../components/tabelDinamis/AddTabel";
import DeleteTabel from "./../components/tabelDinamis/DeleteTabel";

export default {
  components: {
    EditTabel,
    AddTabel,
    DeleteTabel
  },
  data: () => ({
    hapus: {
      show: false,
      tabel: {
        judul_tabel: ""
      }
    },
    currentPage: 1,
    subjectId: "",
    categoryId: "",
    bidangId: "",
    showEditDialog: false,
    rules: {
      required: value => !!value || "Harus Terisi."
    }
  }),
  created() {
    this.$store.dispatch("userStore/getBidang");
    this.$store.dispatch("tabelDinamisStore/setCategories");
    this.$store.dispatch("tabelDinamisStore/setSubjects");
    this.$store.dispatch("tabelDinamisStore/setTableData");
  },
  watch: {
    currentPage(val) {
      this.$store.dispatch("tabelDinamisStore/setTableData", val);
    },
    subjectId(val) {
      this.$store.dispatch("tabelDinamisStore/setSubjectFilter", val);
      this.currentPage = 1;
    },
    categoryId(val) {
      this.$store.dispatch("tabelDinamisStore/setCategoryFilter", val);
      this.currentPage = 1;
    },
    bidangId(val) {
      this.$store.dispatch("tabelDinamisStore/setBidangFilter", val);
      this.currentPage = 1;
    }
  },
  computed: {
    tabelTable() {
      return this.$store.state.tabelDinamisStore.tabelTable;
    },
    subjects() {
      return this.$store.state.tabelDinamisStore.subjects;
    },
    categories() {
      return this.$store.state.tabelDinamisStore.categories;
    },
    bidangs() {
      return this.$store.state.userStore.users;
    },
    snackbar() {
      return this.$store.state.tabelDinamisStore.snackbar;
    },
    syncStatus() {
      return this.$store.state.tabelDinamisStore.syncStatus;
    },
    bidangs() {
      return this.$store.state.userStore.users;
    },
    user() {
      return this.$store.state.userStore.user;
    }
  },
  methods: {
    viewItem(item) {
      return "/tabel/" + item.id + "/" + item.tabel_web_id;
    },
    colorize(val) {
      let color = ["gray", "blue", "orange", "green"];
      return color[val];
    },
    subjectName(val) {
      return this.subjects.find(obj => {
        return obj.sub_id == val;
      })?.title;
    },

    refreshTable() {
      this.$store.dispatch("tabelDinamisStore/refreshTable");
      this.currentPage = 1;
    },
    syncTabel() {
      this.$store.dispatch("tabelDinamisStore/syncTabelData");
      this.currentPage = 1;
    },
    searchTabel() {
      this.$store.dispatch("tabelDinamisStore/setTableData");
      this.currentPage = 1;
    },
    resetFilter() {
      this.subjectId = "";
      this.categoryId = "";
      this.bidangId = "";
      this.currentPage = 1;
    },
    changeUser(item) {
      this.$store.dispatch("tabelDinamisStore/editTabelUser", item);
    },
    deleteDialogShow(item) {
      this.hapus.show = true;
      this.hapus.tabel = item;
    }
  }
};
</script>

<style></style>
