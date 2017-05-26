<script>
    @if(Session::has('info'))
        $.notify({
        icon: 'pe-7s-bell',
        message: '{!! Session::get('info') !!}'
    },{
        type: 'info',
        timer: 4000
    });
    @endif
    @if(Session::has('success'))
        $.notify({
        icon: 'pe-7s-bell',
        message: '{!! Session::get('success') !!}'
    },{
        type: 'success',
        timer: 4000
    });
    @endif
    @if(Session::has('warning'))
        $.notify({
        icon: 'pe-7s-bell',
        message: '{!! Session::get('warning') !!}'
    },{
        type: 'warning',
        timer: 4000
    });
    @endif
    @if(Session::has('danger'))
        $.notify({
        icon: 'pe-7s-bell',
        message: '{!! Session::get('danger') !!}'
    },{
        type: 'danger',
        timer: 4000
    });
    @endif
</script>