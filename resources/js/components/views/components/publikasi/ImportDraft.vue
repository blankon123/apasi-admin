<template>
  <v-btn color="blue">
    <v-icon left>
      mdi-cloud-upload
    </v-icon>
    Upload Draft
  </v-btn>
</template>

<script>
export default {
  name: "ImportDraft",
  props: ["id"],
  data() {
    return {};
  }
};
</script>

<style></style>
