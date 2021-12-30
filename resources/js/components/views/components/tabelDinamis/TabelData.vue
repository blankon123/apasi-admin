<template>
  <v-card style='z-index:1;'>
    <v-card-text class="my-1 py-1">
      <div :id="id" class="black--text "></div>
    </v-card-text>
    <v-progress-linear
      indeterminate
      color="cyan"
      rounded
      v-if="loading"
    ></v-progress-linear>
  </v-card>
</template>

<script>
export default {
  props: ["loading", "tabelData", "id", "readOnly"],
  data: () => ({
    tabelHandson: {},
    checkingInterval: null,
    tabelHandsonData: [],
    tabelHandsonReady: false,
    tabelHandsonMerged: [],
    tabelHandsonBorder: []
  }),
  created() {
    let localThis = this;
    this.checkingInterval = setInterval(function() {
      if (window.Handsontable && "status" in localThis.tabelData) {
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
  mounted() {
    this.loadJSLibrary(
      "https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"
    );
    this.loadCSSLibrary(
      "https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css"
    );
  },
  methods: {
    init() {
      this.excelize(this.tabelData);
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
        readOnly: this.readOnly,
        width: "100%",
        height: "60vh",
        fixedColumnsLeft: 1,
        fixedRowsTop: 3,
        mergeCells: this.tabelHandsonMerged,
        // customBorders: this.tabelHandsonBorder,
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
    loadJSLibrary(url) {
      let scriptTag = document.createElement("script");
      scriptTag.setAttribute("src", url);
      document.head.appendChild(scriptTag);
    },
    loadCSSLibrary(url) {
      let styleTag = document.createElement("link");
      styleTag.setAttribute("rel", "stylesheet");
      styleTag.setAttribute("href", url);
      document.head.appendChild(styleTag);
    },
    changeZIndexStyle(classtag) {
      document.getElementsByClassName(classtag).forEach(ele => {
        ele.style.zIndex = "1";
      });
    },
    excelize(data) {
      let vervars = data.vervar;
      let vars = data.var[0];
      let turvars = data.turvar;
      let tahuns = data.tahun;
      let turtahuns = data.turtahun;
      let dataContents = data.datacontent;
      // let verticalLength = turvars.length * tahuns.length * turtahuns.length;
      let turvarData = [data.labelvervar];
      let tahunData = [""];
      let turtahunData = [""];

      let mergedRow = [{ row: 0, col: 0, rowspan: 3, colspan: 1 }];
      let mergedNumber = tahuns.length * turtahuns.length;
      let startMerged = 1;

      let startBorderColumn = 1;
      let borderTabel = [
        {
          range: {
            from: {
              row: 2,
              col: 0
            },
            to: {
              row: 2,
              col: mergedNumber * turvars.length
            }
          },
          bottom: {
            width: 3,
            color: "black"
          }
        }
      ];

      turvars.forEach(turvar => {
        borderTabel.push({
          range: {
            from: {
              row: 0,
              col: startBorderColumn
            },
            to: {
              row: 2 + vervars.length,
              col: startBorderColumn + mergedNumber - 1
            }
          },
          left: {
            width: 3,
            color: "black"
          },
          right: {
            width: 3,
            color: "black"
          }
        });
        mergedRow.push({
          col: startMerged,
          row: 0,
          colspan: mergedNumber,
          rowspan: 1
        });

        startBorderColumn += mergedNumber;
        startMerged += mergedNumber;

        tahuns.forEach(tahun => {
          turtahuns.forEach(turtahun => {
            turvarData.push(turvar.label);
            tahunData.push(tahun.label);
            turtahunData.push(turtahun.label);
          });
        });
      });
      let dataWeb = [turvarData, tahunData, turtahunData];

      vervars.forEach(vervar => {
        let row = [vervar.label];
        turvars.forEach(turvar => {
          tahuns.forEach(tahun => {
            turtahuns.forEach(turtahun => {
              //urutan Data -> vervar.var.turvar.tahun.turtahun
              row.push(
                dataContents[
                  "" +
                    vervar.val +
                    vars.val +
                    turvar.val +
                    tahun.val +
                    turtahun.val
                ]
              );
            });
          });
        });
        dataWeb.push(row);
      });
      this.tabelHandsonData = dataWeb;
      this.tabelHandsonMerged = mergedRow;
      this.tabelHandsonBorder = borderTabel;
    },
    getData() {
      return {
        data: this.tabelHandson.getData(),
        merged: this.tabelHandson.getPlugin("mergeCells").mergedCellsCollection
          .mergedCells
      };
    }
  }
};
</script>

<style></style>
