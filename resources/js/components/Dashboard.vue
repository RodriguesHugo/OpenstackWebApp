<template>
  <v-navigation-drawer class="blue lighten-3" permanent app fixed clipped dark>
    <v-list>
      <v-list-tile v-for="item in items" :key="item.title" :to="item.route" exact>
        <v-list-tile-action>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  data() {
    return {
      items: [
        { title: "Instance", icon: "computer", route: { name: "instance" } },
        { title: "Images", icon: "check_box_outline_blank", route: { name: "images" } },
        { title: "Volumes", icon: "disc_full", route: { name: "volume" } },
        { title: "Flavors", icon: "gavel", route: { name: "flavor" } }
      ]
    };
  }
};
</script>
