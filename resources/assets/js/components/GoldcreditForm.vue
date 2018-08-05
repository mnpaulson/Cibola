<template>
    <v-layout row wrap>
        <v-flex d-flex xs12 lg8 xl6>
            <transition name="component-fade" appear>
                <v-card>
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
                </v-card>
            </transition>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        data: () => ({
            employeeList: [],
            employee: null,
            credit: {
                id: null,
                employee_id: null,
                customer_id: null,
                goldCAD: null,
                goldUSD: null,
                goldDate: null,
                creditValue: null
            },
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
        },
        mounted() {
            this.getEmployees();
        }
    }
</script>