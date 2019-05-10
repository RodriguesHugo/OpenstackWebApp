<template>
  <v-toolbar dark color="primary" app fixed clipped-left>
    <v-toolbar-title class="white--text">OpenStack Web Application</v-toolbar-title>

    <v-spacer></v-spacer>
    <v-toolbar-items>
      <v-btn flat :to="'/exemplo'">Logout</v-btn>
    </v-toolbar-items>
    
  </v-toolbar>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
