@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Biller Information</h1>
          <p>Biller information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Biller Information</li>
          <li class="breadcrumb-item active"><a href="#">Biller Information Table</a></li>
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
            <h3 class="tile-title">Biller List </h3>
            <div class="tile-body table-responsive">
              <table class="table table-hover table-bordered" id="">
                <thead>
                  <tr>
                    <th>Biller Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($billers as $biller)
                  <tr>
                      <td>{{ $biller->biller_code }}</td>
                      <td>{{ $biller->name }}</td>
                      <td>{{ $biller->email }}</td>
                      <td>{{ $biller->phone }}</td>
                      <td>{{ $biller->address }}</td>
                      <td>
                          <a href="{{ route('billers.edit', $biller->id) }}"><i class="fa fa-edit"></i></a>
                          <a href="{{ route('billers.show', $biller->id) }}"><i class="fa fa-eye"></i></a>
                          <a href="#" data-toggle="modal" onclick="deleteData({{ $biller->id }})" 
                            data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>                        
                  @endforeach
                  
                </tbody>
              </table>

                @include('admin.includes.pagination', ['data' => $billers])
            </div>
          </div>
        </div>
      </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script type="text/javascript">
        function deleteData(id)
        {
            var id = id;
            var url = '{{ route("billers.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit()
        {
         $("#deleteForm").submit();
        }
      </script>

@include('admin.includes.delete_confirm')

    @endsection