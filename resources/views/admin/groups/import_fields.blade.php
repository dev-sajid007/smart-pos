@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Group Information</h1>
          <p>Group information</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Group Information</li>
          <li class="breadcrumb-item active"><a href="#">Group Information Table</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
            @if(Session::get('message'))
            <div class="alert alert-success">
              {{ Session::get('message') }}
            </div>
            @endif
          
          <div class="tile">
            <h3 class="tile-title">Group List</h3>
            <div class="tile-body">
                <form action="{{ url('groups/process_import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    
                    <input type="hidden" name="csv_file_id" value="{{ $csv_data_file->id }}">
                    <input type="hidden" name="fk_company_id" value="{{ $company_id_session }}">
                    <input type="hidden" name="fk_created_by" value="{{ $user_id_session }}">
                    <input type="hidden" name="fk_updated_by" value="{{ $user_id_session }}">
                    <input type="hidden" name="group_id_db" value="{{ $group_id_db }}">

                        <div class="form-group">
                            <div class="form-control">
                                    <button class="btn btn-primary" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Import Group</button>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                @foreach (config('app.import_fields_group') as $field)    
                                    <th>{{ $field }}</th>
                                @endforeach
                            </tr>  
                        </thead>
                        <tbody>
                            @foreach ($csv_data as $row)
                                    <tr>
                                        @foreach ($row as $key=>$value)      
                                        <td>{{ $value }}</td>
                                        @endforeach 
                                    </tr>             
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>
     

    <script type="text/javascript">$('#sampleTable').DataTable();</script>



    @include('admin.includes.delete_confirm')

    @endsection