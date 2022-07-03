@extends('admin.master')
@section('content')
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Manage Group</h1>
      <p>Manage Group Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Groups</li>
      <li class="breadcrumb-item"><a href="#">Show Group</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile table-responsive">
        <h3 class="tile-title">Groups Table</h3>
        <table class="table table-bordered">

          <tbody>
            <tr>
              <th>Group Code</th>
              <td>{{ $group_info->group_code }}</td>
            </tr>
            <tr>
              <th>Group Name</th>
              <td>{{ $group_info->name }}</td>
            </tr>
            <tr>
              <td colspan="2"> <b>Group Members</b> 
                <table class="table mt-2">
                  @foreach ($group_contacts as $group_contact)
                  <tr>
                    <td>{{ $group_contact->phone }}</td>
                  </tr>
                  @endforeach
                </table>
                {{ $group_contacts->links() }}
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
<script type="text/javascript">
  $('#sampleTable').DataTable();
</script>

@endsection