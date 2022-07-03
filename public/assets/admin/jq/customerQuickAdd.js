
    // Customer Create Section

    function resetCustomerForm()
    {
        $('.customerAddForm').trigger('reset');
    }

    $('.customerAddForm').submit(function (e) {
        e.preventDefault();

        let that = $(this)
        triggerPreloader(true)

        window.axios.post(`${that.attr('action')}`, that.serialize())
            .then(({data}) => {
                triggerPreloader(false)
                $('#addnew').modal('hide')
                setCustomer(data.customer)
                resetDue()
            })
            .catch((error) => {
                triggerPreloader(false)
                const errors = error.response.data.errors
                $.each(errors, (i, error) => {
                    $('.error-ul').append(` <li class="text-danger">${error[0]}</li> `)
                    that.delegate(':input', 'focus', function () {
                        $('.error-ul li').remove();
                    })
                })
            });
    })

    function resetDue()
    {
        $('#previous_due').val(0)
        $('#advanced_payment').val(0)
    }

    function triggerPreloader(isLoading = false){
        if(isLoading){
            $('.loading').css('display', 'flex')
        }else{
            $('.loading').css('display', 'none')
        }
    }

