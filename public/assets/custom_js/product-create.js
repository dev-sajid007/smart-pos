

$('.expire-date').datepicker({
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
});

$('.expire-date').blur(function () {
    if ($(this).val().length > 10) {
        $(this).val(`{{ fdate(now(), 'd/m/Y') }}`)
    } else {

    }
});

$('.select2').select2();

$('#has_serial').click(function () {
    if ($(this).is(':checked')) {
        $('#opening_qty_field').val('')
        $('#opening_qty_div').hide()
        $('.generate-from-common').removeClass('d-none')
        $('#common_serial').prop('checked', true)
        $('.common-serial-row').removeClass('d-none')
    } else {
        $('#opening_qty_div').show()
        $('.product-serials').addClass('d-none')
        $('.generate-from-common').addClass('d-none')
        $('.common-serial-row').addClass('d-none')
        $('.input-product-serial').val('')
    }
})

$('#common_serial').click(function () {
    if ($(this).is(':checked')) {
        $('.product-serials').addClass('d-none')
        $('.common-serial-row').removeClass('d-none')
    } else {
        $('.product-serials').removeClass('d-none')
        $('.common-serial-row').addClass('d-none')
        $('.input-product-serial').val('')
    }
})

$('.save-btn').click(function () {
    let isSubmit = true
    if ($('#has_serial').is(':checked')) {
        let openingQuantity = parseInt($('.opening-quantity').val())
        if (openingQuantity > 0) {
            if ($('#common_serial').is(':checked')) {
                let commonSerial = $('.input-common-serial').val()
                let startFrom = $('.input-start-from').val()
                if (commonSerial == '' || startFrom == '') {
                    alert('Common serial or Start from is empty')
                    isSubmit = false
                }
            } else {
                if ($('.input-product-serial') == '') {
                    alert('First insert serial')
                    isSubmit = false
                } else {
                    let serials = ($('.input-product-serial').val() + "").split(",")
                    if (serials.length != openingQuantity) {
                        alert('Product serial not equal opening quantity')
                        isSubmit = false
                    }
                }
            }
        }
    }
    if (isSubmit) {
        $('#productCreateForm').submit()
    }
})

$("#reload_category").on('click', function () {

    $.ajax({
        url: '{{ url("categories/get_json_list") }}',
        type: 'GET',
        success: function (res) {
            if (res) {
                $('#fk_category_id').empty();
                const htmlString = `<option value="">Select One</option>`
                $.each(res, function (key, value) {
                    $('#fk_category_id').append(`<option value="${key}">${value}</option>`);
                });
            }
        }
    })

});

$('#fk_category_id').change(function () {

    var categoryId = $(this).val();
    if (categoryId) {

        $.ajax({
            type: "GET",
            url: $('.subcategory-url').val() + '/' + categoryId,
            success: function (res) {
                if (res) {
                    $("#fk_sub_category_id").empty();
                    $("#fk_sub_category_id").append('<option value="">Salect One</option>');
                    $(res).each(function (index, item) {
                        $("#fk_sub_category_id").append('<option value="' + item.id + '">' + item.sub_category_name + '</option>');
                    });
                } else {
                    $("#fk_sub_category_id").empty();
                }

            }
        });
    } else {
        $("#fk_sub_category_id").empty();
    }
});


$('#add_category_quick').on('click', function () {
    $('#add_category_quick_form').removeClass('d-none');
    $('#add_category_quick_form').slideToggle();
});


$('#btn_add_category').on('click', function () {
    var userId = $('#user_id_quick').val();
    var companyId = $('#company_id_quick').val();
    var categoryCode = $('#category_code_quick').val();
    var categoryName = $('#category_name_quick').val();
    var csrf_token = $('#csrf_token').val();
    var dataString = 'category_code=' + categoryCode + '&category_name=' + categoryName + '&user_id=' + userId + '&company_id=' + companyId + '&_token=' + csrf_token;

    if (categoryName != '') {

        $.ajax({
            type: 'POST',
            url: "{{ route('categories.store') }}",
            data: dataString,
            success: function (res) {
                $('#add_category_success').html('Category Added!');
            }
        });

    } else {
        $('#add_category_error').html('Category Name field is required!');
    }

});

function add_category_quick(category_name) {
    alert(category_name);
}
