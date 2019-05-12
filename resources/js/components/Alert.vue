<template>
    <v-snackbar
        v-model="snackbar"
        :color="color"
        :multi-line="mode === 'multi-line'"
        :timeout="timeout"
        :vertical="mode === 'vertical'"
      >
        {{ text }}
        <v-btn
          dark
          flat
          @click="snackbar = false"
        >
          Close
        </v-btn>
    </v-snackbar>
</template>
<script>
export default {
  data() {
    return {  
      timeout: 6000,
      mode: '',
    };
  },
  computed:{
    snackbar:{
      get(){
        return this.$store.state.alert.snackbar;
      },
      set(){
        return ;
      }
    },
    color(){
      return this.$store.state.alert.color;
    },
    text(){
      return this.$store.state.alert.text;
    }
  }
};
</script>

