<template>
  <div>
    TabelView {{ tabelId }}
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
    text: `Hello, I'm a snackbar`,
    tabelId: $route.params.id
  })
};
</script>

<style></style>
