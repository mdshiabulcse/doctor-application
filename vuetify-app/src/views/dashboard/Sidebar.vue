<template>
  <v-list dense>
    <v-list-item v-for="item in items" :key="item.text" :to="item.route" router>
      <v-list-item-icon>
        <v-icon>{{ item.icon }}</v-icon>
      </v-list-item-icon>
      <v-list-item-content>
        <v-list-item-title>{{ item.text }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-list-group
      v-for="item in items"
      v-if="item.children"
      :key="item.text"
      :prepend-icon="item.icon"
      no-action
    >
      <template v-slot:activator>
        <v-list-item-content>
          <v-list-item-title>{{ item.text }}</v-list-item-title>
        </v-list-item-content>
      </template>

      <v-list-item
        v-for="child in item.children"
        :key="child.text"
        :to="child.route"
        router
      >
        <v-list-item-content>
          <v-list-item-title>{{ child.text }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-group>
  </v-list>
</template>

<script>
export default {
  props: {
    items: Array,
  },
};
</script>
