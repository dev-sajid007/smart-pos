@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i>
                    @if($dueCollection->isSupplier())
                        Payment
                    @else
                        Collection
                    @endif
                    Invoice
                </h1>
            </div>
            <div>
                <a class="btn btn-primary" href="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
                <a class="btn btn-danger" href="{{ route('due-collections.index') }}?type={{ request('type') }}"><i class="fa fa-backward"></i>  Back To List</a>
            </div>

        </div>

        <div class="row" id="printDiv">
            <div class="col-md-12">
                <div class="tile">

                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-left">{{$company->name}}</h3>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-right">
                                <span class="text-secondary">Date :</span>
                                {{ $dueCollection->created_at->format('d/m/Y') }}
                            </h4>
                            <h4 class="text-right"> <span class="text-secondary">Invoice ID :</span>
                                {{$dueCollection->invoiceId}}</h4>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6">
                            From
                            <address><strong>{{ $company->name }}</strong><br>
                                Address : {{ $company->address }}<br>
                                Phone : {{ $company->phone }}<br>
                                Email: {{ $company->email }}
                            </address>
                        </div>
                        <div class="col-md-6 text-right">
                            To
                            <address>
                                <strong>{{ $dueCollection->transactionable->name }}</strong><br>
                                Address : {{ $dueCollection->transactionable->address }}<br>
                                Phone : {{ $dueCollection->transactionable->phone }}<br>
                                Email: {{ $dueCollection->transactionable->email }}
                            </address>
                        </div>
                    </div>


                    <table class="table table-bordered table-sm mb-5" style="border: none">
                        <thead>
                            <tr>
    {{--                            <th colspan="5">Purchase Reference : {{ $purchase->purchase_reference }}</th>--}}
                            </tr>
                            <tr>
                                <th width="1%">SL</th>
                                <th>Purpose</th>
                                <th class="text-right">Previous Due</th>
                                <th width="20%" class="text-right">Paid Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    {{$dueCollection->isSupplier() ? 'Payment' : 'Receive'}}
                                </td>
                                <td class="text-right">{{ number_format(abs($dueCollection->previous_due), 2) }}</td>
                                <td class="text-right">{{ number_format(abs($dueCollection->amount), 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{ ucwords(inWords(abs($dueCollection->amount))) }}</td>
                            </tr>
                        </tbody>

                        <tfoot style="border: none">
                            <th colspan="4" style="border: none">Note: {{ $dueCollection->note ?? '' }}</th>
                        </tfoot>
                    </table>
                    <p class="text-right" style="margin-top: -60px">
                        <b>Balance : TK.
                            {{
                                number_format($dueCollection->previous_due != '' ?
                                ($dueCollection->previous_due - abs($dueCollection->amount)) :
                                $dueCollection->transactionable->currentBalance, 2)
                            }}
                        </b>
                    </p>

                    <div class="row mt-5 mb-5">
                        <div class="col-md-4">
{{--                            <b> Supplier : {{ $purchase->purchase_supplier->name }} </b><br>--}}
                            <hr>
                            Signature and Date
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
{{--                            <b>Issued By : {{ $purchase->purchase_company->name }} </b> <br>--}}
                            <hr>
                            Signature and Date
                        </div>
                    </div>


                    <br>
                    <br>
                    <h3 class="text-center mt-5">Thanks For Coming</h3>
                    <p class="text-center">Powered By: Smart Software, 88 01844047001</p>
                </div>
            </div>
        </div>

    </main>

@endsection


@section('js')

    <script type="text/javascript">

        @if(session('success'))
        setTimeout(() => { window.print() }, 3000)
        @else
        // window.print()
        @endif


    </script>


@stop
