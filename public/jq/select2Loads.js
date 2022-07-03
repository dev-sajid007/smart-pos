

function select2Loads(settings){
    settings = $.extend({
        selector: '',
        url: '',
        placeholder: 'Enter text to Search',
        min: '1',
    }, settings);


    select2()

    function select2() {
        $(function(){
            $(settings.selector).select2({
                placeholder: settings.placeholder,
                minimumInputLength: settings.min,
                ajax: {
                    url: settings.url,
                    dataType: 'json',
                    quietMillis: 250,
                    data: params => {
                        return {
                            name: params.term,
                        }
                    },
                    processResults: (data) => {
                        return {
                            results: $.map(data, (item, index) => {
                                return {
                                    text: item,
                                    id: index
                                }
                            })
                        };
                    },
                    cache: true
                },
            });
        })
    }
}

