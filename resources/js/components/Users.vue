<template>
  <v-card class="mt-10" :loading="loading">
    <v-card-title>
      <create-user-dialog
        @newItem="updateListe"
        :item="editItem"
      ></create-user-dialog>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-1 mt-5"
        :items-per-page="15"
        :search="search"
      >
        <template v-slot:item.action="{ item }">
          <v-btn @click="editItem = Object.assign({}, item)" icon
            ><v-icon>mdi-account-edit</v-icon></v-btn
          >
          <v-btn @click="deleteUserDialog(item.id)" icon
            ><v-icon>mdi-delete</v-icon></v-btn
          >
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
import CreateUserDialog from "./CreateUserDialog.vue";

import api from "../api";

export default {
  components: {
    CreateUserDialog,
  },
  data() {
    return {
      items: [],
      headers: [
        {
          text: "id",
          value: "id",
          width: "5%",
        },
        {
          text: "name",
          value: "name",
          width: "40%",
        },
        {
          text: "email",
          value: "email",
        },
        {
          text: "action",
          value: "action",
          sortable: false,
          width: "10%",
        },
      ],
      loading: false,
      search: "",
      editItem: null,
    };
  },
  beforeMount() {
    this.loading = true;
    api.get("api/users").then((res) => {
      this.items = res.data;
      this.loading = false;
    });
  },
  methods: {
    updateListe(item) {
      const index = this.items.findIndex((i) => i.id == item.id);
      console.log(index);
      if (index != -1) {
        this.items.splice(index, 1, item);
      } else {
        this.items.push(item);
      }
    },
    deleteUserDialog(id) {
      this.$swal({
        title: "Do you realy want to delete this user?",
        showDenyButton: true,
        confirmButtonText: `Sure`,
        denyButtonText: `Don't do it`,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          this.deleteUser(id);
        }
      });
    },
    deleteUser(id) {
      this.loading = true;
      api
        .delete(`/api/users/${id}`)
        .catch((e) => {
          this.$swal({
            icon: "error",
            title: "oh no!",
            text: e.response.data.message,
          });
          this.loading = false;
        })
        .then((res) => this.removeUser(id))
        .catch((e) => console.log(e));
    },
    removeUser(id) {
      const index = this.items.findIndex((i) => i.id == id);
      this.items.splice(index, 1);
      this.loading = false;
    },
  },
};
</script>

<style>
</style>