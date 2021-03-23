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
    <v-fab-transition>
      <v-btn color="blue" fab absolute bottom right @click="refresh">
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </v-fab-transition>
    <v-row>
      <v-col md="6" sm="12">
        <v-card>
          <v-card-title>
            Publikasi Terhapus
            <v-spacer></v-spacer>
            <v-icon class="red--text">
              mdi-note-remove
            </v-icon>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-data-table
              :headers="publikasiHeader"
              :items="publikasi"
              class="elevation-1"
              :search="publikasiSearch"
              :loading="loading"
            >
              <template v-slot:top>
                <v-text-field
                  v-model="publikasiSearch"
                  label="Cari disini..."
                  class="mx-4"
                ></v-text-field>
              </template>
              <template v-slot:[`item.waktu`]="{ item }">
                {{ dateForHuman(item.deleted_at) }}
              </template>
              <template v-slot:[`item.warna`]="{ item }">
                <div
                  style="width: 7px;height:35px;float:left;margin:4px;border-radius:3px"
                  :class="item.user.color"
                ></div>
              </template>
              <template v-slot:[`item.restore`]="{ item }">
                <v-btn color="green" x-small @click="restorePublikasi(item)">
                  <v-icon>
                    mdi-restore
                  </v-icon>
                  Restore
                </v-btn>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col md="6" sm="12">
        <v-card>
          <v-card-title>
            Tabel Terhapus
            <v-spacer></v-spacer>
            <v-icon class="red--text">
              mdi-table-remove
            </v-icon>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-data-table
              :headers="tabelHeader"
              :items="tabel"
              class="elevation-1"
              :search="tabelSearch"
              :loading="loading"
            >
              <template v-slot:top>
                <v-text-field
                  v-model="tabelSearch"
                  label="Cari disini..."
                  class="mx-4"
                ></v-text-field>
              </template>
              <template v-slot:[`item.waktu`]="{ item }">
                {{ dateForHuman(item.deleted_at) }}
              </template>
              <template v-slot:[`item.warna`]="{ item }">
                <div
                  style="width: 7px;height:35px;float:left;margin:4px;border-radius:3px"
                  :class="item.user.color"
                ></div>
              </template>
              <template v-slot:[`item.restore`]="{ item }">
                <v-btn color="green" x-small @click="restoreTabel(item)">
                  <v-icon>
                    mdi-restore
                  </v-icon>
                  Restore
                </v-btn>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  mounted() {
    this.$store.dispatch("trashStore/getTrashAll");
  },
  data() {
    return {
      publikasiSearch: "",
      publikasiHeader: [
        {
          text: "Bidang",
          align: "start",
          sortable: false,
          value: "user.nama_bidang"
        },
        {
          text: "",
          align: "start",
          sortable: false,
          value: "warna"
        },
        {
          text: "Publikasi",
          align: "start",
          sortable: false,
          hide: true,
          value: "judul_publikasi"
        },
        {
          text: "Dihapus Pada",
          align: "start",
          sortable: false,
          value: "waktu"
        },
        {
          text: "",
          align: "start",
          sortable: false,
          value: "restore"
        }
      ],
      tabelSearch: "",
      tabelHeader: [
        {
          text: "Bidang",
          align: "start",
          sortable: false,
          value: "user.nama_bidang"
        },
        {
          text: "",
          align: "start",
          sortable: false,
          value: "warna"
        },
        {
          text: "Tabel",
          align: "start",
          sortable: false,
          hide: true,
          value: "judul_tabel"
        },
        {
          text: "Dihapus Pada",
          align: "start",
          sortable: false,
          value: "waktu"
        },
        {
          text: "",
          align: "start",
          sortable: false,
          value: "restore"
        }
      ]
    };
  },
  computed: {
    publikasi() {
      return this.$store.state.trashStore.publikasiTrash;
    },
    tabel() {
      return this.$store.state.trashStore.tabelDinamisTrash;
    },
    loading() {
      return this.$store.state.trashStore.loading;
    },
    snackbar() {
      return this.$store.state.trashStore.snackbar;
    }
  },
  methods: {
    refresh() {
      this.$store.dispatch("trashStore/getTrashAll");
    },
    restorePublikasi(item) {
      this.$store.dispatch("trashStore/restore", {
        type: "publikasi",
        id: item.id
      });
    },
    restoreTabel(item) {
      this.$store.dispatch("trashStore/restore", {
        type: "tabelDinamis",
        id: item.id
      });
    },
    dateForHuman(arcDate) {
      let dateResult = moment(arcDate);
      return dateResult.isValid()
        ? dateResult.locale("id").calendar(null, {
            lastDay: "[Kemarin] HH:mm",
            sameDay: "HH:mm",
            nextDay: "[Besok,] dddd HH:mm",
            lastWeek: "dddd [Kemarin] HH:mm",
            nextWeek: "dddd, D M yyyy HH:mm",
            sameElse: "dddd, D M yyyy HH:mm"
          })
        : "-";
    }
  }
};
</script>

<style></style>
