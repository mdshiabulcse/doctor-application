<template>
  <v-list dense>
    <!-- Main Menu Items -->
    <router-link :to="{name:'user.dashboard'}">
      <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
    </router-link>
    <router-link v-if="isAdmin || isManager || isUser" :to="{name:'patient.info'}">
      <v-list-item prepend-icon="mdi-account-injury-outline" title="Patients" value="Patient Info"></v-list-item>
    </router-link>
    <router-link v-if="isAdmin || isManager || isUser" :to="{name:'appointment.data'}">
      <v-list-item prepend-icon="mdi-calendar-check-outline" title="Appointment" value="Appointment"></v-list-item>
    </router-link>

    <!-- Doctor Menu start-->
    <v-list-item v-if="isAdmin || isManager" @click="toggleChildItems(0)">
      <router-link to="">
        <v-list-item-action>
          <v-icon :style="`transform: rotate(${childItemsVisible[0] ? 90 : 0}deg);`">mdi-chevron-down</v-icon>
          <v-list-item title="Doctors"></v-list-item>
        </v-list-item-action>
      </router-link>
    </v-list-item>
    <v-slide-y-transition>
      <v-list-item v-show="childItemsVisible[0]">
        <!-- Child Menu Items for Doctors -->
        <v-list-item
          v-for="(childItem, index) in childItems[0]"
          :key="index"
          :to="childItem.route"
          exact
          @click="hideChildItems(0)" :prepend-icon="childItem.icon" :title="childItem.title" value="doctor">
        </v-list-item>
      </v-list-item>
    </v-slide-y-transition>

    <!-- Invoice Menu -->
    <v-list-item v-if="isAdmin || isManager || isUser" @click="toggleChildItems(1)">
     <router-link to="">
       <v-list-item-action>
         <v-icon :style="`transform: rotate(${childItemsVisible[1] ? 90 : 0}deg);`">mdi-chevron-down</v-icon>
         <v-list-item title="Invoice"></v-list-item>
       </v-list-item-action>
     </router-link>
    </v-list-item>
    <v-slide-y-transition>
      <v-list-item v-show="childItemsVisible[1]">
        <!-- Child Menu Items for Invoice -->
        <v-list-item
          v-for="(childItem, index) in childItems[1]"
          :key="index"
          :to="childItem.route"
          exact
          @click="hideChildItems(1)" :prepend-icon="childItem.icon" :title="childItem.title" value="Invoice">
        </v-list-item>
      </v-list-item>
    </v-slide-y-transition>

<!--    operation menu start -->
    <!-- Invoice Menu -->
    <v-list-item v-if="isAdmin || isManager || isUser" @click="toggleChildItems(2)">
      <router-link to="">
        <v-list-item-action>
          <v-icon :style="`transform: rotate(${childItemsVisible[1] ? 90 : 0}deg);`">mdi-chevron-down</v-icon>
          <v-list-item title="OT"></v-list-item>
        </v-list-item-action>
      </router-link>
    </v-list-item>
    <v-slide-y-transition>
      <v-list-item v-show="childItemsVisible[2]">
        <!-- Child Menu Items for Invoice -->
        <v-list-item
          v-for="(childItem, index) in childItems[2]"
          :key="index"
          :to="childItem.route"
          exact
          @click="hideChildItems(2)" :prepend-icon="childItem.icon" :title="childItem.title" value="Invoice">
        </v-list-item>
      </v-list-item>
    </v-slide-y-transition>
<!--    operation menu end -->

    <!-- Administrative Menu -->
    <v-list-item v-if="isAdmin || isManager" @click="toggleChildItems(3)">
     <router-link to="">
       <v-list-item-action>
         <v-icon :style="`transform: rotate(${childItemsVisible[3] ? 90 : 0}deg);`">mdi-chevron-down</v-icon>
         <v-list-item title="Administrative"></v-list-item>
       </v-list-item-action>
     </router-link>
    </v-list-item>
    <v-slide-y-transition>
      <v-list-item v-show="childItemsVisible[3]">
        <!-- Child Menu Items for Administrative -->
        <v-list-item
          v-for="(childItem, index) in childItems[3]"
          :key="index"
          :to="childItem.route"
          exact
          @click="hideChildItems(3)" :prepend-icon="childItem.icon" :title="childItem.title" value="admin">
        </v-list-item>
      </v-list-item>
    </v-slide-y-transition>

  </v-list>
</template>

<script>
import { ref } from 'vue';
import { useAuth } from "@/store/auth";

export default {
  setup() {
    const mini = ref(false);
    const childItemsVisible = ref([false, false, false, false]); // Extend visibility array
    const authData = useAuth();
    const childItems = [
      [
        { title: 'Doctor List', icon: 'mdi-plus', route: '/doctor-list' },
      ],
      [
        { title: 'Invoice List', icon: 'mdi-plus', route: '/invoice-list' },
      ],
      [
        { title: 'OT List', icon: 'mdi-plus', route: '/operation-list' },
      ],
      [
        { title: 'User Info', icon: 'mdi-plus', route: '/user-list' },
        { title: 'Patient Source', icon: 'mdi-plus', route: '/patient-source' },
        { title: 'Doctor List', icon: 'mdi-plus', route: '/doctor-list' },
      ],
    ];
    // ======== role system code start======== //
    let isAdmin = false;
    let isManager = false;
    let isUser = false;

    authData.user.data.user_group.forEach(group => {
      if (group.group_id === 1) {
        isAdmin = true;
      } else if (group.group_id === 2) {
        isManager = true;
      }
      else if (group.group_id === 3) {
        isUser = true;
      }
    });
// ======== role system code end======== //
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
      //======== role system user role return start=========//
      isAdmin,
      isManager,
      isUser,
      //======== role system user role return end=========//
    };
  },
};
</script>

<style scoped>
/* Rotate the chevron icon */
.v-icon.mdi-chevron-down {
  transition: transform 0.03s ease-in-out;
}

.v-icon.mdi-chevron-down.rotate-90 {
  transform: rotate(90deg);
}

/* Add a margin to child items for spacing */
.v-list-item-group > .v-list-item {
  margin-left: 16px;
}

/* Hide child items initially */
.v-list-item-group:not(.v-enter) {
  display: none !important;
}
</style>
