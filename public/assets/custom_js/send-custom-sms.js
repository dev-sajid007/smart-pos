
let sendingNumbers = ""

// getAvailableBalance()

// $('.students-number').keyup(function () {
//     countStudentsNumber()
//     countStudentCost()
// })

// $('.students-message').keyup(function () {
//     countStudentMessage()
//     countStudentCost()
// })

// function countStudentCost() {
//     let totalNumber = parseInt($('.total-student-numbers').text())
//     let totalMessage = parseInt($('.total-student-message-count').text())
//     $('.total-student-sms-cost').text(totalNumber * totalMessage)
//     checkStudentAvailableBalance()
// }

// function checkStudentAvailableBalance() {
//     let studentCost = parseInt($('.total-student-sms-cost').text())
//     let availableBalance = $('.available-balance').text()
//     if (studentCost > availableBalance) {
//         $('.send-sms-to-students').prop('disabled', true)
//         alert('Sorry you do not have sufficient balance')
//     } else {
//         $('.send-sms-to-students').prop('disabled', false)
//     }
// }

// function countStudentsNumber()
// {
//     let numbers = $('.students-number').val()
//     if (numbers == '') {
//         $('.total-student-numbers').text(0)
//     } else {
//         let count = (numbers.split(',')).length
//         $('.total-student-numbers').text(count)
//     }
// }

// function countStudentMessage()
// {
//     let length = $('.students-message').val().length
//     if(length < 1) {
//         count = 0
//     } else if (length <= 160) {
//         count = 1
//     } else if (length <= 320) {
//         count = 2
//     } else if (length <= 480) {
//         count = 3
//     } else {
//         count = 4
//     }
//     $('.total-student-message-count').text(count)
// }


// let sendingNumbers = "";

// $('.customerCheckboxAll').click(function () {
//     $('.customerCheckbox').prop('checked', $(this).is(':checked'))
// })
// $('.supplierCheckboxAll').click(function () {
//     $('.supplierCheckbox').prop('checked', $(this).is(':checked'))
// })

// $('.send-single-message').click(function () {
//     let message = $('.custom-message-area').val()
//     let sendableNumbers = getSendableNumbers()

//     if (message != "" && sendableNumbers != "") {
//         sendSmsViaAjax(message, sendableNumbers)
//     }
// })

// $('.send-sms-to-students').click(function () {
//     let message = $('.students-message').val()
//     let sendableNumbers = getStudentNumbers()

//     if (message != "" && sendableNumbers != "") {
//         sendSmsViaAjax(message, sendableNumbers)
//     }
// })

// $('.send-sms-to-employees').click(function () {
//     let message = $('.employee-message-area').val()
//     let sendableNumbers = getEmployeeNumbers()
//     if (message != "" && sendableNumbers != "") {
//         sendSmsViaAjax(message, sendableNumbers)

//     }
// })


// function sendSmsViaAjax(message, sendableNumbers) {
//     let status = 'Message Not Send'
//     let url = $('.ajax-send-sms-route').val()

//     $(sendableNumbers).each(function (index, numbers) {
//         $.ajax({
//             url: url,
//             type: 'GET',
//             data: {
//                 number: numbers,
//                 text: message
//             },
//             success: function (res) {
//                 status = "Message Send Successfully"
//                 $('.students-message').val('')
//                 $('.employee-message-area').val('')

//                 sendingNumbers = ""
//                 setAlertMessage()

//                 getAvailableBalance()
//             },
//             error: function (res) {
//                 status = 'Message Not Send'
//             }
//         });
//     })
// }



// function updateSmsBalance(smsCost) {
//     let url = $('.update-sms-balance-url').val()
//     $.ajax({
//         url: url + "?smsCost=" + smsCost,
//         type: 'GET',
//         success: function(res) {
//             if (res.balance) {
//                 $('.available-balance').text(res.balance)
//             }
//         }
//     });
// }

// function getSendableNumbers() {
//     let inputNumbers = $('.number-area').val() + ""
//     let data = inputNumbers.split(",")
//     let numberArray = [];
//     let numbers = "";
//     let numberCount = 0

