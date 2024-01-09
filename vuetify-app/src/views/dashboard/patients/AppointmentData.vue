<template>
  <v-container>
    <v-row no-gutters>
      <v-col cols="12">
              <v-sheet>
                <v-breadcrumbs :items="breadcrumbs">
                  <template v-slot:prepend>
                    <v-icon size="small" icon="$vuetify"></v-icon>
                  </template>
                </v-breadcrumbs>
              </v-sheet>
      </v-col>
      <v-row>
        <v-col cols="12" class="">
          <v-card
            class="mx-auto my-2"
            title="Appointment Data"
            prepend-icon="mdi-36px mdi-light mdi-clipboard-text-outline"
            rel="noopener"
            color="warning"
          ></v-card>
          <v-col cols="12">
            <v-card>
              <v-container>
                <v-row align="center" justify="center">
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-file-document-edit-outline" color="info"> Edit Patinet</v-btn>
                  </v-col>
                  <v-col cols="auto">
                    <v-btn prepend-icon="mdi-printer" color="warning"> Print Invoice</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="12">
            <v-row>


            </v-row>
          </v-col>
        </v-col>
      </v-row>
    </v-row>
  </v-container>
</template>
<script setup>
import {computed} from 'vue'


const breadcrumbs = computed(() => [
  {
    title: 'Home',
    disabled: false,
    href: '/',
  },
  {
    title: 'Patients Appointment',
    disabled: false,
    href: '#',
  },
]);


</script>
