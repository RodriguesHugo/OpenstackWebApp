<template>
  <v-toolbar dark color="primary" app fixed clipped-left>
    <v-toolbar-title class="white--text">OpenStack Web Application</v-toolbar-title>
    <v-chip
      v-if="this.$store.state.token != ''"
      label
    >Actual project: {{this.$store.state.proj.name}}</v-chip>
    <v-spacer></v-spacer>
    <v-toolbar-items>
      <v-select
        v-model="proj"
        :items="this.$store.state.projs"
        label="Project"
        v-if="this.$store.state.token != ''"
      ></v-select>
      <v-btn
        flat
        @click="changeScopedProject(proj)"
        v-show="this.$store.state.token != ''"
      >Change Project</v-btn>
      <v-btn flat :to="'/'" @click="logout" v-show="this.$store.state.token == ''">Login</v-btn>
      <v-btn flat @click="logout" v-show="this.$store.state.token != ''">Logout</v-btn>
    </v-toolbar-items>
  </v-toolbar>
</template>
<script>
export default {
  data() {
    return {
      proj: ""
    };
  },
  methods: {
    logout() {
      this.$store.commit("clearToken");
      this.$router.push({ name: "login" });
    },
    changeScopedProject(proj) {
      let credentials = {
        token: this.$store.state.token,
        projectId: proj.id
      };
      axios.post("api/changeProject", credentials).then(response => {
        this.$store.commit("setToken", response.data);
        this.$store.commit("showSuccess", "Project changed successfully");
        this.$store.commit("setProj", proj);
        this.$router.push({ name: "instance" });
      });
    }
  },
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
