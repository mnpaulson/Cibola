<template>
  <v-flex d-flex xs12 sm12 md4>                             
    <v-card>
      <span v-show="isInfo" class="">          
        <v-btn style="z-index:0" class="close-btn" dark small right absolute outline fab color="grey" @click="clearCustomer()"><v-icon class="fab-fix" dark>close</v-icon></v-btn>
      </span>
      <!-- <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title>{{ header }}</v-toolbar-title>
      </v-toolbar> -->
      <v-card-text>
        <v-layout>
        <v-flex v-if="isSearch" d-flex sm11>
          <v-select
              v-model="searchSelect"
              :search-input.sync="search"
              autocomplete
              label="Customer Search"
              cache-items
              :items="fuseList"
              item-text="name"
              item-value="id"
              autofocus          
            ></v-select>
        </v-flex>
        <v-flex d-flex>
            <v-btn style="z-index:0" v-show="isSearch" color="primary" fab dark small @click="setFormState(true)" class="new-cus-btn"><v-icon class="fab-fix">add</v-icon></v-btn>                    
        </v-flex>
        </v-layout>
        <v-flex v-if="isInfo">
          <h3 class="headline mb-0">
            <v-icon large>person</v-icon>
            <span>{{ customer.fname }}</span>
            <span>{{ customer.lname }}</span>
          </h3>
          <p>
            <v-icon>phone</v-icon> {{ customer.phone }} <br>
            <v-icon>email</v-icon> {{ customer.email }} <br>
            <v-icon>home</v-icon> {{ customer.address }}
          </p>
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
        <div>
        </div>
        <div v-show="isForm">
          <v-flex d-flex row>
            <v-flex xs8 class="" v-show="customer.id == null"><v-btn xs6 block color="primary" @click="storeCustomer()">Create Customer</v-btn></v-flex>
            <v-flex xs8 class="" v-show="customer.id"><v-btn xs6 block  color="primary" @click="updateCustomer()">Save Changes</v-btn></v-flex>
            <v-flex xs4 class="ml-2"><v-btn xs6 block color="error" @click="clearCustomer()">Cancel</v-btn></v-flex>
          </v-flex>
        </div>
        <v-btn style="z-index:0" v-show="isInfo" dark small bottom right absolute fab color="primary" @click="setFormState(true)" class="fab-up"><v-icon class="fab-fix" dark>edit</v-icon></v-btn>
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
      searchSelect: "", //name after selection is made
      fuseList: [],
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
        if (!isNaN(val) && val != null) {
          this.getCustomer(val);
          this.$emit('update:id', val);
        }
      },
      id (val) {
        if (!isNaN(this.id) && this.id !== null) {
          this.getCustomer(this.id);
        }
        else this.setFormState(false);
      }
    },

    methods: {

      searchName() {
        if (this.searchList == null) {
          axios.get('/customers/searchList')
            .then((response) => {
              this.searchList = response.data;
              this.fuseMatch();
            })
            .catch((error) => {
              console.log(error);
            });
        } else {
          this.fuseMatch();
        }       
      },

      fuseMatch() {
        this.fuseList = [];
        this.$search(this.search, this.searchList, this.searchOptions).then(results => {
          results.forEach(result => {
            this.fuseList.push({name: result.fname + " " + result.lname, id: result.id});
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
        } else if (this.id == null){
          this.isForm = false;
          this.isSearch = true;
          this.isInfo = false;
          this.header = "Customer Lookup";
          this.customer.fname = null;
          this.customer.lname = null;
          this.customer.phone = null;
          this.customer.email = null;
          this.customer.address = null;
          this.customer.id = null;
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
            //Empty search list so new customer will show up
            this.searchList = null;
            this.$emit('newCustomer');
            this.$emit('update:id', response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      },

      //Updates the customer with current information
      updateCustomer() {
        axios.post('customers/update', this.customer)
          .then((response) => {
            this.setFormState(false);
          })
          .catch((error) => {
            console.log(error);
          });
      },

      //Clears customer data and updates parent that there is no customer selected
      clearCustomer() {
        this.customer.fname = null;
        this.customer.lname = null;
        this.customer.phone = null;
        this.customer.email = null;
        this.customer.address = null;
        this.customer.id = null;
        this.$emit('update:id', null);
        this.setFormState(false);
        this.fuseList = [];
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
      if (this.id !== null) {
        this.getCustomer(this.id);
      }
      else this.setFormState(false);
        
    }

  }
</script>