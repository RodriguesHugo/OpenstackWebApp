<template>
  <v-container>
    <v-layout row>
      <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
          <v-btn color="success" v-on="on">Create</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Create a new instance</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field v-model="instanceData.name" label="Name" required></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-overflow-btn
                    v-model="instanceData.imageId"
                    :items="images"
                    label="Select an image"
                    target="#dropdown-example"
                  ></v-overflow-btn>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-overflow-btn
                    v-model="instanceData.flavorId"
                    :items="flavors"
                    label="Select a flavor"
                    target="#dropdown-example"
                  ></v-overflow-btn>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-overflow-btn
                    v-model="instanceData.networkId"
                    :items="networks"
                    label="Select a network"
                    target="#dropdown-example"
                  ></v-overflow-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click="createInstance()">Create</v-btn>
            <v-btn color="blue darken-1" flat @click="dialog = false">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-data-table :headers="instancesHeaders" :items="instances" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Instance:(</v-alert>
      </template>
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.status }}</td>
        <td>{{ props.item.flavor.id }}</td>
        <td>
          <v-btn small color="error darken-1" @click="deleteInstance(props.item.id)">Delete</v-btn>
        </td>
      </template>
    </v-data-table>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      instancesHeaders: [
        { text: "Name", value: "name" },
        { text: "Status", value: "status" },
        { text: "Flavour", value: "flavor.id" },
        { text: "Actions", value: "id" }
      ],
      instances: [],
      dialog: false,
      instanceData: {
        name: "",
        imageId: "",
        flavorId: "",
        networkId: ""
      },
      images: [],
      flavors: [],
      networks: []
    };
  },
  methods: {
    deleteInstance(instaceId){
      console.log(instaceId);
    },
    createInstance() {
      let credentials = {
        token: this.$store.state.token,
        name: this.instanceData.name,
        imageId: this.instanceData.imageId,
        flavorId: this.instanceData.flavorId,
        networkId: this.instanceData.networkId
      };
      console.log(credentials);
      axios
        .post("api/createInstance", credentials)
        .then(response => {
          this.$store.commit(
            "showSuccess",
            credentials.name + " instace created"
          );
          this.dialog = false;
          this.getInstances();
        })
        .catch(error => {
          console.log(error);
        });
    },
    getInstances() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getInstances", token)
        .then(response => {
          this.instances = response.data.servers;
          console.log(this.instances);
        })
        .catch(error => {
          this.$store.commit("showError", "Error geting instances");
        });
    },
    getFlavorsAndImagesAndNetworks() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getFlavors", token)
        .then(response => {
          this.flavors = response.data.flavors.map(flavor => ({
            text: flavor.name,
            value: flavor.id
          }));
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
      axios
        .post("api/getImage", token)
        .then(response => {
          this.images = response.data.images.map(image => ({
            text: image.name,
            value: image.id
          }));
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
      axios
        .post("api/getNetworks", token)
        .then(response => {
          this.networks = response.data.networks.map(network => ({
            text: network.name,
            value: network.id
          }));
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    }
  },
  mounted() {
    this.getInstances();
    this.getFlavorsAndImagesAndNetworks();
  }
};
</script>
