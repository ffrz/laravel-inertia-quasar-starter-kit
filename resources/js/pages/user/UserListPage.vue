<template>
  <q-page class="row">
    <div class="column q-pa-lg">
      <q-table
        color="primary"
        v-model:pagination="pagination"
        :loading="isLoading"
        :columns="columns"
        :rows="users"
        @request="handleRequest"
        :rows-per-page-options="[10, 25, 50]"
      >
        <template #loading>
          <q-inner-loading showing color="red" />
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";

const users = ref([]);
const isLoading = ref(true);
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const columns = [
  { name: "name", label: "Name", field: "name", align: "left", sortable: true },
  {
    name: "email",
    label: "Email",
    field: "email",
    align: "left",
    sortable: true,
  },
  { name: "age", label: "Age", field: "age", align: "center", sortable: true },
];

onMounted(() => {
  fetchUsers();
});

const fetchUsers = (
  page = 0,
  rowsPerPage = 10,
  sortBy = "name",
  descending = false
) => {
  axios
    .get("user/data", {
      params: {
        page: page,
        per_page: rowsPerPage,
        order_by: sortBy,
        order_type: descending ? 'desc' : 'asc',
      },
    })
    .then((response) => {
      const data = response.data;
      console.log(data);
      users.value = data.data;
      pagination.value.page = data.current_page;
      pagination.value.rowsPerPage = data.per_page;
      pagination.value.rowsNumber = data.total;
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const handleRequest = (props) => {
  /// FIX ME: ga bisa balikin asc / desc
  fetchUsers(
    props.pagination.page,
    props.pagination.rowsPerPage,
    props.pagination.sortBy,
    props.pagination.descending
  );
};
</script>
