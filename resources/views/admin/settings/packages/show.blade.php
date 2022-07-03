@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Package</h1>
            <p>Manage Package Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Packages</li>
            <li class="breadcrumb-item"><a href="#">Show Package</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Packages Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Package Name</th>
                  <td>{{ $package->name }}</td>
                </tr>
                <tr>
                  <th>Package Billing Cycle</th>
                  <td>{{ $package->billing_cycle->name }}</td>
                </tr>
                <tr>
                  <th>Package Trial Period</th>
                  <td>{{ $package->trial_period }}</td>
                </tr>
                <tr>
                  <th>Alert Before(Days)</th>
                  <td>{{ $package->alert_before }}</td>
                </tr>
                <tr>
                  <th>Package Description</th>
                  <td>{{ $package->description }}</td>
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