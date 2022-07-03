@extends('admin.master')
@section('title', ' - Chart Of Account Details')
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
                <div class="tile">
                    <h3 >Account Chart Detail</h3>
                    <div class="tile-body table-responsive" style="min-height: 440px">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Head Type</th>
                                    <td>
                                        @if($account_chart->head_type==0)
                                            Income
                                        @else
                                            Expanse
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Head Name</th>
                                    <td>{{$account_chart->head_name}}</td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($account_chart->status==1)
                                            <a href="{{route('charts.status',$account_chart->id)}}"  class="btn btn-success btn-sm">Active</a>
                                        @else
                                            <a href="{{route('charts.status',$account_chart->id)}}"  class="btn btn-warning btn-sm">Inactive</a>
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