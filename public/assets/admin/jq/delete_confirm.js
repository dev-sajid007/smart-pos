
function delete_item(url) {
    Swal.fire({
        title: 'Are you sure ?',
        html: "<b>You want to delete permanently !</b>",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        width: 400,
    }).then((result) => {
        if (result.value) {
            $('#deleteItemForm').attr('action', url).submit();
        }
    })
}