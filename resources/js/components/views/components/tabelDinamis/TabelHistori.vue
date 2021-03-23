<template>
  <div>
    <v-dialog v-model="dialog.show" max-width="900px">
      <v-card>
        <v-card-text>
          <v-tabs>
            <v-tab>
              <v-icon>mdi-note</v-icon>
              Detail
            </v-tab>
            <v-tab>
              <v-icon>mdi-database</v-icon>
              Data
            </v-tab>
            <v-tab-item>
              <div class="ma-2 pa-2">
                <v-textarea
                  :value="dialog.item.keterangan"
                  label="Keterangan"
                  readonly
                  outlined
                  rows="1"
                ></v-textarea>
                <v-textarea
                  :value="dialog.item.perubahan"
                  label="Perubahan"
                  readonly
                  outlined
                  rows="2"
                ></v-textarea>
              </div>
            </v-tab-item>
            <v-tab-item>
              <show-histori-data
                :tabelHandsonMerged="dialog.item.data.merged"
                :tabelHandsonData="dialog.item.data.data"
                :id="'histori' + dialog.item.id"
              ></show-histori-data>
            </v-tab-item>
          </v-tabs>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card elevation="6">
      <v-list-item two-line class="text-left">
        <v-list-item-content>
          <v-list-item-title class="headline">
            Histori Tabel
          </v-list-item-title>
          <v-list-item-subtitle>Daftar Perubahan</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
      <v-card-text class="pa-0 ma-0" v-if="historisNotNull">
        <v-timeline
          align-top
          dense
          style="max-height: 68vh"
          class="overflow-y-auto"
        >
          <div class="pa-0 ma-0" v-for="(item, index) in historis" :key="index">
            <v-timeline-item
              class="py-1 ma-0 text-left"
              :color="colorize(index)"
              small
              dense
            >
              <strong>{{ item.keterangan }} </strong>
              <div>
                <strong>
                  Oleh
                  <span :class="item.user.color + '--text'">
                    {{ item.user.nama_bidang }}
                  </span>
                  <span v-if="item.data">
                    <v-btn
                      x-small
                      class="ma-1"
                      color="primary"
                      @click="showDialog(item)"
                    >
                      <v-icon>
                        mdi-table-headers-eye
                      </v-icon>
                      Lihat Data
                    </v-btn>
                  </span>
                </strong>
              </div>
              <div>{{ dateForHuman(item.created_at) }}</div>
            </v-timeline-item>
          </div>
        </v-timeline>
      </v-card-text>
      <v-card-text class="pa-0 ma-0" v-else>
        Belum ada catatan
      </v-card-text>
      <v-progress-linear
        indeterminate
        color="cyan"
        rounded
        v-if="loading"
      ></v-progress-linear>
    </v-card>
  </div>
</template>

<script>
import ShowHistoriData from "./ShowHistoriData.vue";

export default {
  components: { ShowHistoriData },
  props: ["historis", "loading"],
  created() {},
  computed: {
    historisNotNull() {
      return this.historis?.length;
    }
  },
  data() {
    return {
      dialog: {
        item: {
          data: {
            data: null,
            merged: null
          },
          keterangan: "",
          perubahan: ""
        },
        show: false
      }
    };
  },
  methods: {
    colorize(i) {
      return i % 2 == 1 ? "pink" : "teal";
    },
    showDialog(item) {
      this.dialog.show = true;
      this.dialog.item = item;
    },
    dateForHuman(arcDate) {
      let dateResult = window.moment(arcDate).utcOffset(7 * 60);
      return dateResult.isValid()
        ? dateResult.format("DD-MM-YYYY , HH:mm")
        : "-";
    }
  }
};
</script>

<style></style>
