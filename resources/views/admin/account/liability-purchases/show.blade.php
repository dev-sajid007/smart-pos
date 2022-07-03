
@extends('admin.master')
@section('title', ' - Liability Purchase')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <a href="{{ route('liability-purchases.index') }}" class="btn btn-success pull-right">
                        <i class="fa fa-list"></i> List
                    </a>

                    <h3><i class="fa fa-th-list"></i> Liability Purchase View</h3>

                    <div class="tile-body" style="height: 740px !important;">


                        <div class="tile-body table-responsive">
                            <h5>Date: {{ $liabilityPurchase->date }}</h5>
                            <h5>Description: {{ $liabilityPurchase->description }}</h5>
                            
                            <table class="table table-hover table-bordered table-sm" id="sampleTable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Liability</th>
                                        <th>Particular</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($liabilityPurchase->details as $key => $detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ optional($detail->asset)->name }}</td>
                                            <td>{{ $detail->particular }}</td>
                                            <td class="text-right">{{ $detail->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3"><strong>Total:</strong></th>
                                        <th class="text-right">{{ number_format($liabilityPurchase->amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
