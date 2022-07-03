@extends('admin.master')

@section('content')
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
          <p>Software Payment Invoice</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Invoice</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <section class="invoice">
              <div class="row mb-4">
                <div class="col-6">
                  <h2 class="page-header"><i class="fa fa-globe"></i> {{ $software_payment->company->name }} </h2>
                </div>
                <div class="col-6">
                  <h5 class="text-right">Date: {{ $software_payment->software_payment_date }}</h5><br>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-6">
                  From <address><strong>{{ $from_company->name }}</strong><br>
                    Address :  {{ $from_company->address }}<br>
                    Phone : {{ $from_company->phone }}<br>
                    Email: {{ $from_company->email }}
                  </address>
                </div>
                <div class="col-6">To
                  <address><strong>{{ $software_payment->company->name }}</strong><br>
                    Address : {{ $software_payment->company->address }}<br>
                    Phone : {{ $software_payment->company->phone }}<br>
                    Email: {{ $software_payment->company->email }}
                  </address>  
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-bordered">
                      <tr>
                        <th>Invoice Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                      <tr>
                        <td>{{ $software_payment->software_payment_date }}</td>
                        <td>{{ $software_payment->paid_amount }}</td>
                        <td>{!! $software_payment->status ==1 ? 
                        '<label class="badge badge-success">Paid</label>':
                        '<label class="badge badge-danger">Unpaid</label>' !!}</td>
                      </tr>
                  </table>
                </div>
              </div>
              <div class="row d-print-none mt-2">
                <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print();" target="_blank"><i class="fa fa-print"></i> Print</a></div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </main>
    @endsection