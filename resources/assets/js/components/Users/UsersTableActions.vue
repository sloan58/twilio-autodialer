<template>
    <div class="custom-actions">
        <!--<a :href="href" class="btn btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>-->
        <button class="btn btn-warning" @click="navigate('edit-item', rowData, rowIndex)"><i class="fa fa-user-secret"></i>Impersonate</button>
    </div>
</template>

  <script>
  export default {
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    data() {
        return {

        }
    },
    methods: {
      navigate(action, data, index) {
        location.href = "/users/impersonate/" + this.rowData.id
      }
    }
  }
  </script>

  <style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
  </style>