<template>
  <div>
    <v-container>
      <v-row>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="title">
              Log Aktivitas
            </v-list-item-title>
            <v-list-item-subtitle>
              Catatan Perubahan Terakhir
            </v-list-item-subtitle>
          </v-list-item-content>
          <v-btn small @click="refresh" icon :loading="notifikasiLoading">
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-list-item>
      </v-row>
      <v-divider></v-divider>
      <v-row class="py-0 my-1" v-if="notifikasi.length != 0">
        <v-card
          v-for="(item, index) in notifikasi"
          :key="index"
          :class="`ma-1 pa-1 ${colorize(item)} darken-2`"
        >
          <v-expansion-panels
            class="py-0"
            :multiple="index == 0 || index == 1"
            accordion
            :value="expanded"
          >
            <v-expansion-panel class="py-0">
              <v-expansion-panel-header class="py-0 text-subtitle-2">{{
                item.data.tabel
                  ? "Tabel " + item.data.message
                  : "Publikasi " + item.data.message
              }}</v-expansion-panel-header>
              <v-expansion-panel-content>
                <div class="text-caption">
                  {{ item.data.tabel ? item.data.tabel.judul_tabel : "" }}
                  {{
                    item.data.publikasi
                      ? item.data.publikasi.judul_publikasi
                      : ""
                  }}
                </div>
                <v-divider></v-divider>
                <div class="text-caption font-weight-bold">
                  <span class="text">{{ item.data.user }}</span>
                  {{ dateForHuman(item.created_at) }}
                </div>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card>
      </v-row>
      <v-row v-else class="pa-2 ma-2">
        Tidak Ada Notifikasi
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    expanded: [0]
  }),
  computed: {
    notifikasi() {
      return this.$store.state.indexMainStore.notifikasi.data;
    },
    notifikasiLoading() {
      return this.$store.state.indexMainStore.notifikasi.loading;
    }
  },
  created() {
    this.$store.dispatch("indexMainStore/getNotifikasi");
  },
  methods: {
    refresh() {
      this.$store.dispatch("indexMainStore/getNotifikasi");
    },
    colorize(item) {
      return item.data.tabel
        ? item.data.tabel.user?.color
        : item.data.publikasi?.user?.color;
    },
    dateForHuman(arcDate) {
      let dateResult = moment(arcDate);
      return dateResult.isValid()
        ? dateResult.locale("id").calendar(null, {
            lastDay: "[Kemarin], HH:mm",
            sameDay: "[Hari Ini,] D MMMM yyyy, HH:mm",
            lastWeek: "dddd [Kemarin], HH:mm",
            nextWeek: "dddd, D MMMM yyyy, HH:mm",
            sameElse: "dddd, D MMMM yyyy, HH:mm"
          })
        : "-";
    }
  }
};
</script>

<style></style>
