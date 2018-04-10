<template>
    <div>
        <customer-form :id.sync="id" v-on:newCustomer="update()"></customer-form>
        <v-fade-transition>                  
        <v-flex d-flex class="">
            <div v-show="id"  class="my-1">
                <v-btn color="primary" :href="'#/job/0/' + id">
                    <v-icon>add</v-icon>
                    New Job
                </v-btn>
                <v-btn color="error" @click="deleteDialog = true">
                    <v-icon>delete</v-icon>
                    Delete Customer
                </v-btn>
            </div>
        </v-flex>
        </v-fade-transition>   
        <transition name="component-fade" appear>                                
        <v-flex d-flex xs12 md6  class="mt-2">
            <v-card v-show="!id" class="">
                <!-- <v-toolbar color="indigo" dark clipped-left flat>
                    <v-toolbar-title>Customers</v-toolbar-title>
                </v-toolbar> -->
                <v-card-title>
                    <v-card-title primary-title>
                        <h3 class="headline mb-0">Customers</h3>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="search"
                    ></v-text-field>
                </v-card-title>
                <template>
                    <v-data-table v-bind:headers="headers" :items="items" v-bind:pagination.sync="pagination" class="elevation-1" :search="search">
                        <template slot="items" slot-scope="props">
                            <tr @click="setId(props.item.id)">
                                <td class="text-xs-right">{{ props.item.fname }}</td>
                                <td class="text-xs-right">{{ props.item.lname }}</td>
                                <td class="text-xs-right">{{ props.item.created_at }}</td>
                                <td class="text-xs-right">{{ props.item.updated_at }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </template>
            </v-card>
        </v-flex>
        </transition>               
        <v-dialog v-model="deleteDialog" max-width="500px">
            <v-card xs12 md6>
                <v-toolbar color="error" dark clipped-left flat>
                    <v-toolbar-title><v-icon>warning</v-icon> Delete Customer</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    Are you sure you want to delete this customer? <br>
                    This will also remove them from any associated jobs.
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error"  @click.stop="deleteCustomer()">
                        <v-icon>delete</v-icon>
                        Delete
                    </v-btn>                    
                    <v-btn color="primary" right absolute @click.stop="deleteDialog=false">
                        <v-icon>cancel</v-icon>
                        Cancel
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        data: () => ({
            id: null,
            search: null,
            deleteDialog: false,
            items: [],
            headers: [{
                    text: 'First Name',
                    value: 'fname'
                },
                {
                    text: 'Last Name',
                    value: 'lname'
                },
                {
                    text: 'Created',
                    value: 'created_at'
                },
                {
                    text: 'Updated',
                    value: 'updated_at'
                },
            ],
            pagination: {
              sortBy: 'created_at',
              descending: true,
              rowsPerPage: 10
            },
        }),
        watch: {
            id(val) {
                if (this.id == null) this.$router.push("/customer");
            },
            // Handle changing between customer view and no customer selected
            '$route' (to, from) {
                if (!to.params.id) this.id = null;
                else this.id = Number(to.params.id);
            }
        },

        methods: {

            getCustomers() {
                axios.get('/customers/index')
                    .then((response) => {
                        this.items = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            setId(val) {
                this.id = Number(val);
                this.$router.push("/customer/" + val);
            },

            deleteCustomer() {
                this.deleteDialog = false;
                axios.post('/customers/delete', {id: this.id})
                    .then((response) => {
                        this.id = null;
                        this.update();
                    })
                    .catch((error) => {
                        console.log(error);
                    }); 
            },

            update() {
                this.getCustomers();
            }
        },
        mounted() {
            if (this.$route.params.id) this.id = Number(this.$route.params.id);
            this.getCustomers();
        }
    }
</script>