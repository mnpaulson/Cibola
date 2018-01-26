<template>
  <v-flex xs12 sm6>
    <v-card>
      <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title>{{ header }}</v-toolbar-title>

      </v-toolbar>
      <v-card-text>
        <v-flex xs12 v-if="isSearch">
          <!-- <v-text-field label="Customer Search" v-model="name" xs12 @keyup="searchName"></v-text-field> -->
          <v-select
              v-model="searchSelect"
              :search-input.sync="search"
              label="Customer Search"
              combobox
              cache-items
              :items="searchResults"
              item-text="name"
              item-value="id"
            ></v-select>
        </v-flex>
        <v-flex xs12 v-if="isInfo">
          <p>
            Name: 
            <span>{{ customer.fname }}</span>
            <span>{{ customer.lname }}</span>
          </p>
          <p>Phone: {{ customer.phone }} Email: {{ customer.email }}</p>
          <p>Address: {{ customer.address }}</p>
        </v-flex>
        <v-form>
          <v-layout row wrap v-if="isForm">
            <v-flex xs12 sm6>
              <v-text-field label="First Name" :rules="nameRules" required v-model="customer.fname" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field label="Last Name" :rules="nameRules" required v-model="customer.lname" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field label="Phone Number" v-model="customer.phone" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field label="E-Mail" v-model="customer.email" xs12 ></v-text-field>
            </v-flex>
            <v-text-field label="Address" v-model="customer.address" xs12 ></v-text-field>
          </v-layout>
        </v-form>
        <div v-show="isSearch">
        <v-btn color="primary" dark small absolute bottom right fab @click="setFormState(true)" >
          <v-icon>add</v-icon>
        </v-btn>
        </div>
        <div v-show="isForm">
          <v-btn v-show="customer.id == null" color="primary" @click="storeCustomer()">Add Customer</v-btn>
          <v-btn v-show="customer.id" color="primary" @click="updateCustomer()">Update Customer</v-btn>
          <v-btn color="error" @click="setFormState(false)">Cancel</v-btn>
        </div>
        <div v-show="isInfo" class="cdb-bottom-right">
          <v-btn fab dark small color="primary" @click="setFormState(true)"><v-icon dark>edit</v-icon></v-btn>
          <v-btn fab dark small color="error" @click="clearCustomer()"><v-icon dark>close</v-icon></v-btn>
        </div>
      </v-card-text>
    </v-card>
  </v-flex>
</template>

<script>
  export default {
    data: () => ({

      isForm: false,
      isSearch: false,
      isInfo: false,
      header: "",
      searchSelect: "",
      searchResults: [],
      searchList: null,
      search: [],
      searchOptions: {
          shouldSort: true,
          threshold: 0.6,
          location: 0,
          distance: 100,
          maxPatternLength: 32,
          minMatchCharLength: 1,
        keys: ["fname", "lname"]
      },
      customer: {
        fname: null,
        lname: null,
        phone: null,
        email: null,
        address: null,
        id: null
      },

      nameRules: [
        (v) => !!v || 'Name is required',
      ]

    }),

    props: {
      id: Number
    },

    watch: {
      search (val) {
        val && this.searchName(val)
      },
      searchSelect (val) {
        // console.log(val);
        if (!isNaN(val)) {
          // console.log(val);
          this.getCustomer(val);
          this.$emit('update:id', val);
        }
      }
    },

    methods: {

      searchName() {
        axios.get('/customers/searchList')
          .then((response) => {
            this.searchList = response.data;
            this.fuseMatch();
          })
          .catch((error) => {
            console.log(error);
          });        
      },

      fuseMatch() {
        this.$search(this.search, this.searchList, this.searchOptions).then(results => {
          results.forEach(result => {
            this.searchResults = [];
            this.searchResults.push({name: result.fname + " " + result.lname, id: result.id});
          });
        })
      },

      //Sets the state of the customer card
      //val == true will enable the customer entry/edit form
      //val == false will set it to either search or info display
      //based on the existance of a customer id
      setFormState(val) {
        if (val){
          this.isForm = true;
          this.isSearch = false;
          this.isInfo = false;
          if (this.customer.id) {
            this.header = "Edit Customer Details";
          } else {
            this.header = "Add New Customer";
          }
          
        } else if (this.customer.id == null){
          this.isForm = false;
          this.isSearch = true;
          this.isInfo = false;
          this.header = "Customer Lookup";
        } else {
          this.isForm = false;
          this.isSearch = false;
          this.isInfo = true;
          this.header = "Customer Details";
        }
        
      },

      //Saves new customer to DB
      storeCustomer() {
        axios.post('customers/store', this.customer)
          .then((response) => {
            this.customer.id = response.data;
            this.setFormState(false);
          })
          .catch((error) => {
            console.log(error);
          });
      },

      updateCustomer() {
        axios.post('customers/update', this.customer)
          .then((response) => {
            this.setFormState(false);
          })
          .catch((error) => {
            console.log(error);
          });
      },

      clearCustomer() {
        this.customer.fname = null;
        this.customer.lname = null;
        this.customer.phone = null;
        this.customer.email = null;
        this.customer.address = null;
        this.customer.id = null;
        this.$emit('update:id', null);
        this.setFormState(false);
        this.searchResults = [];
        this.searchSelect = null;
        
      },

      getCustomer(id) {
        this.customer.id = id;
        axios.post('customers/show', this.customer)
          .then((response) => {
            this.customer.fname = response.data[0].fname;
            this.customer.lname = response.data[0].lname;
            this.customer.phone = response.data[0].phone;
            this.customer.email = response.data[0].email;
            this.customer.address = response.data[0].address;
            this.setFormState(false);
          })
          .catch((error) => {
            console.log(error);
          });
        
      }
    },

    mounted() {
      if (this.id !== null) this.getCustomer(this.id);
      else this.setFormState(false);
        
    }

  }
</script>