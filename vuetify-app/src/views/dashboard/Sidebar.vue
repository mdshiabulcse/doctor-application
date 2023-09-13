<!--<template>-->
<!--    <router-link :to="{name:'user.dashboard'}">-->
<!--      <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>-->
<!--    </router-link>-->
<!--    <router-link :to="{name:'user.profile'}">-->
<!--      <v-list-item prepend-icon="mdi-account" title="My Account" value="profile"></v-list-item>-->
<!--    </router-link>-->
<!--    <router-link :to="{name:'doctor.list'}">-->
<!--      <v-list-item prepend-icon="mdi-doctor" title="Doctor List" value="doctor"></v-list-item>-->
<!--    </router-link>-->
<!--    <router-link v-for="item in sidebarItems"-->
<!--                 :key="item.routeName"-->
<!--                 :to="`/${item.routeName}`"-->
<!--                 >-->
<!--      <v-list-item :prepend-icon="item.icon" :title="item.title" value="doctor"></v-list-item>-->
<!--    </router-link>-->
<!--</template>-->
<!-- Sidebar.vue -->



<!-- Sidebar.vue -->

<template>
    <v-list v-for="item in sidebarItems">
      <v-list-item
        :key="item.routeName"
        :to="`/${item.routeName}`"
        link
        @mouseenter="expandParent(item)"
        @mouseleave="collapseParent(item)"
      >
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <router-link
        v-if="item.expanded"
        v-for="childItem in item.children"
        :key="childItem.routeName"
        :to="`/${childItem.routeName}`"
        link
        class="expanded-child"
      >
        <v-list-item-icon>
          <v-icon>{{ childItem.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>{{ childItem.title }}</v-list-item-title>
        </v-list-item-content>
      </router-link>
    </v-list>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const sidebarItems = ref([
      {
        routeName: 'Home',
        icon: 'mdi-home',
        title: 'Home',
        expanded: false,
        children: [],
      },
      {
        routeName: '#',
        icon: 'mdi-folder',
        title: 'Parent',
        expanded: false,
        children: [
          {
            routeName: 'Child',
            icon: 'mdi-file',
            title: 'Child',
          },
          {
            routeName: 'Child',
            icon: 'mdi-file',
            title: 'Child',
          },
        ],
      },
      {
        routeName: '#',
        icon: 'mdi-folder',
        title: 'Parent',
        expanded: false,
        children: [
          {
            routeName: 'Child',
            icon: 'mdi-file',
            title: 'Child',
          },
          {
            routeName: 'Child',
            icon: 'mdi-file',
            title: 'Child',
          },
        ],
      },
    ]);

    const expandParent = (parentItem) => {
      parentItem.expanded = true;
    };

    const collapseParent = (parentItem) => {
      parentItem.expanded = false;
    };

    return {
      sidebarItems,
      expandParent,
      collapseParent,
    };
  },
};
</script>

<style scoped>
/* Customize the appearance of expanded child items */
.v-list-item.expanded-child {
  background-color: #f0f0f0; /* Background color for expanded child items */
  padding-left: 48px; /* Adjust the indentation for child items */
}

/* Add a transition effect for smooth expansion/collapse */
.v-list-item.expanded-child-enter-active,
.v-list-item.expanded-child-leave-active {
  transition: all 0.3s;
}
.v-list-item.expanded-child-enter,
.v-list-item.expanded-child-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
