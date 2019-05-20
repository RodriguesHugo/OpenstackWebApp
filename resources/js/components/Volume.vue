<template>
  <v-container>
    <v-layout row>
      <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
          <v-btn color="success" v-on="on">Create</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Volume Create</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md6>
                  <v-text-field label="Name" v-model="volumeName" required></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-text-field label="Size" v-model="volumeSize" required></v-text-field>
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
        <td>
          <v-btn small color="error darken-1" @click="deleteVolume(props.item.id)">Delete</v-btn>
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
      volumeName: "",
      volumeSize: "",
      dialog: false,
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
    deleteVolume($id) {
      let token = {
        token: this.$store.state.token,
        projectId: this.$store.state.projId,
        volumeId: $id
      };
      axios
        .post("api/deleteVolume", token)
        .then(response => {
          console.log(response);
          this.$store.commit("showSuccess", response.data);
          setTimeout(() => {
            this.getVolumes();
          }, 2000);
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },
    createVolume() {
      let token = {
        token: this.$store.state.token,
        projectId: this.$store.state.projId,
        volumeName: this.volumeName,
        volumeSize: this.volumeSize
      };
      axios
        .post("api/createVolume", token)
        .then(response => {
          this.dialog = false;
          this.$store.commit("showSuccess", "Volume Created");
          setTimeout(() => {
            this.getVolumes();
          }, 2000);
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