//     $.each(data, function(key, item) {
//         let employeeNumber = item + ""
//         employeeNumber = employeeNumber.trim()
//         number = employeeNumber.substring(0, 2);
//         if (number != "88") {
//             let preset = "88"
//             if (employeeNumber.substring(0, 1) != "0") {
//                 preset += "0"
//             }
//             appendNumber(preset + "" + employeeNumber)
//             numberCount++
//             if (numberCount >= 50) {
//                 numberArray.push(sendingNumbers)
//                 sendingNumbers = ""
//             }
//         } else {
//             if (employeeNumber.substring(2, 3) != "0") {
//                 let output = [employeeNumber.slice(0, 3), "0", employeeNumber.slice(3)].join('');
//                 employeeNumber = output
//             }
//             if (employeeNumber.length == 13) {
//                 appendNumber(employeeNumber)
//                 numberCount++
//                 if (numberCount >= 50) {
//                     numberArray.push(sendingNumbers)
//                     sendingNumbers = ""
//                 }
//             }
//         }
//     });

//     if (numberCount > 0) {
//         numberArray.push(sendingNumbers)
//         sendingNumbers = ""
//     }
//     return numberArray
// }

// function getStudentNumbers() {
//     let inputNumbers = $('.students-number').val() + ""
//     let data = inputNumbers.split(",")
//     let numberArray = [];
//     let numbers = "";
//     let numberCount = 0
//     $.each(data, function(key, item) {
//         let studentNumber = item + ""
//         studentNumber = studentNumber.trim()
//         number = studentNumber.substring(0, 2);
//         if (number != "88") {
//             let preset = "88"
//             if (studentNumber.substring(0, 1) != "0") {
//                 preset += "0"
//             }
//             appendNumber(preset + "" + studentNumber)
//             numberCount++
//             if (numberCount >= 50) {
//                 numberArray.push(sendingNumbers)
//                 sendingNumbers = ""
//             }
//         } else {
//             if (studentNumber.substring(2, 3) != "0") {
//                 let output = [studentNumber.slice(0, 3), "0", studentNumber.slice(3)].join('');
//                 studentNumber = output
//             }
//             if (studentNumber.length == 13) {
//                 appendNumber(studentNumber)
//                 numberCount++
//                 if (numberCount >= 50) {
//                     numberArray.push(sendingNumbers)
//                     sendingNumbers = ""
//                 }
//             }
//         }
//     });

//     if (numberCount > 0) {
//         numberArray.push(sendingNumbers)
//         sendingNumbers = ""
//     }
//     return numberArray
// }

// function getEmployeeNumbers() {
//     let data = $('.employees[type="checkbox"]:checked')
//     let numberArray = [];
//     let numberCount = 0
//     $.each(data, function(key, item) {
//         let employeeNumber = $(item).data('employee-mobile') + ""
//         number = employeeNumber.substring(0, 2);
//         if (number != "88") {
//             let preset = "88"
//             if (employeeNumber.substring(0, 1) != "0") {
//                 preset += "0"
//             }
//             appendNumber(preset + "" + employeeNumber)
//             numberCount++
//             if (numberCount >= 50) {
//                 numberArray.push(sendingNumbers)
//                 sendingNumbers = ""
//             }
//         } else {
//             if (employeeNumber.substring(2, 3) != "0") {
//                 let output = [employeeNumber.slice(0, 3), "0", employeeNumber.slice(3)].join('');
//                 employeeNumber = output
//             }
//             if (employeeNumber.length == 13) {
//                 appendNumber(employeeNumber)
//                 numberCount++
//                 if (numberCount >= 50) {
//                     numberArray.push(sendingNumbers)
//                     sendingNumbers = ""
//                 }
//             }
//         }
//     });

//     if (numberCount > 0) {
//         numberArray.push(sendingNumbers)
//         sendingNumbers = ""
//     }
//     return numberArray
// }


$('.customerCheckboxAll').click(function () {
    $('.customerCheckbox').prop('checked', $(this).is(':checked'))
})

$('.supplierCheckboxAll').click(function () {
    $('.supplierCheckbox').prop('checked', $(this).is(':checked'))
})

function sendSmsToCustomNumbers()
{
    let message = $('.custom-message-area').val()
    let numbers = getSendableNumbers()


    if (message != "" && numbers != "") {
        sendSmsViaAjax(message, numbers)
        $('.custom-message-area').val('')
        $('.number-area').val('')
    }
}

function sendSmsToSuppliers()
{
    let message = $('.supplier-message-area').val()
    let numbers = getSupplierNumbers()

    if (message != "" && numbers != "") {
        sendSmsViaAjax(message, numbers)

        $('.supplierCheckbox').prop('checked', false)
        $('.supplier-message-area').val('')
    }
}

