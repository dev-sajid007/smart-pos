
        <h4 class="text-center d-print-none " style="font-size: 16px;padding: 5px 0px 10px 10%; margin: 0px !important;height: 35px;">
            Your Subscription amount:&nbsp;
            @php
                $tests = App\SoftwarePayment::where('status', 0)->get();
                foreach ($tests as $key=>$test) {
                    $fdate      = $test->software_payment_date;
                    $tdate      = date('Y-m-d');
                    $datetime1  = new DateTime($fdate);
                    $datetime2  = new DateTime($tdate);
                    $interval   = $datetime1->diff($datetime2);
                    $days       = $interval->format('%a');//now do whatever you like with $days

                    if ($test->alert >= $days || $fdate <= $tdate) {
                        $amount = $test->amount;
                        $id     = $test->id;

                        echo '<style>#alert_module{display:block !important;}</style>';
                        echo '<blockquote class="text-warning" style="font-size:16px; display: inline-table;">'.
                                $test->amount.'&nbsp;Tk.
                                <span class="text-white">
                                    &nbsp;Till:&nbsp;
                                </span>'
                                .$test->software_payment_date.
                             '</blockquote>,&nbsp;';
                    }
                    else {}
                }
                $company = App\company::where('id',1)->first();
            @endphp
            You can pay it by
            <form action="{{ url('https://www.smartsoftware.com.bd/pay') }}" method="post" target="_blank" class="d-print-none " style="display: inline-block;">
                @csrf
                <input type="hidden" name="order_id" value="{{ $id??'' }}">
                <input type="hidden" name="customer_name" value="{{ $company->name }}">
                <input type="hidden" name="customer_email" value="{{ $company->email }}">
                <input type="hidden" name="customer_mobile" value="{{ $company->phone }}">
                <input type="hidden" name="customer_address_1" value="{{ $company->address }}">
                <input type="hidden" name="customer_city" value="Dhaka">
                <input type="hidden" name="customer_state" value="Dhaka">
                <input type="hidden" name="customer_postcode" value="1100">
                <input type="hidden" name="domain" value="{{ request()->getHost() }}">
                <input type="hidden" name="amount" value="{{ $amount??0 }}">
                <input type="hidden" name="success_url" value="{{ url('software_payments/paid') }}">
                <input type="hidden" name="fail_url" value="{{ url('software_payments/paid') }}">
                <input type="hidden" name="opt_a" value="{{ url('home') }}">
                <style>
                    .pay-submit-buttton{
                        background: transparent;
                        color     : yellow;
                        font-size : 16px;
                        border    : none;
                        cursor    : pointer;
                    }
                    .pay-submit-buttton:hover{
                        color: rgb(190, 190, 31);
                    }
                    .pay-submit-buttton:active{
                        border:none;
                    }
                </style>
                <input type="submit" value="Clicking Here" class="pay-submit-buttton">
            </form>
        </h4>


