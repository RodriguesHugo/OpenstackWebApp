<template>
  <v-container>
    <v-btn small color="success" @click="createVolume()">Create</v-btn>

    <v-data-table :headers="volumes" :items="desserts" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Volumes :(</v-alert>
      </template>
      <template v-slot:items="props">
        <td>{{ props.item.displayName }}</td>
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.status }}</td>
        <td>{{ props.item.volumeType }}</td>
        <td>{{ props.item.createdAt }}</td>
        <td>{{ props.item.size }} GiB</td>
      </template>
    </v-data-table>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      volumes: [
        { text: "Name", value: "displayName" },
        { text: "Id", sortable: false, value: "id" },
        { text: "Status", value: "status" },
        { text: "Volume Type", value: "volumeType" },
        { text: "CreatedAt", value: "createdAt" },
        { text: "Size", value: "size" },
        { text: "Actions", value: "action" }
      ],
      desserts: [],
      token: ""
    };
  },
  methods: {
    createVolume() {
      let token = {
        token: this.$store.state.token,
        userLoginName: this.$store.state.userLoged
      };
      axios
        .post("api/createVolume", token)
        .then(response => {
          console.log(response.data.volumes);
          this.desserts = response.data.volumes;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },

    getVolumes() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getVolumes", token)
        .then(response => {
          console.log(response.data.volumes);
          this.desserts = response.data.volumes;
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
