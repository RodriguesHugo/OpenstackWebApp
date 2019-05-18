<template>
  <v-container>
    <v-btn small color="success" @click="createInstance">Create</v-btn>
    <v-data-table :headers="instances" :items="desserts" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Instance:(</v-alert>
      </template>
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.status }}</td>
        <td>{{ props.item.size }} bits</td>
      </template>
    </v-data-table>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      instances: [
        { text: "Name", value: "name" },
        { text: "Id", sortable: false, value: "id" },
        { text: "Status", value: "status" },
        { text: "Size", value: "size" }
      ],
      desserts: [],
      token: ""
    };
  },
  methods: {
    createInstance() {},
    getVolumes() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getImage", token)
        .then(response => {
          console.log(response.data.images);
          this.desserts = response.data.images;
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
