<template>
  <v-container >
    <v-row no-gutters>
      <v-col cols="12">
        <v-sheet class="">
          <v-breadcrumbs :items="items">
            <template v-slot:prepend>
              <v-icon size="small" icon="$vuetify"></v-icon>
            </template>
          </v-breadcrumbs>
          <v-col cols="auto">
            <v-dialog
              transition="dialog-top-transition"
              width="auto"
            >
              <template v-slot:activator="{ props }">
                <v-btn
                  color="primary"
                  v-bind="props"
                >Add Doctor</v-btn>
              </template>
              <template v-slot:default="{ isActive }">
                <v-card>
                  <v-toolbar
                    color="primary"
                    title="Opening from the top"
                  ></v-toolbar>
                  <v-card-text>
                    <div class="text-h2 pa-12">Hello world!</div>
                  </v-card-text>
                  <v-card-actions class="justify-end">
                    <v-btn
                      variant="text"
                      @click="isActive.value = false"
                    >Close</v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
          </v-col>
        </v-sheet>
      </v-col>
      <v-col cols="12">
        <v-sheet class="">
          <v-data-table-server
            v-model:items-per-page="itemsPerPage"
            :headers="headers"
            :items-length="totalItems"
            :items="serverItems"
            :loading="loading"
            :search="search"
            class="elevation-1"
            item-value="name"
            @update:options="loadItems"
          ></v-data-table-server>
        </v-sheet>
      </v-col>
    </v-row>
  </v-container>

</template>

<script >
const desserts = [
  {
    name: 'Frozen Yogurt',
    calories: 159,
    fat: 6.0,
    carbs: 24,
    protein: 4.0,
    iron: '1',
  },
  {
    name: 'Jelly bean',
    calories: 375,
    fat: 0.0,
    carbs: 94,
    protein: 0.0,
    iron: '0',
  },
  {
    name: 'KitKat',
    calories: 518,
    fat: 26.0,
    carbs: 65,
    protein: 7,
    iron: '6',
  },
  {
    name: 'Eclair',
    calories: 262,
    fat: 16.0,
    carbs: 23,
    protein: 6.0,
    iron: '7',
  },
  {
    name: 'Gingerbread',
    calories: 356,
    fat: 16.0,
    carbs: 49,
    protein: 3.9,
    iron: '16',
  },
  {
    name: 'Ice cream sandwich',
    calories: 237,
    fat: 9.0,
    carbs: 37,
    protein: 4.3,
    iron: '1',
  },
  {
    name: 'Lollipop',
    calories: 392,
    fat: 0.2,
    carbs: 98,
    protein: 0,
    iron: '2',
  },
  {
    name: 'Cupcake',
    calories: 305,
    fat: 3.7,
    carbs: 67,
    protein: 4.3,
    iron: '8',
  },
  {
    name: 'Honeycomb',
    calories: 408,
    fat: 3.2,
    carbs: 87,
    protein: 6.5,
    iron: '45',
  },
  {
    name: 'Donut',
    calories: 452,
    fat: 25.0,
    carbs: 51,
    protein: 4.9,
    iron: '22',
  },
]

const FakeAPI = {
  async fetch ({ page, itemsPerPage, sortBy }) {
    return new Promise(resolve => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage
        const end = start + itemsPerPage
        const items = desserts.slice()

        if (sortBy.length) {
          const sortKey = sortBy[0].key
          const sortOrder = sortBy[0].order
          items.sort((a, b) => {
            const aValue = a[sortKey]
            const bValue = b[sortKey]
            return sortOrder === 'desc' ? bValue - aValue : aValue - bValue
          })
        }

        const paginated = items.slice(start, end)

        resolve({ items: paginated, total: items.length })
      }, 500)
    })
  },
}

export default {
  data: () => ({
    items: [
      {
        title: 'Dashboard',
        disabled: false,
        href: 'breadcrumbs_dashboard',
      },
      {
        title: 'Link 1',
        disabled: false,
        href: 'breadcrumbs_link_1',
      },
      {
        title: 'Link 2',
        disabled: true,
        href: 'breadcrumbs_link_2',
      },
    ],
    itemsPerPage: 5,
    headers: [
      {
        title: 'Dessert (100g serving)',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'Calories', key: 'calories', align: 'end' },
      { title: 'Fat (g)', key: 'fat', align: 'end' },
      { title: 'Carbs (g)', key: 'carbs', align: 'end' },
      { title: 'Protein (g)', key: 'protein', align: 'end' },
      { title: 'Iron (%)', key: 'iron', align: 'end' },
    ],
    search: '',
    serverItems: [],
    loading: true,
    totalItems: 0,
  }),
  methods: {
    loadItems ({ page, itemsPerPage, sortBy }) {
      this.loading = true
      FakeAPI.fetch({ page, itemsPerPage, sortBy }).then(({ items, total }) => {
        this.serverItems = items
        this.totalItems = total
        this.loading = false
      })
    },
  },
}
</script>
