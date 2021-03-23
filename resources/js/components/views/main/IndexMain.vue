<template>
  <div id="app">
    <v-app id="inspire">
      <v-navigation-drawer expand-on-hover v-model="drawer" app>
        <v-list-item dense two-line>
          <v-list-item-avatar size="32">
            <img v-bind:src="linkFoto" />
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>{{ currentUser.nama_bidang }}</v-list-item-title>
            <v-list-item-subtitle>{{ currentUser.role }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>
        <v-list dense>
          <v-list-item link to="/dashboard">
            <v-list-item-action>
              <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Dashboard</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list dense>
          <v-list-item
            v-for="item in menuPublikasiItems"
            :key="item.title"
            link
            :to="item.link"
          >
            <v-list-item-action>
              <v-badge
                v-if="item.link == '/publikasi'"
                color="blue"
                :content="publikasiCount"
                overlap
              >
                <v-icon>{{ item.icon }}</v-icon>
              </v-badge>
              <v-icon v-else>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.title }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list dense>
          <v-list-item
            v-for="item in menuTabelItems"
            :key="item.title"
            link
            :to="item.link"
          >
            <v-list-item-action>
              <v-badge
                v-if="item.link == '/tabelDinamis'"
                color="blue"
                :content="tabelDinamisCount"
                overlap
              >
                <v-icon>{{ item.icon }}</v-icon>
              </v-badge>
              <v-icon v-else>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list dense v-if="currentUser.role == 'ADMIN'">
          <v-list-item link to="/pekerjaan">
            <v-list-item-action>
              <v-icon>mdi-briefcase</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Pekerjaan</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link to="/account">
            <v-list-item-action>
              <v-icon>mdi-account</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Akun</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list dense v-if="currentUser.role == 'ADMIN'">
          <v-list-item link to="/log">
            <v-list-item-action>
              <v-icon>mdi-notebook</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Log Aktivitas</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link to="/trash">
            <v-list-item-action>
              <v-icon>mdi-trash-can</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Tempat Sampah</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <template v-slot:append>
          <div>
            <v-list>
              <v-list-item link @click="logout">
                <v-list-item-icon>
                  <v-icon>mdi-power</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                  <v-list-item-title>Logout</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </div>
        </template>
      </v-navigation-drawer>
      <v-navigation-drawer v-model="showNotif" app right>
        <notifikasi></notifikasi>
      </v-navigation-drawer>
      <v-app-bar app dense elevation="2">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title
          ><v-icon>mdi-bookshelf</v-icon>[APASI] Aplikasi Pembantu
          Diseminasi</v-toolbar-title
        >
        <v-spacer></v-spacer>

        <v-btn
          icon
          id="mode-switcher"
          @click="$vuetify.theme.dark = !$vuetify.theme.dark"
        >
          <v-icon>
            {{
              $vuetify.theme.dark ? "mdi-weather-night" : "mdi-weather-sunny"
            }}
          </v-icon>
        </v-btn>
        <v-btn icon @click="showNotif = !showNotif">
          <v-icon>
            {{ !showNotif ? "mdi-bell-ring-outline" : "mdi-bell-outline" }}
          </v-icon>
        </v-btn>
      </v-app-bar>

      <v-main id="mainApp">
        <v-container fluid :fill-height="$route.name == 'Main'">
          <v-row align="start" justify="center" v-if="$route.name != 'Main'">
            <v-col class="text-center">
              <router-view class="main-view" name="MainView"></router-view>
            </v-col>
          </v-row>
          <v-row v-else align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
              <v-card class="elevation-12">
                <v-toolbar color="primary" flat>
                  <v-toolbar-title
                    >Aplikasi Pembantu Diseminasi</v-toolbar-title
                  >
                  <v-spacer></v-spacer>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn icon large target="_blank" v-on="on">
                        <v-icon>mdi-bookshelf</v-icon>
                      </v-btn>
                    </template>
                    <span>[APASI]</span>
                  </v-tooltip>
                </v-toolbar>
                <v-card-text class="text-center">
                  <div class="text-h1">
                    <span> <v-icon size="80"> mdi-emoticon </v-icon> </span>
                  </div>
                  <div class="text-h6 mb-5">
                    Selamat Datang di APASI
                  </div>
                  <v-divider></v-divider>
                  <v-btn
                    color="primary"
                    small
                    class="mt-5"
                    href="/tabelDinamis"
                  >
                    <v-icon>
                      mdi-table
                    </v-icon>
                    Tabel Dinamis
                  </v-btn>
                  <v-btn color="primary" small class="mt-5" href="/publikasi">
                    <v-icon>
                      mdi-book
                    </v-icon>
                    Publikasi
                  </v-btn>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-main>

      <v-footer app elevation="2">
        <v-row align="center" justify="center">
          <v-col class="text-center">
            <span
              ><span> {{ thisYear }} </span> © Made by
              <a
                href="https://github.com/blankon123/"
                style="text-decoration: none;color: rgb(233 255 48)"
              >
                blankon123
              </a>
              with 👶</span
            >
          </v-col>
        </v-row>
      </v-footer>
    </v-app>
  </div>
</template>

<script>
import Notifikasi from "../components/index/Notifikasi.vue";
export default {
  components: { Notifikasi },
  data: () => ({
    menuPublikasiItems: [
      {
        title: `Publikasi ${new Date().getFullYear()}`,
        icon: "mdi-book-open-variant",
        link: "/publikasi"
      },
      {
        title: "Daftar Publikasi",
        icon: "mdi-book-open",
        link: "/publikasiAll"
      }
    ],
    menuTabelItems: [
      { title: "Tabel Dinamis", icon: "mdi-table-large", link: "/tabelDinamis" }
    ],
    drawer: true,
    showNotif: false,
    thisYear: new Date().getFullYear()
  }),
  methods: {
    logout() {
      axios
        .post("/api/v1/logout")
        .then(response => {
          localStorage.removeItem("apasi_cred");
          this.$router.push("/login");
        })
        .catch(error => console.log(error));
    }
  },
  computed: {
    publikasiCount() {
      return this.$store.state.indexMainStore.publikasiCount;
    },
    tabelDinamisCount() {
      return this.$store.state.indexMainStore.tabelDinamisCount;
    },
    currentUser: {
      get() {
        return this.$store.state.userStore.user;
      }
    },
    linkFoto: {
      get() {
        if (this.currentUser.nama_bidang) {
          return (
            "https://ui-avatars.com/api/?name=" +
            this.currentUser.nama_bidang +
            "&rounded=true"
          );
        }
      }
    }
  },
  created() {}
};
</script>

<style lang="scss">
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 6px rgba(14, 218, 92, 0);
  background-color: rgba(14, 218, 92, 0);
}

::-webkit-scrollbar {
  width: 5px;
  height: 5px;
  background-color: rgba(14, 218, 92, 0);
}

::-webkit-scrollbar-thumb {
  border-radius: 5px;
  background-image: -webkit-gradient(
    linear,
    left bottom,
    left top,
    color-stop(0.44, rgb(122, 153, 217)),
    color-stop(0.72, rgb(73, 125, 189)),
    color-stop(0.86, rgb(28, 58, 148))
  );
}
</style>
