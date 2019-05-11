<template>
  <v-container>
    <v-flex md4>
      <form>
        <v-text-field v-model="email" label="E-mail" required></v-text-field>
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
      email: "",
      password: ""
    };
  },
  methods: {
    login() {
      let credenciais = {
        mail: this.email,
        password: this.password
      };
      console.log(credenciais);
      axios
        .get("api/login", credenciais)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    }
  }
};
</script>
