<template>
    <v-flex xs12 sm6>
        <v-card>
            <v-toolbar color="indigo" dark clipped-left flat>
                <v-toolbar-title>Employees</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-text-field label="Add Employee" v-model="name" xs12 @keyup.enter.native="addEmployee"></v-text-field>
            </v-card-text>
            <v-card-text>
                <v-list dense>
                    <template v-for="(employee, index) in employees">
                        <v-list-tile  :key="employee.id">
                            <v-list-tile-content>
                                <v-list-tile-title>{{ employee.name }}</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-list-tile-action-text>
                                    <v-btn small color="error"  @click="deleteEmployee(employee.id)">Delete</v-btn>
                                </v-list-tile-action-text>
                            </v-list-tile-action>
                        </v-list-tile>
                        <v-divider v-if="index + 1 < employees.length" :key="employee.id"></v-divider>
                    </template>
                </v-list>
            </v-card-text>
        </v-card>
    </v-flex>
</template>

<script>
    export default {
        data: () => ({
            name: null,
            // Placeholder hard coded names
            employees: [{name: 'Graham', id: 1}, {name: 'Elliot', id: 2}, {name: 'Amanda', id: 3}, {name: 'Carly', id: 4}]
        }),
        methods: {
            addEmployee() {
                var nextId = this.employees[this.employees.length - 1].id + 1;
                this.employees.push({name: this.name, id: nextId});
                this.name = null;
            },
            deleteEmployee(id) {
                console.log(id);
            }
        }
    }
</script>