<template>
  <v-flex d-flex xs12 sm12 md4>  
    <div v-if="isSearch">
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
          solo
          prepend-icon="person"
          append-icon="add_circle"
          :append-icon-cb="newCustomerForm"
        ></v-select>
    </div>                           
    <v-card  v-if="!isSearch">      
      <span v-show="isInfo" class="">          
        <v-btn style="z-index:0" class="close-btn" dark small right absolute outline fab color="grey" @click="clearCustomer()"><v-icon class="fab-fix" dark>close</v-icon></v-btn>
      </span>
      <!-- <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title>{{ header }}</v-toolbar-title>
      </v-toolbar> -->
      <v-card-text>
        <v-layout>
        <!-- <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular> -->
        <!-- <v-flex v-if="isSearch" d-flex sm11>
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
              solo       
            ></v-select>
        </v-flex> -->
        <!-- <v-flex d-flex>
            <v-btn style="z-index:0" v-show="isSearch" color="primary" fab dark small @click="setFormState(true)" class="new-cus-btn"><v-icon class="fab-fix">add</v-icon></v-btn>                    
        </v-flex> -->
        </v-layout>
        <v-flex v-if="isInfo">
          <router-link :to="{ path: `/customer/${customer.id}` }">          
            <h3 class="headline mb-0">
              <v-icon large>person</v-icon>
              <span>{{ customer.fname }}</span>
              <span>{{ customer.lname }}</span>
            </h3>
          </router-link>
          <p>
            <v-icon>phone</v-icon> {{ customer.phone }} <br>
            <v-icon>email</v-icon> {{ customer.email }} <br>
            <v-icon>home</v-icon> {{ customer.addr_st }} <br>
            {{ customer.addr_city }}, {{ customer.addr_prov }} {{ customer.addr_postal }} <br>
            {{ customer.addr_country }}
          </p>
        </v-flex>   
        <v-form>
          <v-layout row wrap v-if="isForm">
            <v-flex xs12 sm6>
              <v-text-field  label="First Name" :rules="nameRules" required v-model="customer.fname" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field append-icon="person" label="Last Name" :rules="nameRules" required v-model="customer.lname" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field append-icon="phone" label="Phone Number" v-model="customer.phone" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-text-field append-icon="mail" label="E-Mail" v-model="customer.email" xs12 ></v-text-field>
            </v-flex>
            <v-flex xs12><v-text-field  append-icon="home" label="Street Address" v-model="customer.addr_st" xs12 ></v-text-field></v-flex>
            <v-flex xs12 sm8><v-text-field label="City" v-model="customer.addr_city" xs12 ></v-text-field></v-flex>
            <v-flex xs12 sm4><v-text-field label="Province" v-model="customer.addr_prov" xs12 ></v-text-field></v-flex>
            <v-flex xs12 sm3><v-text-field label="Postal Code" v-model="customer.addr_postal" xs12 ></v-text-field></v-flex>
            <v-flex xs12 sm9><v-text-field label="Country" v-model="customer.addr_country" xs12 ></v-text-field></v-flex>
            
          </v-layout>
        </v-form>
        <div>
        </div>
        <div v-show="isForm">
          <v-flex d-flex row>
            <v-flex xs8 class="" v-show="customer.id == null"><v-btn xs6 block color="primary" @click="storeCustomer()">Create Customer</v-btn></v-flex>
            <v-flex xs8 class="" v-show="customer.id"><v-btn xs6 block  color="primary" @click="updateCustomer()">Save Changes</v-btn></v-flex>
            <v-flex xs4 class="ml-2"><v-btn xs6 block color="error" @click="setFormState(false)">Cancel</v-btn></v-flex>
          </v-flex>
        </div>
        <!-- <v-btn style="z-index:0" v-show="isInfo" dark small bottom right absolute fab color="primary" @click="setFormState(true)" class="fab-up"><v-icon class="fab-fix" dark>edit</v-icon></v-btn> -->
        <v-btn v-show="isInfo" color="grey " flat small right absolute class="cus-edit-btn" @click="setFormState(true)">Edit</v-btn>
      </v-card-text>
            <v-progress-linear v-show="loading" :indeterminate="true" class="mb-0"></v-progress-linear>      
    </v-card>
    <span class="cb-print">
        <div class="cb-print-element cb-print-blanks">
          <div class="cb-print-top-box cb-print-top-flags"><span class="cb-print-top-text">EMR NA LM Check</span></div>
          <div class="cb-print-top-box cb-print-top-deposit"><span class="cb-print-top-text">Deposit</span></div>
          <div class="cb-print-top-box cb-print-top-gold-credit"><span class="cb-print-top-text">Gold Credit</span></div>
          <div class="cb-print-top-box cb-print-total"><span class="cb-print-top-text">Total</span></div>          
        </div>
        <div class="cb-print-element cb-print-customer-info">
          <v-icon class="cb-print-element cb-print-customer-icon">person</v-icon><span class="cb-print-element">{{ customer.fname }} {{ customer.lname }}</span><br>
          <v-icon class="cb-print-element cb-print-customer-icon">phone</v-icon><span class="cb-print-element">{{ customer.phone }}</span> <br>
          <v-icon class="cb-print-element cb-print-customer-icon">email</v-icon><span class="cb-print-element">{{ customer.email }}</span>
        </div>
          <div class="cb-print-element cb-print-customer-name">Name: {{ customer.fname }} {{ customer.lname }}</div>        
    </span>                                
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
      loading: false,
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
        addr_st: null,
        addr_city: null,
        addr_prov: null,
        addr_postal: null,
        addr_country: null,
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
          this.loading = true;
          axios.get('/customers/searchList')
            .then((response) => {
              this.searchList = response.data;
              this.loading = false;              
              this.fuseMatch();
            })
            .catch((error) => {
              this.loading = false;
              console.log(error);
            });
        } else {
          this.fuseMatch();
        }       
      },

      fuseMatch() {
        this.fuseList = [];
        this.$search(this.search, this.searchList, this.searchOptions).then(results => {
          //Only load the 20 best matches into the display
          var length = results.length;
          console.log(length);
          if (length > 20) length = 20;
          for (var i = 0; i < length; i++) {
            this.fuseList.push({name: results[i].fname + " " + results[i].lname, id: results[i].id});
          }
          // results.forEach(result => {
          //   this.fuseList.push({name: result.fname + " " + result.lname, id: result.id});
          // });
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
          this.customer.addr_st = null;
          this.customer.addr_city = null;
          this.customer.addr_prov = null;
          this.customer.addr_postal = null;
          this.customer.addr_country = null;
          this.customer.id = null;
        } else {
          this.isForm = false;
          this.isSearch = false;
          this.isInfo = true;
          this.header = "Customer Details";
        }
        
      },

      //For use with search appended icon callback function
      newCustomerForm() {
        this.setFormState(true);
      },

      //Saves new customer to DB
      storeCustomer() {
        this.loading = true;        
        axios.post('customers/store', this.customer)
          .then((response) => {
            this.customer.id = response.data;
            this.setFormState(false);
            //Empty search list so new customer will show up
            this.searchList = null;
            this.$emit('newCustomer');
            this.$emit('update:id', response.data);
            this.loading = false;            
          })
          .catch((error) => {
            this.store.setAlert(true, "error", error.message);
            this.loading = false;
            console.log(error);                        
          });
      },

      //Updates the customer with current information
      updateCustomer() {
        this.loading = true;        
        axios.post('customers/update', this.customer)
          .then((response) => {
            this.setFormState(false);
            this.loading = false;            
          })
          .catch((error) => {
            this.loading = false;
            console.log(error);
          });
      },

      //Clears customer data and updates parent that there is no customer selected
      clearCustomer() {
        this.customer.fname = null;
        this.customer.lname = null;
        this.customer.phone = null;
        this.customer.email = null;
        this.customer.addr_st = null;
        this.customer.addr_city = null;
        this.customer.addr_prov = null;
        this.customer.addr_postal = null;
        this.customer.addr_country = null;
        this.customer.id = null;
        this.$emit('update:id', null);
        this.setFormState(false);
        this.fuseList = [];
        this.searchSelect = null;
        
      },

      getCustomer(id) {
        if (id == 0) return;
        this.customer.id = id;
        this.loading = true;                    
        axios.post('customers/show', this.customer)
          .then((response) => {
            this.customer.fname = response.data[0].fname;
            this.customer.lname = response.data[0].lname;
            this.customer.phone = response.data[0].phone;
            this.customer.email = response.data[0].email;
            this.customer.addr_st = response.data[0].addr_st;
            this.customer.addr_city = response.data[0].addr_city;
            this.customer.addr_prov = response.data[0].addr_prov;
            this.customer.addr_postal = response.data[0].addr_postal;
            this.customer.addr_country = response.data[0].addr_country;
            this.setFormState(false);
            this.loading = false;                        
          })
          .catch((error) => {
            this.loading = false;
            console.log(error);
          });
        
      }
    },

    mounted() {
      if (this.id !== null) {
        this.getCustomer(this.id);
      }
      else this.setFormState(false);
        
    },
    computed: {
      store() {
          return this.$root.$data.store;
      }
    }

  }
</script>