<script type="text/javascript">
    document.addEventListener('keyup', function(e){
        if (e.altKey && e.keyCode == 83) {
            window.location.href = `{{ route('sales.create') }}`;
        }
    }, false);
</script>
