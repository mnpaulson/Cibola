<template>
    <transition name="component-fade" appear>
    <v-layout row wrap>
        <v-flex d-flex xs12 lg8 xl6>
                <v-card>
                    <v-form ref="goldcreditForm" lazy-validation>                    
                    <v-layout row wrap>
                        <v-autocomplete
                                label="Employee Select"
                                cache-items
                                required
                                :rules="employeeRules"
                                prepend-icon="person_pin"
                                :items="employeeList"
                                v-model="credit.employee_id"
                                item-text="name"
                                item-value="id"
                        ></v-autocomplete>
                    </v-layout>
                    </v-form>
                </v-card>
        </v-flex>
        <v-flex d-flex xs12 lg8 xl6>
                <v-card>
                    <v-flex d-flex xs6 md3>
                    <v-autocomplete
                        label="Item Select"
                        cache-items
                        :items="itemList"
                        item-text="name"
                        item-value="id"
                    ></v-autocomplete>
                    </v-flex>
                    <v-flex xs6 md3>
                    <v-text-field
                        label="Multiplier"
                        box
                    ></v-text-field>
                    </v-flex>
                </v-card>
        </v-flex>
    </v-layout>
    </transition>
</template>

<script>
    export default {
        data: () => ({
            employeeList: [],
            employee: null,
            itemList: [],
            credit: {
                id: null,
                employee_id: null,
                customer_id: null,
                goldCAD: null,
                goldUSD: null,
                goldDate: null,
                creditValue: null
            },
            items: [],
            employeeRules: [
                v => !!v || 'Select employee'
            ]
        }),
        methods: {
            getEmployees() {
                axios.get('/employees/active')
                    .then((response) => {
                        this.employeeList = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            getValues() {
                axios.get('/values/gettype?type_id=1')
                    .then((response) => {
                        this.itemList = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
        },
        mounted() {
            this.getEmployees();
            this.getValues();
        },
        props: {
            customer_id: Number,
            goldcredit_id: Number
        },
        watch: {
            customer_id (val) {
                if (!isNaN(this.customer_id) && this.customer_id !== null) {
                    this.credit.customer_id = val;
                }
            },
        }
    }
</script>