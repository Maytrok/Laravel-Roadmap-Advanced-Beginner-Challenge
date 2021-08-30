<template>
  <v-sheet height="400" class="overflow-hidden" style="position: relative">
    <v-navigation-drawer
      v-model="value"
      app
      class="blue-grey lighten-5 fill-height white--text"
    >
      <v-container fluid>
        <v-row style="height: 60px" class="blue-grey darken-4" align="center">
          <v-spacer></v-spacer><v-col>CRM</v-col><v-spacer></v-spacer>
        </v-row>
      </v-container>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item v-for="item in items" :key="item.title" link to="">
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </v-sheet>
</template>
<script>
export default {
  props: {
    value: {
      type: Boolean,
    },
  },
  data() {
    return {
      items: [
        { title: "Dashboard", icon: "mdi-view-dashboard", link: "/" },
        { title: "User", icon: "mdi-account", link: "/users" },
        {
          title: "Clients",
          icon: "mdi-account-group",
          link: "/clients",
        },
        { title: "Projects", icon: "mdi-content-copy", link: "/projects" },
        { title: "Tasks", icon: "mdi-format-list-checks", link: "/tasks" },
      ],
    };
  },
};
</script>