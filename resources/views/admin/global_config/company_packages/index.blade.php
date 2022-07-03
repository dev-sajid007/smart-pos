@extends('admin.master')
@section('content')

<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Software Billing</h1>
            <p>Manage Software Billing Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item"><a href="#">Manage Software Billing</a></li>
          </ul>
        </div>
        <div class="row">
                <div class="col-md-12">
                  @if(Session::get('message'))
                  <div class="alert alert-success">
                    {{ Session::get('message') }}
                  </div>
                  @endif
                  @if(Session::get('error_message'))
                  <div class="alert alert-danger">
                    {{ Session::get('error_message') }}
                  </div>
                  @endif
                  <div class="tile">
                    <a href="{{route('company_packages.create')}}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i>Add Software Billing</a>
                    <h3 class="tile-title">Software Billing List </h3>
                    <div class="tile-body table-responsive">
                      <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                          <tr>
                            <th>Software Name</th>
                            <th>Billing Cycle</th>
                            <th>Trial Ends</th>
                            <th>Current Billing Ends</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($company_packages as $company_package)
                          <tr>
                              <td>{{ $company_package->company->name }}</td>
                              <td>{{ $company_package->billing_cycle->name }}</td>
                              <td>{{ $company_package->trial_ends_at }}</td>
                              <td>{{ $company_package->ends_at }}</td>
                              <td>{!! $company_package->status == 1 ? 
                              '<span class="badge badge-success">Approved</span>':
                              '<span class="badge badge-danger">Pending</span>'
                              !!}</td>
                              <td>{{ $company_package->amount }}</td>
                              <td>
                                  <a class="btn btn-primary btn-sm" title="View" href="{{ route('company_packages.show',$company_package->id) }}"> <i class="fa fa-eye"></i> </a> 
                                  @if($company_package->status != 1) 
                                  <a class="btn btn-info btn-sm" title="Edit" href="{{ route('company_packages.edit',$company_package->id) }}"> <i class="fa fa-edit"></i> </a>   
                                    <a class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{$company_package->id}}')" href="#"> <i class="fa fa-trash"></i> </a>
                                  
                                  
                                  <form action="{{route('company_packages.destroy', $company_package->id)}}" id="deleteForm_{{$company_package->id}}" method="POST">
                                      @csrf
                                      @method("DELETE")
                                  </form>
                                  @endif
                              </td>
                          </tr>
                            
                          @endforeach
                        </tbody>
                      </table>
                        @include('admin.includes.pagination', ['data' => $company_packages])
                    </div>
                  </div>
                </div>
              </div>
</main>


<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>


<script>
      function formSubmit(id)
      {
        swal({
          title: "Are you sure?",
          text: "You will not be able to recover this data !",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel plz!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function(isConfirm) {
          if (isConfirm) {
            $('#deleteForm_'+id).submit();
            swal("Deleted!", "Your data has been deleted.", "success");
          } else {
            swal("Cancelled", "Your data is safe :)", "error");
          }
        });
      }
     </script>
        @endsection