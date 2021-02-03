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
      <v-col lg="4" sm="12" xs="12" class="text-left">
        <v-card elevation="6">
          <v-card-text class="py-0">
            <v-timeline align-top dense>
              <v-timeline-item color="pink" small>
                <v-row class="pt-1">
                  <v-col cols="3">
                    <strong>5pm</strong>
                  </v-col>
                  <v-col>
                    <strong>New Icon</strong>
                    <div class="caption">
                      Mobile App
                    </div>
                  </v-col>
                </v-row>
              </v-timeline-item>

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
              </v-timeline-item>
            </v-timeline>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col lg="8" sm="12" xs="12">
        <v-card elevation="6"></v-card>
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
    snackbar() {
      return this.$store.state.publikasiViewStore.snackbar;
    }
  }
};
</script>

<style></style>
