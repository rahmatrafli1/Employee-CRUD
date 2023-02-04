<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Create New Employee</div>

          <div class="card-body">
            <form>
              <div class="form-group mb-3">
                <label>Name</label>
                <input
                  type="text"
                  v-model="cam.name"
                  class="form-control"
                  placeholder="Example: Evan"
                />
              </div>
              <div class="form-group mb-3">
                <label>Job Name</label>
                <input
                  type="text"
                  v-model="cam.job_name"
                  class="form-control"
                  placeholder="Example: IT Support"
                />
              </div>
              <div class="form-group mb-3">
                <label>Manager Name</label>
                <input
                  type="text"
                  v-model="cam.manager_name"
                  class="form-control"
                  placeholder="Example: David"
                />
              </div>
              <div class="form-group mb-3">
                <label>Hire Date</label>
                <input
                  type="date"
                  v-model="cam.hire_date"
                  class="form-control"
                />
              </div>
              <div class="form-group mb-3">
                <label>Salary</label>
                <input
                  type="number"
                  v-model="cam.salary"
                  class="form-control"
                  placeholder="Example: 5000000"
                />
              </div>
              <div class="form-group">
                <label>Commission</label>
                <input
                  type="number"
                  v-model="cam.commission"
                  class="form-control"
                  placeholder="Example: 5000000"
                />
              </div>
              <v-btn
                type="submit"
                color="blue"
                @click.prevent="createEmployee"
                class="mt-3"
              >
                Create
              </v-btn>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">List Employee</div>

          <div class="card-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Job Name</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="employee in employees" :key="employee.id">
                  <td>{{ employee.name }}</td>
                  <td>{{ employee.job_name }}</td>
                  <td>
                    <v-row justify="space-between" class="text-center">
                      <v-col>
                        <v-btn
                          icon="mdi-eye"
                          color="primary"
                          @click="showEmployee(employee)"
                        ></v-btn>
                      </v-col>
                      <v-col>
                        <v-btn
                          icon="mdi-pencil"
                          color="warning"
                          @click="editEmployee(employee)"
                        ></v-btn>
                      </v-col>
                      <v-col>
                        <v-btn
                          icon="mdi-delete-outline"
                          color="error"
                          @click="deleteEmployee(employee)"
                        ></v-btn>
                      </v-col>
                    </v-row>
                  </td>
                </tr>
              </tbody>
            </table>
            <v-pagination
              v-model="page"
              @click="getEmployee"
              :length="lengthPage"
            ></v-pagination>
          </div>
        </div>
      </div>
      <v-dialog
        v-model="dialogEdit"
        transition="dialog-bottom-transition"
        width="1500"
        persistent
      >
        <v-card>
          <v-card-text>
            <h5>Edit Employee</h5>
            <v-form>
              <v-text-field v-model="name" label="Name"></v-text-field>

              <v-text-field v-model="job_name" label="Job Name"></v-text-field>

              <v-text-field
                v-model="manager_name"
                label="Manager Name"
              ></v-text-field>

              <v-text-field
                v-model="hire_date"
                label="Hire Date"
                type="date"
              ></v-text-field>

              <v-text-field
                v-model="salary"
                label="Salary"
                type="number"
              ></v-text-field>

              <v-text-field
                v-model="commission"
                label="Commission"
                type="number"
              ></v-text-field>

              <v-btn color="primary" block @click="updateSubmit(idEditEmployee)"
                >Update</v-btn
              >
              <v-btn color="error" class="mt-2" block @click="cancelDialogEdit"
                >Back</v-btn
              >
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        v-model="dialogDelete"
        transition="dialog-bottom-transition"
        width="700"
        persistent
      >
        <v-card>
          <v-card-text>
            <h5>Delete Employee</h5>
            <v-form>
              <p>Are you sure for delete?</p>
              <v-btn
                color="primary"
                block
                @click="deleteSubmit(idDeleteEmployee)"
                >Delete</v-btn
              >
              <v-btn
                color="danger"
                class="mt-2"
                block
                @click="cancelDialogDelete"
                >Cancel</v-btn
              >
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        v-model="dialogShow"
        transition="dialog-bottom-transition"
        width="1500"
        persistent
      >
        <v-card>
          <template v-slot:title> Show Employees </template>

          <v-card-text>{{ name }}</v-card-text>

          <v-card-subtitle>
            {{ job_name }}
          </v-card-subtitle>

          <v-card-text>
            <div>Manager: {{ manager_name }}</div>
          </v-card-text>

          <v-card-text>
            <div>Hire Date: {{ hire_date }}</div>
          </v-card-text>

          <v-card-text>
            <div>Salary: Rp {{ salary }}</div>
          </v-card-text>

          <v-card-text>
            <div>Commission: Rp {{ commission }}</div>
          </v-card-text>

          <div class="d-flex">
            <v-btn variant="plain" @click="cancelDialogShow"> Back </v-btn>
          </div>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import Swal from "sweetalert2";
