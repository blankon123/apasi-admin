<template>
  <div>
    <v-dialog max-width="700px" v-model="show">
      <v-card elevation="3">
        <v-card-title>
          {{ rekap.pilihan }} {{ rekap.bulanAwal }}-{{ rekap.bulanAkhir }}
          {{ rekap.tahun }}
        </v-card-title>
        <v-card-text>
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th></th>
                  <th
                    v-for="x in Object.keys(dataX)"
                    :key="x"
                    class="text-left"
                  >
                    {{ x }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="y in Object.keys(dataY)" :key="y">
                  <td>{{ properText(y) }}</td>
                  <td
                    v-for="x in Object.keys(dataX)"
                    :key="x"
                    class="font-weight-bold text-center"
                  >
                    {{ dataX[x][y] }}
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-btn color="blue" small :disabled="!rekapFormComplete" @click="doRekap">
      <v-icon>
        mdi-wrench
      </v-icon>
      Rekap
    </v-btn>
  </div>
</template>

<script>
export default {
  props: [
    "bulan",
    "rekap",
    "rekapFormComplete",
    "pekerjaan",
    "petugases",
    "notifikasiTabel",
    "notifikasiPublikasi"
  ],
  data() {
    return {
      show: false,
      dataX: [],
      dataY: []
    };
  },
  methods: {
    properText(str) {
      return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      });
    },
    doRekap() {
      if (this.rekap.pilihan == "Publikasi") {
        let notifikasiPublikasi = this.notifikasiPublikasi.filter(obj => {
          return (
            moment(obj.created_at).format("M") - 1 >=
              this.bulan.indexOf(this.rekap.bulanAwal) &&
            moment(obj.created_at).format("M") - 1 <=
              this.bulan.indexOf(this.rekap.bulanAkhir) &&
            moment(obj.created_at).format("YYYY") == this.rekap.tahun
          );
        });
        let logByPublikasi = notifikasiPublikasi.reduce((acc, value) => {
          if (!acc[value.data.user]) {
            acc[value.data.user] = [];
          }
          if (!acc[value.data.user][value.data.message]) {
            acc[value.data.user][value.data.message] = 0;
          }
          acc[value.data.user][value.data.message] += 1;
          return acc;
        }, {});
        let tipePekerjaan = notifikasiPublikasi.reduce((acc, value) => {
          if (!acc[value.data.message]) {
            acc[value.data.message] = 1;
          }
          return acc;
        }, {});
        this.show = true;
        this.dataX = logByPublikasi;
        this.dataY = tipePekerjaan;
      }
      if (this.rekap.pilihan == "Tabel") {
        let notifikasiTabel = this.notifikasiTabel.filter(obj => {
          return (
            moment(obj.created_at).format("M") - 1 >=
              this.bulan.indexOf(this.rekap.bulanAwal) &&
            moment(obj.created_at).format("M") - 1 <=
              this.bulan.indexOf(this.rekap.bulanAkhir) &&
            moment(obj.created_at).format("YYYY") == this.rekap.tahun
          );
        });
        let logByTabel = notifikasiTabel.reduce((acc, value) => {
          if (!acc[value.data.user]) {
            acc[value.data.user] = [];
          }
          if (!acc[value.data.user][value.data.message]) {
            acc[value.data.user][value.data.message] = 0;
          }
          acc[value.data.user][value.data.message] += 1;
          return acc;
        }, {});
        let tipePekerjaan = notifikasiTabel.reduce((acc, value) => {
          if (!acc[value.data.message]) {
            acc[value.data.message] = 1;
          }
          return acc;
        }, {});
        this.show = true;
        this.dataX = logByTabel;
        this.dataY = tipePekerjaan;
      }
      if (this.rekap.pilihan == "Pekerjaan") {
        let pekerjaan = this.pekerjaan.filter(obj => {
          return (
            moment(obj.created_at).format("M") - 1 >=
              this.bulan.indexOf(this.rekap.bulanAwal) &&
            moment(obj.created_at).format("M") - 1 <=
              this.bulan.indexOf(this.rekap.bulanAkhir) &&
            moment(obj.created_at).format("YYYY") == this.rekap.tahun
          );
        });
        let logByPekerjaan = pekerjaan.reduce((acc, value) => {
          let nama = this.petugases.filter(obj => {
            return obj.id == value.petugas_id;
          })[0].nama;

          if (!acc[nama]) {
            acc[nama] = [];
          }
          if (!acc[nama][value.tipe_pekerjaan]) {
            acc[nama][value.tipe_pekerjaan] = 0;
          }

          acc[nama][value.tipe_pekerjaan] += 1;
          return acc;
        }, {});
        let tipePekerjaan = pekerjaan.reduce((acc, value) => {
          if (!acc[value.tipe_pekerjaan]) {
            acc[value.tipe_pekerjaan] = 1;
          }
          return acc;
        }, {});
        this.show = true;
        this.dataX = logByPekerjaan;
        this.dataY = tipePekerjaan;
      }
    }
  }
};
</script>

<style></style>
