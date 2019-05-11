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
    props:['snackbar','color','text'],
  data() {
    return {      
        timeout: 6000,
        mode: '',
    };
  },
};
</script>

