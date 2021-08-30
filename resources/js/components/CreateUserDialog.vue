<template>
  <v-dialog v-model="dialog" max-width="500px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
        Create new User
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="text-h5">{{ formTitle }}</span>
      </v-card-title>

      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.email"
                  label="email"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.password"
                  type="password"
                  label="Password"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :loading="loading" color="blue darken-1" text @click="close">
          Cancel
        </v-btn>
        <v-btn :loading="loading" color="blue darken-1" text @click="save">
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import api from "../api";

export default {
  props: {
    item: {
      type: Object,
    },
  },
  data() {
    return {
      valid: true,
      loading: false,
      dialog: false,
      editedItem: {
        id: null,
        name: "",
        email: "",
        password: "",
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedItem.id === null ? "Create new User" : "Edit User";
    },
  },
  methods: {
    async createNewUser() {
      if (this.$refs.form.validate()) {
        const data = {
          name: this.editedItem.name,
          email: this.editedItem.email,
          password: this.editedItem.password,
        };

        return api.post("api/users", data).then((res) => res.data);
      }
    },
    async updateUser() {
      let data = {
        name: this.editedItem.name,
        email: this.editedItem.email,
      };

      if (this.editedItem.password.length > 0) {
        data["password"] = this.editedItem.password;
      }

      return api
        .patch(`/api/users/${this.editedItem.id}`, data)
        .then((res) => res.data);
    },
    async save() {
      try {
        this.loading = true;
        let user = null;
        if (this.editedItem.id == null) {
          user = await this.createNewUser();
        } else {
          user = await this.updateUser();
        }

        console.log("user", user);
        if (user === null) throw new Error("Sometink went wrong");

        this.$emit("newItem", user);
        this.close();
      } catch (error) {
        this.loading = false;
        this.$swal({
          icon: "error",
          title: "whoops",
          text: error,
        });
      }
    },
    close() {
      this.dialog = false;
      this.loading = false;
    },
  },
  watch: {
    dialog(newValue) {
      if (!newValue) {
        this.editedItem = {
          id: null,
          name: "",
          email: "",
          password: "",
        };
      }
    },
    item(newValue, oldValue) {
      if (newValue !== null) {
        this.dialog = true;
        this.editedItem = this.item;
      }
    },
  },
};
</script>

<style>
</style>