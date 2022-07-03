

// change from account option
$('.from-account').change(function () {
    let toAccount = $('.to-account').val()
    if ($(this).val() == toAccount) {
        alert('Both account can not be same')
        $(".from-account").select2().val('').trigger("change");
    }
    $('.select-amount').val('')
})


//  change to account option
$('.to-account').change(function () {
    let fromAccount = $('.from-account').val()
    if ($(this).val() == fromAccount) {
        alert('Both account can not be same')
        $(".to-account").select2().val('').trigger("change");
    }
    $('.select-amount').val('')
})


// type on amount
$('.select-amount').keyup(function () {
    let fromAccountAmount = $('.from-account option:selected').data('total-amount')

    if (Number($(this).val()) > Number(fromAccountAmount)) {
        alert('You can not transfer greater than available amount (' + fromAccountAmount + ')')
        $(this).val(fromAccountAmount)
    }
})
