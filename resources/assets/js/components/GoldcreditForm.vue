<template>
    <transition name="component-fade" appear>
        <div>
    <v-layout row wrap>
        <v-flex d-flex xs12 lg6 xl6>
                <v-card>
                    <v-card-text>
                    <v-form ref="goldcreditForm" lazy-validation>                    
                    <v-layout row wrap>
                        <v-flex row xs12 md6>
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
                            <v-text-field
                                label="Total Value"
                                prepend-icon="attach_money"
                            ></v-text-field>
                            <v-menu
                                ref="dateMenu"
                                lazy
                                :close-on-content-click="true"
                                v-model="dateMenu"
                                transition="scale-transition"
                                offset-y
                                full-width
                                :nudge-right="40"
                                min-width="290px"
                                :return-value.sync="date"
                                >
                                    <v-text-field
                                    slot="activator"
                                    label="Date"
                                    v-model="credit.creditDate"
                                    prepend-icon="event"
                                    readonly
                                    ></v-text-field>
                                    <v-date-picker v-model="credit.creditDate" no-title scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="dateMenu = false">Cancel</v-btn>
                                    <v-btn flat color="primary" @click="$refs.dateMenu.save(dateMenu)">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                                <v-checkbox
                                    :label="'Credit Used'"
                                    v-model="credit.used"
                                ></v-checkbox>
                        </v-flex>
                        <v-flex row xs12 md2>
                                <v-text-field
                                    v-model="credit.goldCAD"
                                    label="Gold CAD"
                                ></v-text-field>
                                <v-text-field
                                    v-model="credit.platCAD"
                                    label="Platinum CAD"
                                ></v-text-field>
                                <v-text-field
                                    v-model="credit.exchange"
                                    label="Exchange"
                                ></v-text-field>
                             <v-btn flat color="primary" @click="getNewGoldValue"><v-icon>refresh</v-icon>{{lastGoldValues}}</v-btn>
                        </v-flex>
                    </v-layout>
                    </v-form>
                    </v-card-text>
                </v-card>
        </v-flex>
    </v-layout>
        <v-layout row wrap>
            <v-flex d-flex xs12 lg2 xl2>
                <v-btn color="primary" @click="newItem">Add Item</v-btn>
            </v-flex>
        </v-layout>
        <template v-for="item in itemList">
            <v-layout row wrap :key="item.id">
                <v-flex d-flex xs12 lg8 xl6>
                        <v-card>
                            <v-card-text>
                                <v-layout row wrap>
                                    <v-flex xs6 md3>
                                        <v-autocomplete
                                            label="Item Select"
                                            cache-items
                                            :items="valueList"
                                            :return-object=true
                                            item-text="name"
                                            item-value="id"
                                            v-model="item.itemObj"
                                        ></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs6 md3>
                                        <v-text-field
                                            v-model="item.weight"
                                            label="Weight/Amount"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 md1>
                                        <v-text-field
                                            v-model="item.multiplier"
                                            label="*"
                                            disabled
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 md1>
                                        <v-text-field
                                            v-model="item.markup"
                                            label="Markup"
                                            disabled
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 md2>
                                        <v-text-field
                                            v-model="item.value"
                                            label="Value"
                                        ></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                </v-flex>
            </v-layout>
        </template>
    </div>
    </transition>
</template>

<script>
    export default {
        data: () => ({
            employeeList: [],
            employee: null,
            valueList: [],
            itemList: [],
            date: false,
            dateMenu: false,
            credit: {
                id: null,
                employee_id: null,
                customer_id: null,
                goldCAD: null,
                exchange: null,
                platCAD: null,
                metalPriceDate: null,
                creditDate: null,
                creditValue: null,
                used: false
            },
            items: [],
            employeeRules: [
                v => !!v || 'Select employee'
            ],
            lastGoldValues: "2018-08-08 3:29PM"
        }),
        methods: {
            //Gets employee list
            getEmployees() {
                axios.get('/employees/active')
                    .then((response) => {
                        this.employeeList = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //Gets metal select menu options
            getValues() {
                axios.get('/values/gettype?type_id=1')
                    .then((response) => {
                        this.valueList = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            // Adds a new blank item to list
            newItem() {
                var list = {
                    id: null,
                    item: null,
                    itemObj: null,
                    weight: null,
                    multiplier: null,
                    markup: null,
                    value: null
                };
                var newList = this.itemList;
                newList.push(list);
                this.$set(this, 'itemList', newList);
            },
            //Gets the value of gold in grams
            getNewGoldValue() {
                axios.get('/values/getGoldValue')
                    .then((response) => {
                        // console.log(response);
                        var goldOz = response.data[0];
                        this.credit.exchange = this.round(response.data[1], 2);

                        var goldG = goldOz / 31.1;
                        this.credit.goldCAD = this.round(goldG, 2);
                        this.getNewPlatValue();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            getNewPlatValue() {
                axios.get('/values/getPlatValue')
                    .then((response) => {
                        // console.log(response);
                        var platOz = response.data;
                        platOz = platOz * this.credit.exchange;
                        var platG = platOz / 31.1;
                        this.credit.platCAD = this.round(platG, 2);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //Round number to desired decimals
            round(value, decimals) {
                return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }
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
            //Calculate values on changes
            itemList: {
                handler(list) {
                    list.forEach(function(e) {
                        var metal;
                        if (e.itemObj) {
                            e.multiplier = e.itemObj.value1;
                            e.markup = e.itemObj.value2;
                            e.item = e.itemObj.id;
                            if (e.itemObj.value3 === "Gold" ) {
                                metal = this.credit.goldCAD;
                            } else if (e.itemObj.value3 === "Platinum" ) {
                                metal = this.credit.platCAD;
                            } else {
                                metal = 1;
                            }
                        }

                        e.value = e.weight * e.multiplier * e.markup * metal;
                    }.bind(this));
                },
                deep: true
            },
        }
    }
</script>