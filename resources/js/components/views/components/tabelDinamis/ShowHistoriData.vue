<template>
  <div>
    <div :id="id" class="black--text"></div>
    <div v-if="!tabelHandsonReady">
      <v-row class="fill-height" align-content="center" justify="center">
        <v-col class="subtitle-1 text-center" cols="12">
          Mengambil Data
        </v-col>
        <v-col cols="8">
          <v-progress-linear
            color="blue accent-4"
            indeterminate
            rounded
            height="6"
          ></v-progress-linear>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id", "tabelHandsonMerged", "tabelHandsonData"],
  data: () => ({
    tabelHandson: {},
    checkingInterval: null,
    tabelHandsonReady: false
  }),
  created() {
    let localThis = this;
    this.checkingInterval = setInterval(function() {
      if (window.Handsontable) {
        localThis.tabelHandsonReady = true;
      }
    }, 100);
  },
  watch: {
    tabelHandsonReady(data) {
      if (data) {
        clearInterval(this.checkingInterval);
        this.init();
      }
    }
  },
  mounted() {},
  methods: {
    init() {
      function headerRenderer(
        instance,
        td,
        row,
        col,
        prop,
        value,
        cellProperties
      ) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = "bold";
        td.style.background = "#b0d8ff";
      }

      function leftRenderer(
        instance,
        td,
        row,
        col,
        prop,
        value,
        cellProperties
      ) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = "bold";
        td.style.textAlign = "left";
        td.style.background = "#b0d8ff";
      }

      function topLeftRenderer(
        instance,
        td,
        row,
        col,
        prop,
        value,
        cellProperties
      ) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = "bold";
        td.style.textAlign = "center";
        td.style.verticalAlign = "middle";
        td.style.background = "#b0d8ff";
      }

      let container = document.getElementById(this.id);
      this.tabelHandson = new Handsontable(container, {
        licenseKey: "non-commercial-and-evaluation",
        rowHeaders: true,
        colHeaders: true,
        allowInsertColumn: true,
        contextMenu: true,
        width: "100%",
        height: "60vh",
        fixedColumnsLeft: 1,
        fixedRowsTop: 3,
        mergeCells: this.tabelHandsonMerged,
        cells: function(row, col) {
          let cellProperties = {};
          if (row === 0 || row === 1 || row === 2) {
            cellProperties.renderer = headerRenderer; // uses function directly
          }
          if (col === 0) {
            cellProperties.renderer = leftRenderer; // uses function directly
          }
          if (row === 0 && col === 0) {
            cellProperties.renderer = topLeftRenderer; // uses function directly
          }
          return cellProperties;
        },
        data: this.tabelHandsonData
      });
      this.changeZIndexStyle("ht_clone_top_left_corner");
      this.changeZIndexStyle("ht_clone_left");
      this.changeZIndexStyle("ht_clone_top");
    },
    changeZIndexStyle(classtag) {
      document.getElementsByClassName(classtag).forEach(ele => {
        ele.style.zIndex = "1";
      });
    }
  }
};
</script>

<style></style>
