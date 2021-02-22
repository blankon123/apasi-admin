<template>
  <div>
    <v-card-title>
      {{ this.title }}
      <v-spacer></v-spacer>
      <v-icon :color="this.color">{{ this.icon }}</v-icon>
    </v-card-title>
    <v-divider></v-divider>
    <div v-if="this.pekerjaan && this.pekerjaan.length">
      <div v-for="(item, i) in this.pekerjaan" :key="i">
        <v-card outlined class="ma-2 pa-0" :color="item.color">
          <div>
            <div class="d-flex flex-no-wrap justify-space-between">
              <v-card-title
                class="headline font-weight-bold text-uppercase my-0 py-0"
                v-text="item.tipe_pekerjaan"
              >
              </v-card-title>
              <div>
                <v-btn
                  v-if="
                    nonDelete.some(f => {
                      return f !== item.tipe_pekerjaan;
                    })
                  "
                  rounded
                  x-small
                  elevation="2"
                  color="red"
                  @click="cangs"
                >
                  <v-icon left>
                    mdi-trash-can
                  </v-icon>
                  Batalkan
                </v-btn>
                <v-btn
                  rounded
                  x-small
                  elevation="2"
                  color="indigo"
                  @click="cangs"
                >
                  <v-icon left>
                    mdi-hammer
                  </v-icon>
                  Kerjakan
                </v-btn>
              </div>
            </div>

            <v-card-subtitle
              v-text="item.nama"
              class="text-start py-0 my-0"
            ></v-card-subtitle>
          </div>
        </v-card>
      </div>
    </div>
    <div v-else>
      <v-card-text class="gray--text text--darken-1">
        Tidak Ada Pekerjaan
      </v-card-text>
    </div>
  </div>
</template>

<script>
export default {
  name: "SedangPekerjaan",
  props: ["pekerjaan", "loading", "title", "color", "icon"],
  data() {
    return {
      nonDelete: ["layout"]
    };
  },
  methods: {
    cangs() {
      console.log("Cangs");
    }
  }
};
</script>

<style></style>
