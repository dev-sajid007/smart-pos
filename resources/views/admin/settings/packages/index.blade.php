@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Package Information</h1>
          <p>Package information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Package Information</li>
          <li class="breadcrumb-item active"><a href="#">Package Information Table</a></li>
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
            <a href="{{route('packages.create')}}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i>Add Package</a>
            <h3 class="tile-title">Package List </h3>
            <div class="tile-body table-responsive">
              <table class="table table-hover table-bordered" id="">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Billing Cycle</th>
                    <th>Trial Period</th>
                    <th>Alert Before(Days)</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($packages as $package)
                  <tr>
                      <td>{{ $package->name }}</td>
                      <td>{{ $package->billing_cycle->name }}</td>
                      <td>{{ $package->trial_period }}</td>
                      <td>{{ $package->alert_before }}</td>
                      <td>

                          <a class="btn btn-info btn-sm" title="Edit" href="{{ route('packages.edit',$package->id) }}"> <i class="fa fa-edit"></i> </a> |
                          <a class="btn btn-primary btn-sm" title="View" href="{{ route('packages.show',$package->id) }}"> <i class="fa fa-eye"></i> </a> |
                            <a class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{$package->id}}')" href="#"> <i class="fa fa-trash"></i> </a>
                      
                      
                          <form action="{{route('packages.destroy', $package->id)}}" id="deleteForm_{{$package->id}}" method="POST">
                              @csrf
                              @method("DELETE")
                          </form>
                          
                      </td>
                    </tr>                        
                  @endforeach
                  
                </tbody>
              </table>

                @include('admin.includes.pagination', ['data' => $packages])
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

    @include('admin.includes.delete_confirm')


    @endsection