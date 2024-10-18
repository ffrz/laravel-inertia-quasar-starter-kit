<template>
  <q-page>
    <div class="q-pa-md">
      <q-table ref="tableRef" flat bordered :dense="$q.screen.lt.md" color="primary" row-key="id" virtual-scroll
        v-model:pagination="pagination" :filter="filter" :loading="loading" :columns="columns" :rows="rows"
        :rows-per-page-options="[10, 25, 50]" @request="onRequest" binary-state-sort>

        <template v-slot:loading>
          <q-inner-loading showing color="red" />
        </template>

        <template v-slot:top-left>
          <div class="q-gutter-sm">
            <q-btn rounded dense color="primary" icon="add" @click="addUser" />
            <!--<q-btn no-caps color="grey-8" icon="archive" @click="exportToCsv" />-->
          </div>
        </template>

        <template v-slot:top-right>
          <q-input dense debounce="300" v-model="filter" placeholder="Search" clearable>
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

        <template v-slot:no-data="{ icon, message, filter }">
          <div class="full-width row flex-center text-grey-8 q-gutter-sm">
            <q-icon size="2em" name="sentiment_dissatisfied" />
            <span>
              Well this is sad... {{ message }}
              {{ filter ? " with term " + filter : "" }}</span>
            <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
          </div>
        </template>

        <template v-slot:body-cell-action="props">
          <q-td class="q-gutter-x-sm" align="center">
            <q-btn :disable="props.row.id == currentUser.id" rounded dense color="grey" icon="edit"
              @click="editUser(props.row)" />
            <q-btn :disable="props.row.id == currentUser.id" rounded dense color="red" icon="delete"
              @click="deleteUser(props.row)" />
          </q-td>
        </template>
      </q-table>
    </div>

    <q-dialog v-model="showUserEditor" persistent>
      <q-card style="min-width: 350px">
        <q-form class="q-gutter-md" @submit.prevent="submitForm">
          <q-card-section class="bg-grey-3">
            <div class="text-h6">
              <template v-if="editorMode == 'add'">
                Add User
              </template>
              <template v-else>
                Edit User
              </template>
            </div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            <input type="hidden" name="id" v-model="form.id">
            <q-input autofocus v-model.trim="form.name" label="Name" lazy-rules :error="!!form.errors.name"
              :error-message="form.errors.name" :rules="[
                (val) => (val && val.length > 0) || 'Please enter name.',
              ]" />
            <q-input v-model.trim="form.email" type="email" label="Email Address" lazy-rules
              :error="!!form.errors.email" :error-message="form.errors.email"
              :rules="[(val) => validateEmail(val) || 'Must be a valid email.']" />
            <q-input v-model="form.password" type="password" label="Password" lazy-rules :error="!!form.errors.password"
              :error-message="form.errors.password" />
          </q-card-section>
          <q-card-actions class="bg-grey-3 text-white" align="right">
            <q-btn type="submit" label="Save" color="primary" />
            <q-btn label="Cancel" v-close-popup color="grey-6" />
          </q-card-actions>
        </q-form>
      </q-card>

    </q-dialog>
  </q-page>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { exportFile, useQuasar } from "quasar";
import { usePage } from "@inertiajs/vue3";
import { useAPIForm } from "@/helpers/useAPIForm";

const page = usePage();
const currentUser = page.props.auth.user;
const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = ref("");
const showUserEditor = ref();
const editorMode = ref('add');
let currentRow = null;
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "name",
  descending: false,
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
  { name: "action", label: "Action", align: "center" },
];

let form = useAPIForm({
  id: 0,
  name: '',
  email: '',
  password: '',
})

onMounted(() => {
  fetchUsers();
});

const addUser = () => {
  form.id = null;
  form.clearErrors();
  editorMode.value = 'add';
  showUserEditor.value = true;
}

const editUser = (row) => {
  currentRow = row;
  form.clearErrors();
  editorMode.value = 'edit';
  showUserEditor.value = true;
  form.id = row.id;
  form.name = row.name;
  form.email = row.email;
};

const submitForm = () => {
  form.clearErrors();
  form.submit(
    editorMode.value == 'add' ? 'post' : 'put',
    'users' + (editorMode.value == 'add' ? '' : '/' + form.id),
    {
      preserveScroll: true,
      onSuccess: (response) => {
        showUserEditor.value = false;
        form.reset();
        $q.notify(response.message);

        if (editorMode.value == 'add') {
          rows.value.unshift(response.data);
        }
        else {
          currentRow.name = response.data.name;
          currentRow.email = response.data.email;
        }
      }
    }
  );
};

function validateEmail(email) {
  return /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(email);
}


const deleteUser = (row) => {
  $q.dialog({
    title: "Confirm",
    icon: "question",
    message: "Are you sure to delete " + row.email + "?",
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .delete("users/" + row.id)
      .then((response) => {
        var removeIndex = rows.value.map((item) => item.id).indexOf(row.id);
        ~removeIndex && rows.value.splice(removeIndex, 1);
        $q.notify("User " + row.email + " has been deleted.");
      })
      .finally(() => {
        loading.value = false;
      })
      .catch((error) => {
        if (error && error.status == 409) {
          $q.notify({
            message: error.response.data.message
              ? error.response.data.message
              : error.message,
            color: "red",
          });
        }
        console.log(error);
      });
  });
};

const fetchUsers = (
  props = {
    page: 1,
    rowsPerPage: 10,
    sortBy: "name",
    descending: false,
  },
  filter = ""
) => {
  const { page, rowsPerPage, sortBy, descending } = props;
  axios
    .get("users", {
      params: {
        page: page,
        per_page: rowsPerPage,
        order_by: sortBy,
        order_type: descending ? "desc" : "asc",
        filter: filter,
      },
    })
    .then((response) => {
      const data = response.data;
      rows.value = data.data;
      pagination.value.page = data.current_page;
      pagination.value.rowsPerPage = data.per_page;
      pagination.value.rowsNumber = data.total;
      pagination.value.sortBy = sortBy;
      pagination.value.descending = descending;
    })
    .finally(() => {
      loading.value = false;
    });
};

const onRequest = (props) => {
  fetchUsers(props.pagination, props.filter);
};

function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`;
}

const exportToCsv = () => {
  const content = [columns.map((col) => wrapCsvValue(col.label))]
    .concat(
      rows.value.map((row) =>
        columns
          .map((col) =>
            wrapCsvValue(
              typeof col.field === "function"
                ? col.field(row)
                : row[col.field === void 0 ? col.name : col.field],
              col.format,
              row
            )
          )
          .join(",")
      )
    )
    .join("\r\n");

  const status = exportFile("table-export.csv", content, "text/csv");

  if (status !== true) {
    $q.notify({
      message: "Browser denied file download...",
      color: "negative",
      icon: "warning",
    });
  }
};
</script>
