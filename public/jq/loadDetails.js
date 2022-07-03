
function loadDetails(settings){

    settings = $.extend({
        type: 'name',
        selector: '',
        url: '',
        select: function () {},
        search: function () {}
    }, settings);

    $(document).on('focus', settings.selector, function () {
        $(this).autocomplete({
            source: function(request, response){
                $.getJSON(settings.url,
                    {name: request.term},
                    function (data) {
                        response($.map(data, function (item) {
                            return searchLabel(item, settings.type);
                        }))
                    }
                )
            },
            select: function(event, ui){
                settings.select(event, ui);
            },
            search: function( event, ui ) {
                settings.search(event);
            },
            minLength: 1,
            autoFocus: true
        })
    })
}



function searchLabel(item, type)
{
    let value, label;
    if (type == 'name'){
        value = item.name
        label = item.name
    }
    if (type == 'nameWithNumber') {
        value =  item.name +' ('+item.mobile_number +')';
        label =  item.name +' ('+item.mobile_number +')';
    }

    if (type == 'nameWithQuantity') {
        value =  item.name;
        label =  item.name +' ['+item.product_code +'] ' + ' - ('+item.retail_quantity +')';
    }

    if (type == 'cardNumber') {
        value = item.card_number;
        label = item.card_number;
    }

    return {
        value,
        label,
        data: item,
    }
}
