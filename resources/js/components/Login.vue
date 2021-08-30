<template>
  <v-container fill-height fluid>
    <v-row align-content="center">
      <v-spacer></v-spacer>
      <v-col>
        <v-card>
          <v-toolbar color="primary" class="white--text">Login</v-toolbar>
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-text-field
                v-model="name"
                :rules="required"
                label="Account or Email"
                required
              ></v-text-field>

              <v-text-field
                v-model="password"
                :rules="required"
                type="password"
                label="Password"
                required
              ></v-text-field>
              <v-btn :loading="loading" color="primary" @click.prevent="submit">
                Submit
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
      <v-spacer></v-spacer>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data: () => ({
    loading: false,
    valid: true,
    name: "admin",
    password: "password",
    required: [(v) => !!v || "Field is required"],
  }),
  methods: {
    ...mapActions(["login"]),
    async submit() {
      if (this.$refs.form.validate()) {
        try {
          this.loading = true;
          await this.login({ username: this.name, password: this.password });

          this.$swal({
            title: "Success",
            text: "Login was successful. redirecting to Dashboard",
            toast: true,
            timer: 3000,
            position: "top-end",
            icon: "success",
          });
          this.$router.push("dashboard");
        } catch (error) {
          this.$swal({
            title: "Oh no!",
            text: "Login failed. Check your credentials",
            icon: "error",
          });
          console.log(error);
        } finally {
          this.loading = false;
        }
      }
    },
  },
};
</script>

<style>
</style>