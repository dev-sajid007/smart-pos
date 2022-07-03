@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Biller</h1>
            <p>Manage Biller Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Billers</li>
            <li class="breadcrumb-item"><a href="#">Show Biller</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Billers Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Biller Code</th>
                  <td>{{ $biller->biller_code }}</td>
                </tr>
                <tr>
                  <th>Biller Name</th>
                  <td>{{ $biller->name }}</td>
                </tr>
                <tr>
                  <th>Biller Email</th>
                  <td>{{ $biller->email }}</td>
                </tr>
                <tr>
                  <th>Biller Phone</th>
                  <td>{{ $biller->phone }}</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>{{ $biller->address }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        </div>
</main>

<script>
function confirmDelete(){
  var cnf=confirm('Are you sure?');
  if(cnf){
      $('#deleteForm').submit();
      return true;
  }else{
    return false;
  }
}
</script>
    
<!-- Data table plugin-->
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#sampleTable').DataTable();</script>

        @endsection