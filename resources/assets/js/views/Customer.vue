<template>
    <div>
        <customer-form :id.sync="id" v-on:newCustomer="update()"></customer-form>
        <v-fade-transition>                  
        <v-flex v-show="id" class="mt-4">
            <v-btn color="primary">
                <v-icon>add</v-icon>
                New Job Bag
            </v-btn>
            <v-btn color="error" @click="deleteDialog = true">
                <v-icon>delete</v-icon>
                Delete Customer
            </v-btn>
        </v-flex>
        </v-fade-transition>   
        <transition name="component-fade" appear>                                
        <v-layout v-show="!id" xs12 row class="mt-4">
            <v-card class="mx-3">
                <v-toolbar color="indigo" dark clipped-left flat>
                    <v-toolbar-title>Customers</v-toolbar-title>
                </v-toolbar>
                <template>
                    <v-data-table v-bind:headers="headers" :items="items" v-bind:pagination.sync="pagination" class="elevation-1">
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
        </v-layout>
        </transition>               
        <v-dialog v-model="deleteDialog" max-width="500px">
            <v-card>
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
                if(isNaN(this.id)) this.id = null;
                if (this.id == null) this.$router.replace("/customer");
                else if (!isNaN(this.id)) this.$router.replace("/customer/" + this.id);
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
            this.id = Number(this.$route.params.id);
            this.getCustomers();
        }
    }
</script>