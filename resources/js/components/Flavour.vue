<template>
  <v-container>
    <v-btn small color="success" @click="createFlavour">Success</v-btn>
    <v-data-table :headers="headerTable" :items="flavours" class="elevation-1">
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
      flavours: [],
      token: ""
    };
  },
  methods: {
    getVolumes() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getFlavours", token)
        .then(response => {
          console.log(response.data.flavours);
          this.flavours = response.data.flavours;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },
    createFlavour(){
      let flavour = {};
      console.log("PotatoFonso");
      axios.post("api/createFlavour", flavour).then(response => {

      }).catch(error => {
          this.$store.commit("showError", "Erro a criar flavour");
          this.$store.commit("clearToken");
      });
    }
  },
  mounted() {
    this.getVolumes();
  }
};
</script>
