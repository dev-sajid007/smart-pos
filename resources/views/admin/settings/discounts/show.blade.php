@extends('admin.master')
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
            <li class="breadcrumb-item"><a href="#">Manage Company</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Bordered Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>No</th>
                  <td>{{$discount->id}}</td>
                </tr>

                <tr>
                  <th>Title</th>
                  <td>{{$discount->title}}</td>
                </tr>

                <tr>
                  <th>Discount</th>
                  <td>{{$discount->amount}}%</td>
                </tr>

                <tr>
                  <th>Type</th>
                  <td>
                    @if($discount->type == 0)
                        Percentage (%)
                      @elseif($discount->type == 1)
                        Fixed ($)
                      @endif
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