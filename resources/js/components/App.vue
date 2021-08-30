<template>
  <!-- App.vue -->

  <v-app>
    <navigation v-model="drawer"></navigation>
    <v-app-bar app height="60">
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <user-icon></user-icon>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-main>
      <!-- Provides the application the proper gutter -->
      <router-view></router-view>
    </v-main>

    <v-footer app>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
import Navigation from "./Navigation.vue";
import UserIcon from "./UserIcon.vue";

import { mapState, mapActions } from "vuex";
export default {
  components: {
    Navigation,
    UserIcon,
  },
  data() {
    return {
      drawer: true,
    };
  },
  computed: {
    ...mapState(["sessionToken"]),
  },
  async beforeMount() {
    await this.initStore();
    if (this.sessionToken === null && this.$route.name != "login") {
      this.$router.replace("/login");
    }
  },
  methods: {
    ...mapActions(["initStore"]),
  },
};
</script>

<style>
</style>