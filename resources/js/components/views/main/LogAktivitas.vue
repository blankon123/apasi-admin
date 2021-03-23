<template>
  <div>
    <v-row>
      <v-col md="9" sm="12">
        <v-card>
          <v-card-title>
            Catatan Aktivitas
            <v-icon>
              mdi-note
            </v-icon>
            <v-spacer></v-spacer>
            <v-btn icon @click="refresh">
              <v-icon>
                mdi-refresh
              </v-icon>
            </v-btn>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-tabs fixed-tabs>
              <v-tab>
                <v-icon>mdi-book</v-icon>
                Log Publikasi
              </v-tab>
              <v-tab>
                <v-icon>mdi-table</v-icon>
                Log Tabel Dinamis
              </v-tab>
              <v-tab-item>
                <v-data-table
                  :headers="notifikasiPublikasiHeader"
                  :items="notifikasiPublikasi"
                  class="elevation-1"
                  :search="notifikasiPublikasiSearch"
                  :loading="loading"
                >
                  <template v-slot:top>
                    <v-text-field
                      v-model="notifikasiPublikasiSearch"
                      label="Cari disini..."
                      class="mx-4"
                    ></v-text-field>
                  </template>
                  <template v-slot:[`item.waktu`]="{ item }">
                    {{ dateForHuman(item.created_at) }}
                  </template>
                  <template v-slot:[`item.warna`]="{ item }">
                    <div
                      style="width: 7px;height:35px;float:left;margin:4px;border-radius:3px"
                      :class="item.data.publikasi.user.color"
                    ></div>
                  </template>
                </v-data-table>
              </v-tab-item>
              <v-tab-item>
                <v-data-table
                  :headers="notifikasiTabelHeader"
                  :items="notifikasiTabel"
                  class="elevation-1"
                  :search="notifikasiTabelSearch"
                  :loading="loading"
                >
                  <template v-slot:top>
                    <v-text-field
                      v-model="notifikasiTabelSearch"
                      label="Cari disini..."
                      class="mx-4"
                    ></v-text-field>
                  </template>
                  <template v-slot:[`item.waktu`]="{ item }">
                    {{ dateForHuman(item.created_at) }}
                  </template>
                  <template v-slot:[`item.warna`]="{ item }">
                    <div
                      style="width: 7px;height:35px;float:left;margin:4px;border-radius:3px"
                      :class="item.data.tabel.user.color"
                    ></div>
                  </template>
                </v-data-table>
              </v-tab-item>
            </v-tabs>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col md="3" sm="12">
        <v-card :loading="rekap.loading">
          <v-card-title>
            Agregat Log
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-card elevation="5" class="mb-3">
              <v-card-title class="mb-2">
                <v-select
                  label="Berdasarkan"
                  :items="pilihan"
                  hide-details
                  v-model="rekap.pilihan"
                ></v-select>
              </v-card-title>
              <v-card-text>
                <v-row>
                  <v-col md="6">
                    <v-select
                      label="Bulan Awal"
                      :items="bulan"
                      hide-details
                      v-model="rekap.bulanAwal"
                      :value="this.bulan[new Date().getMonth()]"
                    ></v-select>
                  </v-col>
                  <v-col md="6">
                    <v-select
                      label="Bulan Akhir"
                      :items="bulan"
                      hide-details
                      v-model="rekap.bulanAkhir"
                      :value="this.bulan[new Date().getMonth()]"
                    ></v-select>
                  </v-col>
                  <v-col md="12">
                    <v-select
                      :value="new Date().getFullYear()"
                      label="Tahun"
                      hide-details
                      v-model="rekap.tahun"
                      :items="tahun"
                    >
                    </v-select>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions class="mb-2">
                <v-spacer></v-spacer>
                <agregat-log
                  :rekapFormComplete="rekapFormComplete"
                  :rekap="rekap"
                  :pekerjaan="pekerjaan"
                  :petugases="petugases"
                  :notifikasiTabel="notifikasiTabel"
                  :notifikasiPublikasi="notifikasiPublikasi"
                  :bulan="bulan"
                ></agregat-log>
              </v-card-actions>
            </v-card>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import agregatLog from "../components/log/agregatLog.vue";
export default {
  components: { agregatLog },
  data() {
    return {
      notifikasiPublikasiHeader: [
        {
          text: "Bidang",
          align: "start",
          sortable: false,
          value: "data.user"
        },
        {
          text: "",
          align: "start",
          sortable: false,
          value: "warna"
        },
        {
          text: "Pekerjaan",
          align: "start",
          sortable: false,
          hide: true,
          value: "data.message"
        },
        {
          text: "Publikasi",
          align: "start",
          sortable: false,
          hide: true,
          value: "data.publikasi.judul_publikasi"
        },
        {
          text: "Waktu",
          align: "start",
          sortable: false,
          value: "waktu"
        }
      ],
      notifikasiPublikasiSearch: "",
      notifikasiTabelHeader: [
        {
          text: "Bidang",
          align: "start",
          sortable: false,
          value: "data.user"
        },
        {
          text: "",
          align: "start",
          sortable: false,
          value: "warna"
        },
        {
          text: "Pekerjaan",
          align: "start",
          sortable: false,
          hide: true,
          value: "data.message"
        },
        {
          text: "Tabel",
          align: "start",
          sortable: false,
          hide: true,
          value: "data.tabel.judul_tabel"
        },
        {
          text: "Waktu",
          align: "start",
          sortable: false,
          value: "waktu"
        }
      ],
      rekap: {
        loading: false,
        show: false,
        data: [],
        dataY: [],
        pilihan: null,
        bulanAwal: "",
        bulanAkhir: "",
        tahun: ""
      },
      notifikasiTabelSearch: "",
      bulan: [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
      ],
      pilihan: ["Publikasi", "Tabel", "Pekerjaan"],
      tahun: [2019, 2020, 2021, 2022, 2023, 2024, 2025]
    };
  },
  methods: {
    refresh() {
      this.$store.dispatch("pekerjaanStore/init");
      this.$store.dispatch("petugasStore/getPetugasTable");
      this.$store.dispatch("logStore/getNotifikasiAll");
      this.rekap.bulanAwal = this.bulan[new Date().getMonth()];
      this.rekap.bulanAkhir = this.bulan[new Date().getMonth()];
      this.rekap.tahun = new Date().getFullYear();
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
  },
  computed: {
    rekapFormComplete() {
      return (
        this.rekap.pilihan &&
        this.rekap.bulanAwal &&
        this.rekap.bulanAkhir &&
        this.rekap.tahun
      );
    },
    loading() {
      return this.$store.state.logStore.notifikasi.loading;
    },
    notifikasi() {
      return this.$store.state.logStore.notifikasi.data;
    },
    notifikasiTabel() {
      return this.$store.state.logStore.notifikasi.tabel;
    },
    notifikasiPublikasi() {
      return this.$store.state.logStore.notifikasi.publikasi;
    },
    pekerjaan() {
      return this.$store.state.pekerjaanStore.pekerjaan.sudah;
    },
    petugases() {
      return this.$store.state.petugasStore.all;
    }
  },
  created() {
    this.$store.dispatch("pekerjaanStore/init");
    this.$store.dispatch("petugasStore/getPetugasTable");
    this.$store.dispatch("logStore/getNotifikasiAll");
    this.rekap.bulanAwal = this.bulan[new Date().getMonth()];
    this.rekap.bulanAkhir = this.bulan[new Date().getMonth()];
    this.rekap.tahun = new Date().getFullYear();
  }
};
</script>

<style></style>
