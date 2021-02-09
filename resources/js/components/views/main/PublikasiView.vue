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
    <v-container class="fill-height" v-if="error.status">
      <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
          <v-card class="elevation-20">
            <v-toolbar color="red">
              <v-toolbar-title>Error</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-btn icon large target="_blank" v-on="on">
                    <v-icon>mdi-bookshelf</v-icon>
                  </v-btn>
                </template>
                <span>[APASI]</span>
              </v-tooltip>
            </v-toolbar>
            <v-card-text>
              {{ error.text }}
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <v-row dense v-else>
      <v-col lg="9" sm="12" xs="12">
        <v-card elevation="6">
          <v-list-item two-line class="text-left">
            <v-list-item-content>
              <v-list-item-title class="headline">
                Detail Publikasi
              </v-list-item-title>
              <v-list-item-subtitle
                >Detail Publikasi yang telah tersimpan
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <isi-sprp
                :publikasi="publikasi"
                v-if="publikasi.stage_id == 11"
              ></isi-sprp>
              <import-draft
                :publikasi="publikasi"
                v-if="publikasi.stage_id == 12"
              ></import-draft>
              <import-rilis
                :publikasi="publikasi"
                v-if="publikasi.stage_id == 13"
              ></import-rilis>
              <confirm-upload
                :publikasi="publikasi"
                v-if="publikasi.stage_id == 14"
              ></confirm-upload>
              <revisi
                :publikasi="publikasi"
                v-if="publikasi.stage_id == 15"
              ></revisi>
              <revisi-progress
                :publikasi="publikasi"
                v-if="publikasi.stage_id == 16"
              ></revisi-progress>
            </v-list-item-action>
          </v-list-item>
          <v-divider></v-divider>
          <v-card-text
            v-if="publikasi.judul_publikasi && true"
            class="text-left"
          >
            <v-row>
              <v-col cols="12">
                <v-text-field
                  :value="publikasi.judul_publikasi"
                  label="Judul Publikasi"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-book"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="4" class="py-0 my-0">
                <v-text-field
                  :value="publikasi.user.name"
                  label="Bidang Terkait"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-book-account"
                  hide-details
                  class="mb-3"
                ></v-text-field>
                <v-text-field
                  :value="publikasi.jenis_arc == 1 ? 'ARC' : 'NON-ARC'"
                  label="Jenis Publikasi"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-calendar"
                  hide-details
                  class="mb-3"
                ></v-text-field>
                <v-text-field
                  :value="dateForHuman(publikasi.arc)"
                  label="Tanggal Terbit"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-clock-time-three"
                  class="mb-3"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="4" class="py-0 my-0">
                <v-text-field
                  :value="
                    publikasi.ukuran == null
                      ? '-'
                      : getName(ukuran, publikasi.ukuran)
                  "
                  label="Ukuran Publikasi"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-stretch-to-page"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="
                    publikasi.bahasa == null
                      ? '-'
                      : getName(bahasa, publikasi.bahasa)
                  "
                  label="Bahasa Publikasi"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-text-to-speech"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="
                    publikasi.orientasi == null
                      ? '-'
                      : getName(orientasi, publikasi.orientasi)
                  "
                  label="Orientasi Publikasi"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-crop-rotate"
                  class="mb-3"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="4" class="py-0 my-0">
                <v-text-field
                  :value="
                    publikasi.diterbitkan_untuk == null
                      ? '-'
                      : getName(diterbitkan_untuk, publikasi.diterbitkan_untuk)
                  "
                  label="Diterbitkan Untuk"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-account-supervisor"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="
                    publikasi.numbering == null ? '-' : publikasi.numbering
                  "
                  label="ISSN/ISBN"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-barcode"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="
                    publikasi.cover_oleh == null
                      ? '-'
                      : getName(cover_oleh, publikasi.cover_oleh)
                  "
                  label="Pembuat Cover"
                  outlined
                  readonly
                  prepend-inner-icon="mdi-format-paint"
                  class="mb-3"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  outlined
                  readonly
                  auto-grow
                  hide-details
                  label="Abstraksi"
                  :value="
                    publikasi.abstraksi == null ? '-' : publikasi.abstraksi
                  "
                ></v-textarea>
              </v-col>
            </v-row>
          </v-card-text>
          <v-progress-linear
            indeterminate
            color="cyan"
            rounded
            v-if="loading"
          ></v-progress-linear>
        </v-card>
      </v-col>
      <v-col lg="3" sm="12" xs="12" class="text-left">
        <v-card elevation="6">
          <v-list-item two-line class="text-left">
            <v-list-item-content>
              <v-list-item-title class="headline">
                Histori Publikasi
              </v-list-item-title>
              <v-list-item-subtitle
                >Daftar Perubahan pada Publikasi</v-list-item-subtitle
              >
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-card-text class="pa-0 ma-0" v-if="publikasi.historis && true">
            <v-timeline align-top dense>
              <div v-for="(item, index) in publikasi.historis" :key="index">
                <v-timeline-item :color="colorize(index)" dense>
                  <strong> Publikasi {{ item.Keterangan }} </strong>
                  <div>
                    <strong>
                      Oleh
                      <span :class="item.user.color + '--text'">
                        {{ item.user.nama_bidang }}
                      </span>
                    </strong>
                  </div>
                  <div>{{ dateForHuman(item.created_at) }}</div>
                </v-timeline-item>
              </div>
              <!-- 
              <v-timeline-item color="teal lighten-3" small>
                <v-row class="pt-1">
                  <v-col cols="3">
                    <strong>3-4pm</strong>
                  </v-col>
                  <v-col>
                    <strong>Design Stand Up</strong>
                    <div class="caption mb-2">
                      Hangouts
                    </div>
                    <v-avatar>
                      <v-img
                        src="https://avataaars.io/?avatarStyle=Circle&topType=LongHairFrida&accessoriesType=Kurt&hairColor=Red&facialHairType=BeardLight&facialHairColor=BrownDark&clotheType=GraphicShirt&clotheColor=Gray01&graphicType=Skull&eyeType=Wink&eyebrowType=RaisedExcitedNatural&mouthType=Disbelief&skinColor=Brown"
                      ></v-img>
                    </v-avatar>
                    <v-avatar>
                      <v-img
                        src="https://avataaars.io/?avatarStyle=Circle&topType=ShortHairFrizzle&accessoriesType=Prescription02&hairColor=Black&facialHairType=MoustacheMagnum&facialHairColor=BrownDark&clotheType=BlazerSweater&clotheColor=Black&eyeType=Default&eyebrowType=FlatNatural&mouthType=Default&skinColor=Tanned"
                      ></v-img>
                    </v-avatar>
                    <v-avatar>
                      <v-img
                        src="https://avataaars.io/?avatarStyle=Circle&topType=LongHairMiaWallace&accessoriesType=Sunglasses&hairColor=BlondeGolden&facialHairType=Blank&clotheType=BlazerSweater&eyeType=Surprised&eyebrowType=RaisedExcited&mouthType=Smile&skinColor=Pale"
                      ></v-img>
                    </v-avatar>
                  </v-col>
                </v-row>
              </v-timeline-item>

              <v-timeline-item color="pink" small>
                <v-row class="pt-1">
                  <v-col cols="3">
                    <strong>12pm</strong>
                  </v-col>
                  <v-col>
                    <strong>Lunch break</strong>
                  </v-col>
                </v-row>
              </v-timeline-item>

              <v-timeline-item color="teal lighten-3" small>
                <v-row class="pt-1">
                  <v-col cols="3">
                    <strong>9-11am</strong>
                  </v-col>
                  <v-col>
                    <strong>Finish Home Screen</strong>
                    <div class="caption">
                      Web App
                    </div>
                  </v-col>
                </v-row>
              </v-timeline-item> -->
            </v-timeline>
          </v-card-text>
          <v-progress-linear
            indeterminate
            color="cyan"
            rounded
            v-if="loading"
          ></v-progress-linear>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import ConfirmUpload from "./../components/publikasi/ConfirmUpload";
