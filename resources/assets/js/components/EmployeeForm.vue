<template>
    <v-flex xs12 sm6>
        <v-card>
            <v-toolbar color="indigo" dark clipped-left flat>
                <v-toolbar-title>Employees</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-text-field label="Add Employee" v-model="currentEmployee.name" xs12></v-text-field>
                <v-btn v-show="!currentEmployee.id" small color="primary" @click="createEmployee">Add</v-btn>
                <v-btn v-show="currentEmployee.id" small color="primary" @click="updateEmployee">Update</v-btn>
            </v-card-text>
            <v-card-text>
                <v-list dense>
                    <template v-for="(employee, index) in employees">
                        <v-list-tile :key="employee.id">
                            <v-list-tile-content>
                                <v-list-tile-title>{{ employee.name }}</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-list-tile-action-text>
                                    <v-btn small color="secondary" @click="setCurrentEmployee(employee.id, employee.name, employee.active)">Edit</v-btn>
                                    <v-btn v-show="!employee.active" small color="primary" @click="toggleActive(true, employee.id, employee.name)">Activate</v-btn>
                                    <v-btn v-show="employee.active" small color="grey" @click="toggleActive(false, employee.id, employee.name)">Deactivate</v-btn>
                                    <v-btn small color="error" @click="toggleDeleteDialog(employee.id)">Delete</v-btn>
                                </v-list-tile-action-text>
                            </v-list-tile-action>
                        </v-list-tile>
                        <v-divider v-if="index + 1 < employees.length" :key="employee.name"></v-divider>
                    </template>
                </v-list>
            </v-card-text>
        </v-card>
        <v-dialog v-model="deleteDialog" max-width="500px">
            <v-card>
                <v-toolbar color="error" dark clipped-left flat>
                    <v-toolbar-title>
                        <v-icon>warning</v-icon> Delete Employee</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    Are you sure you want to delete this employee?
                    <br> This will also remove them from any associated jobs.
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error" @click.stop="deleteEmployee()">
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
    </v-flex>

</template>

<script>
    export default {
        data: () => ({
            currentEmployee: {
                id: null,
                name: null,
                active: null
            },
            employees: [],
            deleteId: null, //Holds ID of employee to be deleted
            deleteDialog: false
        }),
        methods: {
            //create new employee
            createEmployee() {
                axios.post('employees/create', this.currentEmployee)
                    .then((response) => {
                        // this.currentEmployee.id = response.data;
                        this.currentEmployee.name = null;
                        this.getEmployees();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //delete employee
            deleteEmployee() {
                axios.post('employees/delete', {
                        id: this.deleteId
                    })
                    .then((response) => {
                        this.deleteId = null;
                        this.deleteDialog = false;
                        this.getEmployees();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //Update employee name
            updateEmployee() {
                axios.post('employees/update', this.currentEmployee)
                    .then((response) => {
                        this.clearCurrentEmployee();
                        this.getEmployees();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //Toggles an employees active state
            toggleActive(active, id, name) {
                axios.post('employees/update', {
                        id: id,
                        active: active,
                        name: name
                    })
                    .then((response) => {
                        this.getEmployees();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //Get all employees
            getEmployees() {
                axios.get('/employees/index')
                    .then((response) => {
                        this.employees = [];
                        this.employees = response.data;
                        console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //Sets the current employee for editing
            setCurrentEmployee(id, name, active) {
                this.currentEmployee.id = id;
                this.currentEmployee.name = name;
                this.currentEmployee.active = active;
            },
            //Clears the active employee
            clearCurrentEmployee() {
                this.currentEmployee.id = null;
                this.currentEmployee.name = null;
                this.currentEmployee.active = null;     
            },
            //Shows the delete dialog and set deleteID
            toggleDeleteDialog(id) {
                this.deleteId = id;
                this.deleteDialog = true;
            }

        },
        mounted() {
            this.getEmployees();
        }
    }
</script>