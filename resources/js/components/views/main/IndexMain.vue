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
            v-for="item in publikasiItems"
            :key="item.title"
            link
            :to="item.link"
          >
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list dense>
          <v-list-item
            v-for="item in tabelItems"
            :key="item.title"
            link
            :to="item.link"
          >
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list dense>
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

      <v-app-bar app dense color="blue">
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
      </v-app-bar>

      <v-main>
        <v-container fluid>
          <v-row align="start" justify="center">
            <v-col class="text-center">
              <router-view class="main-view" name="MainView"></router-view>
            </v-col>
          </v-row>
        </v-container>
      </v-main>

      <v-footer color="blue" app>
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
export default {
  data: () => ({
    publikasiItems: [
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
    tabelItems: [
      {
        title: `Tabel ${new Date().getFullYear()}`,
        icon: "mdi-table",
        link: "/tabel"
      },
      { title: "Daftar Tabel", icon: "mdi-table-large", link: "/tabelAll" }
    ],
    drawer: true,
    currentUser: {},
    token: localStorage.getItem("token"),
    linkFoto: "",
    thisYear: new Date().getFullYear(),
    snackbar: true
  }),
  methods: {
    getUser() {
      axios
        .get("/api/v1/user")
        .then(response => {
          this.currentUser = response.data;
          this.linkFoto =
            "https://ui-avatars.com/api/?name=" +
            this.currentUser.nama_bidang +
            "&rounded=true";
        })
        .catch(error => console.log(error));
    },
    logout() {
      axios
        .post("/api/v1/logout")
        .then(response => {
          this.$router.push("/login");
        })
        .catch(error => console.log(error));
    }
  },
  created() {
    axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
    this.getUser();
  }
};
</script>
