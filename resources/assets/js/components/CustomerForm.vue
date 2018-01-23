<template>
  <v-flex xs12 sm6>
    <v-card>
      <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title v-if="!customerFormDisplay">Customer Lookup</v-toolbar-title>
        <v-toolbar-title v-if="customerFormDisplay">Add / Edit Customer Info</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form>
          <v-layout row wrap>
            <v-flex xs12>
              <v-text-field label="Customer Search" v-model="name" xs12 @keyup="searchName" v-if="!customerFormDisplay"></v-text-field>
              <v-text-field label="Name" v-model="name" xs12 v-if="customerFormDisplay"></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field label="Phone Number" v-model="phone" xs12 v-if="customerFormDisplay"></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field label="E-Mail" v-model="email" xs12 v-if="customerFormDisplay"></v-text-field>
            </v-flex>
            <v-text-field label="Address" v-model="address" xs12 v-if="customerFormDisplay"></v-text-field>
          </v-layout>
        </v-form>
        <v-btn color="green" dark small absolute bottom right fab @click="newCustomerToggle(true)" v-if="!customerFormDisplay">
          <v-icon>add</v-icon>
        </v-btn>
        <v-btn color="primary" v-if="customerFormDisplay">Add Customer</v-btn>
        <v-btn color="error" v-if="customerFormDisplay" @click="newCustomerToggle(false)">Cancel</v-btn>
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
      address: null,
      cardHeader: ""
    }),
    props: {
      id: Number
    },
    methods: {
      searchName() {
        console.log(this.name);
        console.log(this.id);
      },
      newCustomerToggle(val) {
        this.customerFormDisplay = val;
        if (!val) this.name = null;
      }
    }
  }
</script>