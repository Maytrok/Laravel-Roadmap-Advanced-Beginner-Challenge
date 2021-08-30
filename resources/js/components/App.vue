<template>
  <!-- App.vue -->

  <v-app>
    <navigation v-model="drawer"></navigation>
    <v-app-bar app height="60">
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-btn icon> <v-icon>mdi-account-outline</v-icon></v-btn>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-main>
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- <router-view></router-view> -->
      </v-container>
    </v-main>

    <v-footer app>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
import Navigation from "./Navigation.vue";
export default {
  components: {
    Navigation,
  },
  data() {
    return {
      drawer: true,
    };
  },
};
</script>

<style>
</style>