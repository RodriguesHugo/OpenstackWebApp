<template>
  <v-container>
    <v-layout row>
      <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
          <v-btn color="success" v-on="on">Add</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Add WebServer</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md6>
                  <v-text-field label="Name" v-model="volumeName" required></v-text-field>
                </v-flex>
                <v-flex>
                  <v-select
                    v-model="sizeSelected"
                    :items="sizes"
                    hide-details
                    label="Select Size"
                    single-line
                  ></v-select>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error darken-1" @click="dialog = false">Close</v-btn>
            <v-btn color="success darken-1" @click="createVolume()">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>

    <v-data-table :headers="headers" :items="volumes" class="elevation-1">
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">No Servers :(</v-alert>
      </template>
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.addresses.public[1].addr }}</td>
        <td>{{ props.item.status }}</td>
        <td>
          <v-btn small color="error darken-1" @click="deleteInstance(props.item.id)">Delete</v-btn>
        </td>
      </template>
    </v-data-table>
  </v-container>
</template>
<script>
import { setTimeout } from "timers";
export default {
  data() {
    return {
      sizeSelected: "",
      sizes: [{ text: "5 GB" }, { text: "10 GB" }, { text: "20 GB" }],
      volumeName: "",
      volumeSize: "",
      dialog: false,
      headers: [
        { text: "Name", value: "name" },
        { text: "Ipv4", value: "ipv4" },
        { text: "Status", value: "status" },
        { text: "Actions" }
      ],
      volumes: [],
      token: ""
    };
  },
  methods: {
    deleteInstance(instaceId) {
      let credentials = {
        token: this.$store.state.token,
        instanceId: instaceId
      };
      axios
        .post("api/deleteInstance", credentials)
        .then(response => {
          this.$store.commit("showSuccess", response.data);
          setTimeout(() => {
            this.getWebServer();
          }, 15000);
        })
        .catch(error => {
          this.$store.commit("showError", error.response.data.message);
          console.log(error.response.data.message);
        });
    },
    createVolume() {
      let token = {
        token: this.$store.state.token,
        projectId: this.$store.state.proj.id,
        sizeSelected: this.sizeSelected,
        serverName: this.volumeName
      };
      axios
        .post("api/createWebServer", token)
        .then(response => {
          this.dialog = false;
          this.$store.commit("showSuccess", "WebServer Created");
          setTimeout(() => {
            this.getWebServer();
          }, 15000);
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },

    getWebServer() {
      let token = {
        token: this.$store.state.token
      };
      axios
        .post("api/getWebServer", token)
        .then(response => {
          console.log(response.data);
          this.volumes = response.data;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    }
  },
  mounted() {
    this.getWebServer();
  }
};
</script>
