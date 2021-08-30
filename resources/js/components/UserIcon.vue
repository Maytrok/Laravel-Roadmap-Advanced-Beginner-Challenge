<template>
  <span v-if="!loading">
    <v-btn v-if="!user" icon> <v-icon>mdi-account-outline</v-icon></v-btn>
    <v-avatar class="white--text" size="35" v-else color="primary">{{
      user.name.substr(0, 1).toUpperCase()
    }}</v-avatar>
  </span>
  <span v-else>
    <v-progress-circular indeterminate color="primary"></v-progress-circular>
  </span>
</template>

<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(["user", "loading"]),
  },
};
</script>

<style>
</style>