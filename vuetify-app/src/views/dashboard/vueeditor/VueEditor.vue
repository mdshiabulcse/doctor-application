// MyEditor.vue
<template>
  <v-app>
    <v-main>
      <v-container>
        <v-card>
          <v-card-title>Edit Content</v-card-title>
          <v-card-text>
            <Vueditor></Vueditor>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import {ref} from 'vue';
import Vueditor from 'vueditor'

import 'vueditor/dist/style/vueditor.min.css'

const content = ref('');
</script>
