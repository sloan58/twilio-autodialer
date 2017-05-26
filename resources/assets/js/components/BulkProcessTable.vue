<template>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="#">Auto Dialer Bulk Jobs Status</a>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="content">
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Submitted On</th>
                                <th>Status</th>
                                <!--<th class="td-actions text-right" style="" data-field="actions"><div class="th-inner ">Actions</div><div class="fht-cell"></div></th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="file in bulkProcesses">
                                <td>
                                    <a href="/autodialer/bulk/" v-text="file.file_name">
                                    </a>
                                </td>
                                <td v-text="file.created_at">

                                </td>
                                <template v-if="file.status == 'Processing'">
                                <td>
                                    {{ file.status }}
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"  style="color:green"></i>
                                </td>
                                </template>
                                <td v-else v-text="file.status">

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<style>

</style>
<script>
    export default{

        props: {
            bulkProcesses: {
                type: Array,
                required: true
            }
        },
        data(){
            return{

            }
        },
        mounted() {
            this.listen();
        },
        components:{

        },
        methods: {
            listen() {
                Echo.channel('bulk-process-updated')
                    .listen('BulkProcessUpdated', (e) => {
                    var obj = _.findKey(this.bulkProcesses, { 'id': e.bulkFile.id });
                    this.bulkProcesses[obj].status = "FooBar";
                    console.log(e.bulkFile);
                    console.log(this.bulkProcesses[obj]);
                });
            },
    },
    computed: {

    },
}
</script>
