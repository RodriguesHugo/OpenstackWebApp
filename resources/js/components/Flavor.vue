<template>
  <v-container>
    <v-layout row>
      <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
          <v-btn color="primary" dark v-on="on">Create Flavor</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Create a new flavor</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field v-model="flavorData.name" label="Name" required></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="flavorData.ram" label="Ram (MB)"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="flavorData.disk" label="Number of disks" required></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="flavorData.vcpu" label="Number of cpu's" required></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click="createFlavor()">Create</v-btn>
            <v-btn color="blue darken-1" flat @click="dialog = false">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-data-table :headers="headerTable" :items="flavors" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Instance:(</v-alert>
      </template>
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.disk }} GB</td>
        <td>{{ props.item.ram }} MB</td>
        <td>{{ props.item.vcpus }}</td>
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
        { text: "VCpus", value: "vcpus" },
        { text: "Actions", value: "actionW" }

      ],
      flavors: [],
      dialog: false,
      flavorData: { name: '', ram: '', vcpu:'', disk:''}
    };
  },
  methods: {
    getFlavors() {
      axios
        .post("api/getFlavors", { token: this.$store.state.token })
        .then(response => {
          this.flavors = response.data.flavors;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },
    createFlavor() {
      let flavor =
      {
        token: this.$store.state.token,
        projectId: this.$store.state.projId,
        name: this.flavorData.name,
        ram: this.flavorData.ram,
        disk: this.flavorData.disk,
        vcpus: this.flavorData.vcpu,

      };
      axios
        .post("api/createFlavor", flavor)
        .then(response => {
          this.dialog = false;
          this.getFlavors();
        })
        .catch(error => {
          this.$store.commit("showError", "Erro a criar flavor");
        });
    }
  },
  mounted() {
    this.getFlavors();
  }
};
</script>
