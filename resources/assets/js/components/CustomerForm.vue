<template>
<v-flex xs12 sm6>
  <v-card>
      <v-toolbar color="indigo" dark clipped-left flat>
          <v-toolbar-title>Customer Lookup</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form>
            <v-text-field
            label="Customer Search"
            v-model="name"
            xs12
            @keyup="searchName"
            v-if="!customerFormDisplay"
            ></v-text-field>
            <v-text-field
            label="Name"
            v-model="name"
            xs12
            v-if="customerFormDisplay"
            ></v-text-field>
            <v-text-field
            label="Phone Number"
            v-model="phone"
            xs12
            v-if="customerFormDisplay"
            ></v-text-field>
            <v-text-field
            label="E-Mail"
            v-model="email"
            xs12
            v-if="customerFormDisplay"
            ></v-text-field>
            <v-text-field
            label="Address"
            v-model="address"
            xs12
            v-if="customerFormDisplay"
            ></v-text-field>
        </v-form>
        <v-btn
            color="pink"
            dark
            small
            absolute
            bottom
            right
            fab
            @click="newCustomer"
        >
            <v-icon>add</v-icon>
        </v-btn>
      </v-card-text>
  </v-card>
</v-flex>
</template>

<script>
  export default {
    data: () => ({
      name: null,
      customerFormDisplay: false,
      phone: null,
      email: null,
      address: null
    }),
    props: {
      id: Number
    },
    methods: {
      searchName() {
        console.log(this.name);
        console.log(this.id);
      },
      newCustomer() {
        this.customerFormDisplay = true;
      }
    }
  }
</script>