function sendSmsToCustomers()
{

    let message = $('.customer-message-area').val()
    let numbers = getCustomerNumbers()

    if (message != "" && numbers != "") {
        sendSmsViaAjax(message, numbers)
        $('.customerCheckbox').prop('checked', false)
        $('.customer-message-area').val('')
    }
}


function sendSmsViaAjax(message, numbers) {
    let status = 'Message Not Send'
    let url = $('.ajax-send-sms-route').val()

    $.ajax({
        url: url,
        type: 'GET',
        data: {
            numbers: numbers,
            text: message
        },
        success: function (res) {
            status = "Message Send Successfully"
            $('.students-message').val('')
            $('.employee-message-area').val('')

            sendingNumbers = ""
            setAlertMessage()

            getAvailableBalance()
        },
        error: function (res) {
            status = 'Message Not Send'
        }
    });
}

function getSendableNumbers() {

    let inputNumbers    = $('.number-area').val() + ""
    let data            = inputNumbers.split(",")

    $.each(data, function(key, item) {
        let employeeNumber  = item + ""
        employeeNumber      = employeeNumber.trim()
        number              = employeeNumber.substring(0, 2)

        if (number != "88") {
            let preset = "88"
            if (employeeNumber.substring(0, 1) != "0") {
                preset += "0"
            }
            appendNumber(preset + "" + employeeNumber)
        } else {
            if (employeeNumber.substring(2, 3) != "0") {
                let output = [employeeNumber.slice(0, 3), "0", employeeNumber.slice(3)].join('');
                employeeNumber = output
            }
            if (employeeNumber.length == 13) {
                appendNumber(employeeNumber)
            }
        }
    });

    return sendingNumbers
}

function appendNumber(numberss) {
    if (numberss.length == 13) {
        if (sendingNumbers.length > 0) {
            sendingNumbers += ","
        }
        sendingNumbers += numberss
    }
}


function setAlertMessage() {

    let alertMessage = ""

    alertMessage += '<div class=\"alert alert-success success\">'
    alertMessage += '<button type=\"button\" class=\"close\" data-dismiss=\"alert\">'
    alertMessage += '<i class=\"ace-icon fa fa-times\"></i>'
    alertMessage += '</button>'

    alertMessage += '<strong>'
    alertMessage += '<i class=\"ace-icon fa fa-check-circle\"></i>'
    alertMessage += "Success !"
    alertMessage += '</strong>'

    alertMessage += '<span class="success-alert-message"></span>'
    alertMessage += '</div>'

    $('.alert-message').html(alertMessage)
}


function getAvailableBalance() {
    $('.available-balance').text(0)
    $.ajax({
        url: $('.available-balance-url').val(),
        type: 'GET',
        success: function (res) {
            $('.available-balance').text(res['balance'])
        }
    })
}


function getSupplierNumbers() {
    let data = $('.supplierCheckbox[type="checkbox"]:checked')

    $.each(data, function(key, item) {
        let supplierNumber = $(item).data('supplier-mobile') + ""
        number = supplierNumber.substring(0, 2);

        if (number != "88") {
            let preset = "88"
            if (supplierNumber.substring(0, 1) != "0") {
                preset += "0"
            }
            appendNumber(preset + "" + supplierNumber)
        } else {
            if (supplierNumber.substring(2, 3) != "0") {
                let output      = [supplierNumber.slice(0, 3), "0", supplierNumber.slice(3)].join('');
                supplierNumber  = output
            }
            if (supplierNumber.length == 13) {
                appendNumber(supplierNumber)
            }
        }
    })

    return sendingNumbers
}


function getCustomerNumbers() {
    let data = $('.customerCheckbox[type="checkbox"]:checked')

    $.each(data, function(key, item) {
        let customerNumber = $(item).data('customer-mobile') + ""
        number = customerNumber.substring(0, 2)

        if (number != "88") {
            let preset = "88"
            if (customerNumber.substring(0, 1) != "0") {
                preset += "0"
            }
            appendNumber(preset + "" + customerNumber)
        } else {
            if (customerNumber.substring(2, 3) != "0") {
                let output = [customerNumber.slice(0, 3), "0", customerNumber.slice(3)].join('');
                customerNumber = output
            }
            if (customerNumber.length == 13) {
                appendNumber(customerNumber)
            }
        }
    })

    return sendingNumbers
}
