<template>
  <div>
    PublikasiView {{ publikasiId }}
    <div class="text-center ma-2">
      <v-btn @click="snackbar = true">
        Open Snackbar
      </v-btn>
      <v-snackbar top color="success" v-model="snackbar">
        {{ text }}
      </v-snackbar>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    snackbar: false,
    text: `Hello, I'm a snackbar`
  }),
  computed: {
    publikasiId() {
      return this.$route.params.id;
    }
  }
};
</script>

<style></style>
