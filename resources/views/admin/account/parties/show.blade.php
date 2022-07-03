@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Party</h1>
            <p>Manage Party Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Partys</li>
            <li class="breadcrumb-item"><a href="#">Show Party</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Partys Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Party Code</th>
                  <td>{{ $party->party_code }}</td>
                </tr>
                <tr>
                  <th>Party Name</th>
                  <td>{{ $party->name }}</td>
                </tr>
                <tr>
                  <th>Party Email</th>
                  <td>{{ $party->email }}</td>
                </tr>
                <tr>
                  <th>Party Phone</th>
                  <td>{{ $party->phone }}</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>{{ $party->address }}</td>
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