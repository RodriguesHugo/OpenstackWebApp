<template>
  <v-container>
    <v-flex md4>
      <form>
        <v-text-field v-model="name" placeholder="Username" required></v-text-field>
      </form>
    </v-flex>
    <v-flex md4>
      <form>
        <v-text-field v-model="password" placeholder="Password" type="password" required></v-text-field>
      </form>
      <v-btn color="success" @click="login">Login</v-btn>
      <v-dialog v-model="dialog" max-width="290">
        <v-card>
          <v-overflow-btn :items="projects" label="Overflow Btn" target="#dropdown-example"></v-overflow-btn>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" flat="flat" @click="dialog = false">Disagree</v-btn>
            <v-btn color="green darken-1" flat="flat" @click="dialog = false">Agree</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-flex>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      name: "",
      password: "",
      dialog: false,
      projects: []
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
          // this.$store.commit("setToken", response.data);
          // this.$store.commit("setUserLoged", this.name);
          // this.$store.commit("showSuccess", "Login successfull");
          // console.log(this.$store.state.token);
          console.log(response.data);
          this.dialog = true;

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
