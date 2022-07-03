@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Company</h1>
            <p>Manage Company Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item"><a href="#">Manage Accounts</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Payment Detail</h3>
            <div class="tile-body" style="min-height: 440px">
                <table class="table table-bordered">

                    <tbody>
                    <tr>
                        <th>Account Name</th>
                        <td>{{$payments->account_name}}, {{$payments->account_no}}</td>
                    </tr>



                    <tr>
                        <th>Method Name</th>
                        <td>{{$payments->method_name}}</td>
                    </tr>

                    <tr>
                        <th>Description</th>
                        <td>{{$payments->description}}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            @if($payments->status==1)
                                <a href="{{route('account.status',$payments->id)}}"  class="btn btn-success btn-sm">Active</a>
                            @else
                                <a href="{{route('account.status',$payments->id)}}"  class="btn btn-warning btn-sm">Inactive</a>
                            @endif
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
          </div>
        </div>

        </div>
</main>
@endsection