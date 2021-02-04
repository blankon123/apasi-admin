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
    <v-row dense>
      <v-col lg="9" sm="12" xs="12">
        <v-card elevation="6">
          <v-list-item two-line class="text-left">
            <v-list-item-content>
              <v-list-item-title class="headline">
                Detail Publikasi
              </v-list-item-title>
              <v-list-item-subtitle
                >Detail Publikasi yang telah tersimpan</v-list-item-subtitle
              >
            </v-list-item-content>
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
                  filled
                  readonly
                  prepend-inner-icon="mdi-book"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="4" class="py-0 my-0">
                <v-text-field
                  :value="publikasi.user.name"
                  label="Bidang Terkait"
                  filled
                  readonly
                  prepend-inner-icon="mdi-book-account"
                  hide-details
                  class="mb-3"
                ></v-text-field>
                <v-text-field
                  :value="publikasi.jenis_arc == 1 ? 'ARC' : 'NON-ARC'"
                  label="Jenis Publikasi"
                  filled
                  readonly
                  prepend-inner-icon="mdi-calendar"
                  hide-details
                  class="mb-3"
                ></v-text-field>
                <v-text-field
                  :value="dateForHuman(publikasi.arc)"
                  label="Tanggal Terbit"
                  filled
                  readonly
                  prepend-inner-icon="mdi-clock-time-three"
                  class="mb-3"
                  hide-details
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="4" class="py-0 my-0">
                <v-text-field
                  :value="publikasi.ukuran == null ? '-' : publikasi.ukuran"
                  label="Ukuran Publikasi"
                  filled
                  readonly
                  prepend-inner-icon="mdi-stretch-to-page"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="publikasi.bahasa == null ? '-' : publikasi.bahasa"
                  label="Bahasa Publikasi"
                  filled
                  readonly
                  prepend-inner-icon="mdi-text-to-speech"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="
                    publikasi.orientasi == null ? '-' : publikasi.orientasi
                  "
                  label="Orientasi Publikasi"
                  filled
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
                      : publikasi.diterbitkan_untuk
                  "
                  label="Diterbitkan Untuk"
                  filled
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
                  filled
                  readonly
                  prepend-inner-icon="mdi-barcode"
                  class="mb-3"
                  hide-details
                ></v-text-field>
                <v-text-field
                  :value="
                    publikasi.cover_oleh == null ? '-' : publikasi.cover_oleh
                  "
                  label="Pembuat COver"
                  filled
                  readonly
                  prepend-inner-icon="mdi-format-paint"
                  class="mb-3"
                  hide-details
                ></v-text-field>
              </v-col>
            </v-row>
            {{ publikasi }}
          </v-card-text>
          <v-card-actions v-if="publikasi.judul_publikasi && true">
            <v-btn color="red" class="ma-1" :loading="true" :disabled="true">
              <v-icon left>
                mdi-cloud-upload
              </v-icon>
              Action
            </v-btn>
          </v-card-actions>
          <v-progress-linear
            indeterminate
            color="cyan"
            rounded
            v-else
            class="mt-12"
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
            v-else
            class="mt-12"
          ></v-progress-linear>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data: () => ({}),
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
    publikasi() {
      return this.$store.state.publikasiViewStore.publikasi;
    },
    snackbar() {
      return this.$store.state.publikasiViewStore.snackbar;
    }
  },
  methods: {
    colorize(i) {
      return i % 2 == 1 ? "pink" : "teal lighten-3";
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