</script>
<script>
export default {
  data() {
    return {
      cam: {
        name: "",
        job_name: "",
        manager_name: "",
        hire_date: "",
        salary: "",
        commission: "",
      },
      name: "",
      job_name: "",
      manager_name: "",
      hire_date: "",
      salary: "",
      commission: "",
      page: 1,
      lengthPage: 0,
      employees: [],
      dialogEdit: false,
      dialogDelete: false,
      dialogShow: false,
      idEditEmployee: null,
      idDeleteEmployee: null,
      idShowEmployee: null,
    };
  },
  mounted() {
    this.getEmployee();
  },
  methods: {
    async createEmployee() {
      axios
        .post("save_employee", {
          name: this.cam.name,
          job_name: this.cam.job_name,
          manager_name: this.cam.manager_name,
          hire_date: this.cam.hire_date,
          salary: this.cam.salary,
          commission: this.cam.commission,
        })
        .then((res) => {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: res.data,
            showConfirmButton: false,
            timer: 1500,
          });
          this.clearForm();
          this.getEmployee();
        })
        .catch((err) => console.log(err));
    },
    clearForm() {
      this.cam.name = "";
      this.cam.job_name = "";
      this.cam.manager_name = "";
      this.cam.hire_date = "";
      this.cam.salary = "";
      this.cam.commission = "";
    },
    clearFormEdit() {
      this.idEditEmployee = null;
      this.name = "";
      this.job_name = "";
      this.manager_name = "";
      this.hire_date = "";
      this.salary = "";
      this.commission = "";
    },
    clearFormDelete() {
      this.idDeleteEmployee = null;
    },
    clearFormShow() {
      this.idShowEmployee = null;
      this.name = "";
      this.job_name = "";
      this.manager_name = "";
      this.hire_date = "";
      this.salary = "";
      this.commission = "";
    },
    editEmployee(employee) {
      this.dialogEdit = true;
      this.idEditEmployee = employee.id;
      this.name = employee.name;
      this.job_name = employee.job_name;
      this.manager_name = employee.manager_name;
      this.hire_date = employee.hire_date;
      this.salary = employee.salary;
      this.commission = employee.commission;
    },
    cancelDialogEdit() {
      this.dialogEdit = false;
      this.idEditEmployee = null;
      this.name = "";
      this.job_name = "";
      this.manager_name = "";
      this.hire_date = "";
      this.salary = "";
      this.commission = "";
    },
    cancelDialogDelete() {
      this.dialogDelete = false;
      this.idDeleteEmployee = null;
    },
    cancelDialogShow() {
      this.clearFormShow();
      this.dialogShow = false;
    },

    async updateSubmit(id) {
      let formData = new FormData();
      formData.append("name", this.name);
      formData.append("job_name", this.job_name);
      formData.append("manager_name", this.manager_name);
      formData.append("hire_date", this.hire_date);
      formData.append("salary", this.salary);
      formData.append("commission", this.commission);

      const config = {
        method: "post",
        url: `/update_employee/${id}`,
        params: { _method: "PUT" },
        data: formData,
      };

      axios(config)
        .then((response) => {
          this.clearFormEdit();
          this.closeDialogEdit();
          Swal.fire("Updated", response.data, "success");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteEmployee(employee) {
      this.dialogDelete = true;
      this.idDeleteEmployee = employee.id;
    },
    deleteSubmit(id) {
      const config = {
        method: "post",
        url: `/destroy_employee/${id}`,
        params: { _method: "DELETE" },
      };

      axios(config)
        .then((response) => {
          this.clearFormDelete();
          this.closeDialogDelete();
          Swal.fire("Deleted", response.data, "success");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    showEmployee(employee) {
      this.dialogShow = true;
      this.idShowEmployee = employee.id;
      this.name = employee.name;
      this.job_name = employee.job_name;
      this.manager_name = employee.manager_name;
      this.hire_date = employee.hire_date;
      this.salary = employee.salary;
      this.commission = employee.commission;
    },
    closeDialogEdit() {
      this.dialogEdit = false;
      this.getEmployee();
    },
    closeDialogDelete() {
      this.dialogDelete = false;
      this.getEmployee();
    },
    getEmployee() {
      let url = `/all_employee?page=` + this.page;
      axios
        .get(url)
        .then((response) => {
          let { data } = response.data;
          this.employees = data;
          this.page = response.data.current_page;
          this.lengthPage = response.data.last_page;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
