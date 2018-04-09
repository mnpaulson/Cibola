<template>
    <v-flex xs12 md6>
        <transition name="component-fade" appear>          
        <v-card>
            <!-- <v-toolbar color="indigo" dark clipped-left flat>
                <v-toolbar-title>Recent Customers</v-toolbar-title>
            </v-toolbar> -->
        <v-list dense>
            <template v-for="(customer, index) in customers">
                <v-list-tile
                ripple
                :key="customer.id  + '-key'"
                :href="'#/customer/' + customer.id"                
                >
                    <v-list-tile-title>
                            {{customer.fname}} {{customer.lname}}
                    </v-list-tile-title>
                </v-list-tile>
                <v-divider v-if="index + 1 < customers.length" :key="customer.id"></v-divider>
            </template>
        </v-list>
        </v-card>
        </transition>       
    </v-flex>
</template>

<script>
export default {
  data: () => ({
    customers: []
  }),
  methods: {
    getCustomerList() {
      axios
        .get("/customers/recentCustomerList")
        .then(response => {
          this.customers = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    setId(val) {
      this.id = Number(val);
    }
  },

  props: {
    id: Number
  },
  mounted() {
    this.getCustomerList();
  }
};
</script>