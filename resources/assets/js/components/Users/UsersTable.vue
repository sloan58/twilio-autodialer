<template>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">Users</li>
                    </ol>
                    <div class="card">
                        <div class="header">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="#">Users</a>
                                    </div>
                                    <div class="collapse navbar-collapse">
                                        <ul class="nav navbar-nav navbar-right pull-right">
                                            <button type="button" class="btn btn-success btn-wd bootstrap-modal-form-open" data-toggle="modal" data-target="#addUserModal">
                                                <span class="btn-label">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                                <b>Add User</b>
                                            </button>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content">
                            <div class="toolbar">
                                <!--        Here you can write extra buttons/actions for the toolbar              -->
                            </div>

                            <div class="fresh-datatables">
                                <div class="dataTables_wrapper form-inline dt-bootstrap">

                                    <vue-table-filter-bar></vue-table-filter-bar>

                                    <div class="row">
                                        <div class="col-sm-12">

                                            <vuetable ref="vuetable"
                                                      :api-url="url"
                                                      :fields="fields"
                                                      :css="css"
                                                      pagination-path=""
                                                      :sort-order="sortOrder"
                                                      :append-params="moreParams"
                                                      @vuetable:pagination-data="onPaginationData"
                                            ></vuetable>

                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="dataTables_info">

                                                        <vuetable-pagination-info ref="paginationInfo"
                                                                                  info-class="pull-left"
                                                        ></vuetable-pagination-info>

                                                    </div>
                                                </div>
                                                <div class="col-sm-7">
                                                    <div class="dataTables_paginate paging_simple_numbers">

                                                        <vuetable-pagination ref="pagination"
                                                                             :css="cssPagination"
                                                                             :icons="icons"
                                                                             @vuetable-pagination:change-page="onChangePage"
                                                        ></vuetable-pagination>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- end content-->
                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
        </div>
    </div>
</template>

<script>

export default {

  mounted() {

  },
  components: {
  },
  data () {
  	return {
  	  url: "/users",
      css: {
        tableClass: 'table table-striped table-bordered',
        loadingClass: 'loading',
        ascendingIcon: 'glyphicon glyphicon-chevron-up',
        descendingIcon: 'glyphicon glyphicon-chevron-down',
        sortHandleIcon: 'glyphicon glyphicon-menu-hamburger',
      },
      cssPagination: {
        wrapperClass: 'pagination pull-right',
        activeClass: 'btn-primary',
        disabledClass: 'disabled',
        pageClass: 'btn btn-border',
        linkClass: 'btn btn-border',
      },
      icons: {
        first: '',
        prev: '',
        next: '',
        last: '',
      },
      fields: [
        {
          name: 'name',
          sortField: 'name'
        },
        {
          name: 'email',
          sortField: 'email'
        },
        {
          name: '__component:users-table-actions',
          title: 'Actions',
          titleClass: 'text-center',
          dataClass: 'text-center'
        }
      ],
      sortOrder: [
        {
          field: 'name',
          sortField: 'name',
          direction: 'asc'
        }
      ],
      moreParams: {

      },
  	}
  },
  methods: {
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
      this.$refs.paginationInfo.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    }
  },
  events: {
    'filter-set' (filterText) {
      this.moreParams = {
        'filter': filterText
      }
      Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    'filter-reset' () {
      this.moreParams = {}
      this.$refs.vuetable.refresh()
      Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    'user-deleted' (userId) {
        Vue.nextTick( () => this.$refs.vuetable.refresh())
    }
  }
}
</script>