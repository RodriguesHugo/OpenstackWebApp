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
          <v-select v-model="selectedProj" :items="projects" label="Projects"></v-select>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" flat="flat" @click="cancel()">Cancel</v-btn>
            <v-btn
              color="green darken-1"
              flat="flat"
              @click="loginWithProject(selectedProj)"
            >Select Project</v-btn>
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
      projects: [],
      selectedProj: ""
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
          this.projects = response.data.map(proj => ({
            text: proj.name,
            value: proj.id
          }));
          this.dialog = true;
        })
        .catch(error => {
          this.$store.commit("showError", "Login unsuccessfull");
          console.log(error);
        });
    },
    cancel() {
      this.dialog = false;
      this.name = "";
      this.password = "";
    },
    loginWithProject(projId) {
      let credenciais = {
        name: this.name,
        password: this.password,
        projectId: projId
      };
      axios.post("api/loginWithScope", credenciais).then(response => {
        this.$store.commit("setToken", response.data);
        this.$store.commit("setProjId", projId);
        this.$store.commit("setUserLoged", this.name);
        this.$store.commit("showSuccess", "Login successfull");
        this.dialog = false;
      });
    }
  }
};
</script>
