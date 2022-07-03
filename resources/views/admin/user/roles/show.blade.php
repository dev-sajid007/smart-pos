@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Role</h1>
            <p>Manage Role Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Roles</li>
            <li class="breadcrumb-item"><a href="#">Show Role</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Roles Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Role Name</th>
                  <td>{{ $Role->name }}</td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td>{{ $Role->description }}</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td>{{ $Role->status == 1 ? 'Active':'Inactive' }}</td>
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