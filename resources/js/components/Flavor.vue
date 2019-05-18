<template>
  <v-container>
    <!-- <v-btn small color="success" @click="createFlavor">Create</v-btn> -->
    <v-data-table :headers="headerTable" :items="flavors" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Instance:(</v-alert>
      </template>
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.disk }} GB</td>
        <td>{{ props.item.ram }} MB</td>
        <td>{{ props.item.vcpus }} bits</td>
      </template>
    </v-data-table>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      headerTable: [
        { text: "Name", value: "name" },
        { text: "Id", sortable: false, value: "id" },
        { text: "Disk", value: "disk" },
        { text: "Ram", value: "ram" },
        { text: "VCpus", value: "vcpus" }
      ],
      flavors: [],
    };
  },
  methods: {
    getVolumes() {
      axios
        .post("api/getFlavors", {token:this.$store.state.token})
        .then(response => {
          console.log(response.data.flavors);
          this.flavors = response.data.flavors;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },
    // createFlavor() {
    //   let flavor = 
    //   {
    //     token: this.$store.state.token,
    //     name: "teste",
    //     descricao: "opcional",
    //     id:"stringOpcinal",
    //     ram: 1024,
    //     disk: 1,
    //     vcpus: 1,

    //   };
    //   console.log('flavor enviado: ', flavor);
    //   axios
    //     .post("api/createFlavor", flavor)
    //     .then(response => {
    //       console.log(response.data);
    //     })
    //     .catch(error => {
    //       this.$store.commit("showError", "Erro a criar flavor");
    //     });
    // }
  },
  mounted() {
    this.getVolumes();
  }
};
</script>
