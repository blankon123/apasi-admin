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
    <v-card fluid class="mb-2" elevation="2">
      <v-card-title class="text-left mb-0 pb-0">
        {{ tabel.judul_tabel ? tabel.judul_tabel : "" }}
        <v-spacer></v-spacer>
        <save-tabel
          :disabled="this.tabActive == 0"
          @saveApasiTabel="saveTabelApasi"
        >
        </save-tabel>
        <edit-tabel-view
          :tabel="tabel"
          :tabelWeb="tabelDataWeb"
          :subjects="subjects"
          :categories="categories"
          :bidangs="bidangs"
          :userRole="userRole"
        ></edit-tabel-view>
        <div>
          <v-tooltip bottom>
            <template v-slot:activator="{ on: tooltip }">
              <v-btn
                small
                class="mx-1"
                color="primary"
                @click="deleteDialogShow()"
                v-on="{ ...tooltip }"
                :disabled="tabel.is_deleted == 1"
              >
                <v-icon>mdi-trash-can</v-icon>
              </v-btn>
            </template>
            <span>Hapus Tabel</span>
          </v-tooltip>
        </div>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-tooltip bottom>
          <template v-slot:activator="{ on: tooltip }">
            <v-btn
              color="primary"
              small
              @click="refresh"
              :loading="loading"
              v-on="{ ...tooltip }"
            >
              <v-icon>mdi-refresh</v-icon>
            </v-btn>
          </template>
          <span>Refresh Konten</span>
        </v-tooltip>
      </v-card-title>
      <v-card-text class="text-left">
        <span>
          <v-chip small outlined :color="colorize(tabel.category_id)">
            {{ categoryName(tabel.category_id) }}
          </v-chip>
        </span>
        <span>
          <v-chip small outlined :color="colorize(tabel.category_id)">
            {{ subjectName(tabel.subject_id) }}
          </v-chip>
        </span>
        <span>
          <v-chip small :color="userColor">
            {{ username }}
          </v-chip>
        </span>
        <span>
          <v-chip v-if="tabel.is_revisi == 1" small color="teal" outlined>
            Proses Revisi
          </v-chip>
        </span>
        <span>
          <v-chip v-if="tabel.is_deleted == 1" small color="red" outlined>
            Proses Hapus
          </v-chip>
        </span>
      </v-card-text>
      <v-progress-linear
        indeterminate
        color="cyan"
        rounded
        v-if="loading"
      ></v-progress-linear>
    </v-card>
    <v-row>
      <v-col sm="12" lg="9">
        <v-tabs fixed-tabs v-model="tabActive">
          <v-tab>
            <v-icon>mdi-web</v-icon>
            Web
          </v-tab>
          <v-tab>
            <v-icon>mdi-database</v-icon>
            Apasi
          </v-tab>
          <v-tab-item>
            <tabel-data
              ref="tabelWeb"
              id="tabelWeb"
              :loading="webLoading"
              :tabelData="tabelDataWeb"
              :readOnly="1"
            ></tabel-data>
          </v-tab-item>
          <v-tab-item>
            <tabel-data
              id="tabelApasi"
              ref="tabelApasi"
              :loading="webLoading"
              :tabelData="tabelDataWeb"
              :readOnly="0"
            ></tabel-data>
          </v-tab-item>
        </v-tabs>
      </v-col>
      <v-col sm="12" lg="3">
        <tabel-histori
          :historis="tabel.historis"
          :loading="webLoading"
        ></tabel-histori>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import EditTabelView from "../components/tabelDinamis/EditTabelView.vue";
import TabelData from "./../components/tabelDinamis/TabelData";
import SaveTabel from "./../components/tabelDinamis/SaveTabel";
import TabelHistori from "./../components/tabelDinamis/TabelHistori";
import DeleteTabel from "./../components/tabelDinamis/DeleteTabel";

export default {
  components: {
    TabelData,
    DeleteTabel,
    EditTabelView,
    TabelHistori,
    SaveTabel
  },
  data: () => ({
    tabActive: null,
    hapus: {
      show: false,
      tabel: {
        judul_tabel: ""
      }
    }
  }),
  mounted() {
    this.$store.dispatch("userStore/getBidang");
    this.$store.dispatch("tabelDinamisStore/setCategories");
    this.$store.dispatch("tabelDinamisStore/setSubjects");
    this.$store.dispatch("tabelDinamisStore/getTabel", this.$route.params.id);
    this.$store.dispatch(
      "tabelDinamisStore/getTabelWeb",
      this.$route.params.web_id
    );
  },
  computed: {
    tabelDataWeb() {
      return this.$store.state.tabelDinamisStore.tabelDetail.tabelDataWeb;
    },
    loading() {
      return this.$store.state.tabelDinamisStore.tabelDetail.loading;
    },
    webLoading() {
      return this.$store.state.tabelDinamisStore.tabelDetail.webLoading;
    },
    username() {
      return this.tabel.user?.name;
    },
    userRole() {
      return this.tabel.user?.role;
    },
    userColor() {
      return this.tabel.user?.color + " darken-2";
    },
    tabel() {
      return this.$store.state.tabelDinamisStore.tabelDetail.tabel;
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
    }
  },
  methods: {
    colorize(val) {
      let color = ["gray", "blue", "orange", "green"];
      return color[val];
    },
    refresh() {
      this.$store.dispatch("tabelDinamisStore/getTabel", this.$route.params.id);
      this.$refs.tabelWeb.init();
    },
    subjectName(val) {
      return this.subjects.find(obj => {
        return obj.sub_id == val;
      })?.title;
    },
    categoryName(val) {
      return this.categories.find(obj => {
        return obj.subcat_id == val;
      })?.title;
    },
    saveTabelApasi(komentar) {
      this.$store.dispatch("tabelDinamisStore/simpanTabel", {
        id: this.$route.params.id,
        data: this.$refs.tabelApasi.getData(),
        perubahan: komentar
      });
    },
    deleteDialogShow() {
      this.hapus.show = true;
      this.hapus.tabel = this.tabel;
    }
  }
};
</script>

<style></style>
