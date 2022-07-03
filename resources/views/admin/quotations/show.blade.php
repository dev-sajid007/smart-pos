@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Show Quotation</h1>
            <p>Show Quotation</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Quotations</li>
            <li class="breadcrumb-item"><a href="#">Show Quotation</a></li>
          </ul>
        </div>
        <div class="row">
        
          <div class="col-md-12">
          <div class="tile table-responsive">
            <h3 class="tile-title">Quotations Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Quotation Date</th>
                  <td>{{ $quotation->quotation_date }}</td>
                </tr>
                <tr>
                  <th>Quotation Reference</th>
                  <td>{{ $quotation->quotation_reference }}</td>
                </tr>
                <tr>
                  <th>Quotation Subtotal</th>
                  <td>{{ $quotation->sub_total }}</td>
                </tr>
                <tr>
                  <th>Quotation Discount</th>
                  <td>{{ $quotation->invoice_discount }}</td>
                </tr>
                <tr>
                  <th>Quotation Tax</th>
                  <td>{{ $quotation->invoice_tax }}</td>
                </tr>
                <tr>
                  <th>Quotation Customer</th>
                  <td>{{ $quotation->fk_customer_id }}</td>
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