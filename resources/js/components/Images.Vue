<template>
  <v-container>
    <v-layout row>
      <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
          <v-btn color="success" v-on="on">Create</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Image Create</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md6>
                  <v-text-field label="Name" v-model="imageName" required></v-text-field>
                </v-flex>
                <v-form>
                  <v-flex xs12 row>
                    <v-flex xs8>
                      <v-text-field
                        v-model="image.filename"
                        label="System image file"
                        prepend-icon="attach_file"
                        readonly
                        disabled
                        required
                      ></v-text-field>
                      <input
                        type="file"
                        style="display: none"
                        ref="image"
                        accept=".iso, .raw, .cue, .dmg"
                        @change="onFilePicked"
                      >
                    </v-flex>

                    <v-flex xs4>
                      <v-btn flat color="blue darken-1" @click="pickFile">Choose file</v-btn>
                    </v-flex>
                  </v-flex>
                </v-form>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error darken-1" @click="dialog = false">Close</v-btn>
            <v-btn color="success darken-1" @click="uploadImageFile()">Upload</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
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
      image: {
        filename: null,
        file: null,
        url: null
      },
      imageName: "",
      dialog: false,
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
    createImage() {
      let token = {
        token: this.$store.state.token,
        imageName: this.imageName
      };
      axios
        .post("api/createImage", token)
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
        .post("api/getImage", token)
        .then(response => {
          console.log(response.data.images);
          this.desserts = response.data.images;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response.data.message);
        });
    },
    uploadImageFile() {
      this.image.uploading = true;
      const formData = new FormData();
      formData.append("file", this.image.file);
      formData.append("token", this.$store.state.token);
      formData.append("imageName", this.imageName);
      axios
        .post("/api/postImage", formData)
        .then(response => {
          this.dialog = false;
          // TODO: refactor and extract methods
          this.image.url = null;
          this.image.filename = null;
          this.image.file = null;
          this.getDataFromApi();
          this.showSnackBar("success", response.data.message);
        })
        .catch(error => {
          this.showSnackBar("error", error.response.data.message);
        })
        .finally(() => {
          this.image.uploading = false;
        });
    },
    pickFile() {
      this.$refs.image.click();
    },
    onFilePicked(e) {
      this.image.loading = true;
      const files = e.target.files;
      if (files[0] !== undefined) {
        this.image.filename = files[0].name;
        if (this.image.filename.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          this.image.url = fr.result;
          this.image.file = files[0]; // this is an image file that can be sent to server...
        });
        this.image.loading = false;
      } else {
        this.image.file = "";
        this.image.url = "";
      }
    }
  },
  mounted() {
    this.getVolumes();
  }
};
</script>
