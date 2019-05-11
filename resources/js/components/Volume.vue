<template>
  <v-container>
    <v-data-table :headers="volumes" :items="desserts" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Volumes :(</v-alert>
      </template>
    </v-data-table>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      volumes: [
        {
          text: "Id",
          sortable: false,
          value: "id"
        },
        { text: "Status", value: "status" },
        { text: "Volume Type", value: "volumeType" },
        { text: "CreatedAt", value: "createdAt" },
        { text: "Size", value: "size" }
      ],
      desserts: [],
      token: ""
    };
  },
  methods: {
    getVolumes() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getVolumes", token)
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    }
  },
  mounted() {
    this.getVolumes();
  }
};
</script>
