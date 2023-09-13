<template>
  <v-app>
    <v-navigation-drawer app :mini-variant="mini">
      <v-list dense>
        <!-- Parent Menu 1 -->
        <v-list-item @click="toggleChildItems(0)">
          <v-list-item-content>
            <v-list-item-title>Parent Menu 1</v-list-item-title>
          </v-list-item-content>
          <v-list-item-action>
            <v-icon :style="`transform: rotate(${childItemsVisible[0] ? 90 : 0}deg);`">mdi-chevron-down</v-icon>
          </v-list-item-action>
        </v-list-item>
        <v-slide-y-transition>
          <v-list-item-group v-show="childItemsVisible[0]">
            <!-- Child Menu Items for Parent Menu 1 -->
            <v-list-item
              v-for="(childItem, index) in childItems[0]"
              :key="index"
              :to="childItem.route"
              exact
              @click="hideChildItems(0)"
            >
              <v-list-item-content>
                <v-list-item-title>{{ childItem.title }}</v-list-item-title>
              </v-list-item-content>
              <v-list-item-action>
                <v-icon>{{ childItem.icon }}</v-icon>
              </v-list-item-action>
            </v-list-item>
          </v-list-item-group>
        </v-slide-y-transition>

        <!-- Parent Menu 2 -->
        <v-list-item @click="toggleChildItems(1)">
          <v-list-item-content>
            <v-list-item-title>Parent Menu 2</v-list-item-title>
          </v-list-item-content>
          <v-list-item-action>
            <v-icon :style="`transform: rotate(${childItemsVisible[1] ? 90 : 0}deg);`">mdi-chevron-down</v-icon>
          </v-list-item-action>
        </v-list-item>
        <v-slide-y-transition>
          <v-list-item-group v-show="childItemsVisible[1]">
            <!-- Child Menu Items for Parent Menu 2 -->
            <v-list-item
              v-for="(childItem, index) in childItems[1]"
              :key="index"
              :to="childItem.route"
              exact
              @click="hideChildItems(1)"
            >
              <v-list-item-content>
                <v-list-item-title>{{ childItem.title }}</v-list-item-title>
              </v-list-item-content>
              <v-list-item-action>
                <v-icon>{{ childItem.icon }}</v-icon>
              </v-list-item-action>
            </v-list-item>
          </v-list-item-group>
        </v-slide-y-transition>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar app>
      <v-app-bar-nav-icon @click="toggleSidebar"></v-app-bar-nav-icon>
      <v-toolbar-title>My App</v-toolbar-title>
    </v-app-bar>
    <v-main>
      <!-- Your main content here -->
    </v-main>
  </v-app>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const mini = ref(false);

    const childItemsVisible = ref([false, false]);

    const childItems = [
      [
        { title: 'Child 1', icon: 'mdi-account', route: '/child1' },
        { title: 'Child 2', icon: 'mdi-settings', route: '/child2' },
        { title: 'Child 3', icon: 'mdi-apps', route: '/child3' },
      ],
      [
        { title: 'Child 4', icon: 'mdi-account', route: '/child4' },
        { title: 'Child 5', icon: 'mdi-settings', route: '/child5' },
        { title: 'Child 6', icon: 'mdi-apps', route: '/child6' },
      ],
    ];

    function toggleSidebar() {
      mini.value = !mini.value;
    }

    function toggleChildItems(parentIndex) {
      childItemsVisible.value[parentIndex] = !childItemsVisible.value[parentIndex];
    }

    function hideChildItems(parentIndex) {
      childItemsVisible.value[parentIndex] = false;
    }

    return {
      mini,
      toggleSidebar,
      childItemsVisible,
      toggleChildItems,
      hideChildItems,
      childItems,
    };
  },
};
</script>

<style scoped>
/* Rotate the chevron icon */
.v-icon.mdi-chevron-down {
  transition: transform 0.3s ease-in-out;
}

.v-icon.mdi-chevron-down.rotate-90 {
  transform: rotate(90deg);
}

/* Add a margin to child items for spacing */

  /* Add a margin to child items for spacing */
.v-list-item-group > .v-list-item {
  margin-left: 16px;
}

/* Hide child items initially */
.v-list-item-group:not(.v-enter) {
  display: none !important;
}
</style>
