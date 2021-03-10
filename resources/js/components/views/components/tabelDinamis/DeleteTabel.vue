<template>
  <v-dialog v-model="show" max-width="500px">
    <v-card>
      <v-card-title>
        Konfirmasi Hapus
      </v-card-title>
      <v-card-text>
        Apakah anda Yakin akan Menghapus Tabel
        {{ tabel.judul_tabel }} dari Website BPS Kalteng ?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red darken-1" text @click="$emit('unshow')">Batal</v-btn>
        <v-btn color="blue darken-1" text @click="deleteTabel(tabel)">
          Hapus
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "DeleteTabel",
  props: ["tabel", "show"],
  methods: {
    deleteTabel(tabel) {
      this.$emit("unshow");
      this.$store.dispatch("tabelDinamisStore/deleteTabel", tabel);
    }
  }
};
</script>

<style></style>
