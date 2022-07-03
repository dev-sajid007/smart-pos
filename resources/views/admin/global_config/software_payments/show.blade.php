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
            <li class="breadcrumb-item"><a href="#">Show user</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Software Billing Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th><strong>Software Name</strong></th>
                  <td>{{$companyPackage->company->name}}</td>
                </tr>
                <tr>
                  <th><strong>Billing Name</strong></th>
                  <td>{{$companyPackage->package->name}}</td>
                </tr>
                <tr>
                  <th><strong>Trial Expires</strong></th>
                  <td>{{$companyPackage->trial_ends_at}}</td>
                </tr>
                <tr>
                  <th><strong>Validity Expires</strong></th>
                  <td>{{$companyPackage->ends_at}}</td>
                </tr>
                <tr>
                  <th><strong>Amount</strong></th>
                  <td>{{ $companyPackage->amount }}</td>
                </tr>
                <tr>
                  <th><strong>Status</strong></th>
                  <td>{!! $companyPackage->status != 1 ?
                  '<span class="badge badge-danger">Pending</span>':
                  '<span class="badge badge-success">Approved</span>' !!}
                  </td>
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