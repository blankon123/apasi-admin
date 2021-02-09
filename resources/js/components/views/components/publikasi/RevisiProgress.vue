<template>
  <v-btn color="orange darken-3" disabled>
    <v-icon left>
      mdi-progress-wrench
    </v-icon>
    Sedang Direvisi
  </v-btn>
</template>

<script>
export default {
  name: "Revisi",
  props: ["id"],
  data() {
    return {};
  }
};
</script>

<style></style>
