<template>
  <v-container>
    <v-flex md4>
      <form>
        <v-text-field v-model="name" label="Username" required></v-text-field>
      </form>
    </v-flex>
    <v-flex md4>
      <form>
        <v-text-field v-model="password" label="Password" type="password" required></v-text-field>
        <v-btn color="success" @click="login">Login</v-btn>
      </form>
    </v-flex>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      name: "",
      password: ""
    };
  },
  methods: {
    login() {
      let credenciais = {
        name: this.name,
        password: this.password
      };

      axios
        .post("api/login", credenciais)
        .then(response => {
          this.$store.commit("setToken", response.data);
          this.$store.commit("setUserLoged", this.name);
          this.$store.commit("showSuccess", "Login successfull");

          console.log(this.$store.state.token);
        })
        .catch(error => {
          this.$store.commit("showError", "Login unsuccessfull");
          this.$store.commit("clearToken");
          console.log(error);
        });
    }
  }
};
</script>
