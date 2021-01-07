<template>
  <div id="app">
    <router-view></router-view>

    <v-snackbar
      v-model="snackbar"
      top
      :color="snackbarColor"
      :timeout="timeout"
    >
      {{ snackbarText }}
    </v-snackbar>
  </div>
</template>

<script>
export default {
  data: () => ({
    snackbar: false,
    snackbarColor: "blue",
    snackbarText: `Hello, I'm a snackbar`,
    timeout: 2000
  }),
  mounted() {
    this.$eventBus.$on("snackbar", ({ text, color }) => {
      this.snackbarText = text;
      this.snackbarColor = color;
      this.snackbar.show = true;
    });
  }
};
</script>

<style></style>
