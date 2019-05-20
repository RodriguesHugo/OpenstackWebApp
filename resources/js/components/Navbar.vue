<template>
  <v-toolbar dark color="primary" app fixed clipped-left>
    <v-toolbar-title class="white--text">OpenStack Web Application</v-toolbar-title>

    <v-spacer></v-spacer>
    <v-toolbar-items>
      <v-btn flat :to="'/'" @click="logout" v-show="this.$store.state.token == ''">Login</v-btn>
      <v-btn flat @click="logout" v-show="this.$store.state.token != ''">Logout</v-btn>
    </v-toolbar-items>
  </v-toolbar>
</template>
<script>
export default {
  methods: {
    logout() {
      this.$store.commit("clearToken");
      this.$router.push({name:"login"});
    }
  },
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