import ImportDraft from "./../components/publikasi/ImportDraft";
import ImportRilis from "./../components/publikasi/ImportRilis";
import IsiSprp from "./../components/publikasi/IsiSprp";
import Revisi from "./../components/publikasi/Revisi";
import RevisiProgress from "./../components/publikasi/RevisiProgress";

export default {
  components: {
    ConfirmUpload,
    ImportDraft,
    ImportRilis,
    IsiSprp,
    Revisi,
    RevisiProgress
  },
  data: () => ({
    ukuran: [
      { kode: "1", show: "A5" },
      { kode: "2", show: "A4" },
      { kode: "3", show: "B5 JIS" },
      { kode: "4", show: "B5 ISO" },
      { kode: "5", show: "Lainnya" }
    ],
    bahasa: [
      { kode: "1", show: "Indonesia" },
      { kode: "2", show: "Inggris" },
      { kode: "3", show: "Indonesia & Inggris" }
    ],
    orientasi: [
      { kode: "1", show: "Portrait" },
      { kode: "2", show: "Landscape" }
    ],
    diterbitkan_untuk: [
      { kode: "1", show: "Eksternal" },
      { kode: "2", show: "Internal" }
    ],
    cover_oleh: [
      { kode: "1", show: "IPDS" },
      { kode: "2", show: "SM" }
    ]
  }),
  created() {
    this.$store.dispatch(
      "publikasiViewStore/setPublikasiId",
      this.$route.params.id
    );
    this.$store.dispatch("publikasiViewStore/setPublikasiDetails");
  },
  computed: {
    publikasiId() {
      return this.$store.state.publikasiViewStore.publikasiId;
    },
    error() {
      return this.$store.state.publikasiViewStore.error;
    },
    publikasi() {
      return this.$store.state.publikasiViewStore.publikasi;
    },
    snackbar() {
      return this.$store.state.publikasiViewStore.snackbar;
    },
    loading() {
      return this.$store.state.publikasiViewStore.loading;
    }
  },
  methods: {
    colorize(i) {
      return i % 2 == 1 ? "pink" : "teal lighten-3";
    },
    getName(arr, id) {
      return arr.find(function(item, index) {
        if (item.kode == id) return true;
      }).show;
    },
    dateForHuman(arcDate) {
      let dateResult = moment(arcDate);
      return dateResult.isValid()
        ? dateResult.locale("id").calendar(null, {
            lastDay: "[Kemarin, ] D MMMM yyyy",
            sameDay: "[Hari Ini,] D MMMM yyyy",
            nextDay: "[Besok,] dddd",
            lastWeek: "dddd [Kemarin]",
            nextWeek: "dddd, D MMMM yyyy",
            sameElse: "dddd, D MMMM yyyy"
          })
        : "-";
    }
  }
};
</script>

<style></style>
