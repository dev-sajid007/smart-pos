@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> System Settings</h1>
                <p>Create Company Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Companies</li>
                <li class="breadcrumb-item"><a href="#">Add Company</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Update System Settings</h3>
                    <div class="tile-body">
                        <form class="form-horizontal">


                            <div class="form-group row">
                                <label class="control-label col-md-3">Currency Code</label>
                                <div class="col-md-8">
                                    <input name="currency_code" class="form-control" type="text"
                                           placeholder="Currency Code">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Product Tax</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="fk_product_tax_id">
                                        <optgroup label="Select Cities">
                                            <option>Disable</option>
                                            <option>No Tax</option>
                                            <option>Vat</option>
                                            <option>GST</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Invoice Tax</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="fk_invoice_tax_id">
                                        <optgroup label="Select Invoice Tax">
                                            <option>Disable</option>
                                            <option>No Tax</option>
                                            <option>Vat</option>
                                            <option>GST</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3">Default Discount</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="fk_default_discount_id">
                                        <optgroup label="Select Date Formate">
                                            <option>Code 25</option>
                                            <option>Code 39</option>
                                            <option>Code 128</option>
                                            <option>EAN 8</option>
                                            <option>UPC-A</option>
                                            <option>UPC-E</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3">Purches Prefix</label>
                                <div class="col-md-8">
                                    <input class="form-control " type="text" name="purchase_prefix"
                                           placeholder="Sale Ref Prefix">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Sales Prefix</label>
                                <div class="col-md-8">
                                    <input class="form-control " type="text" name="sales_prefix"
                                           placeholder="Quote Ref Prefix">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Quotation Prefix</label>
                                <div class="col-md-8">
                                    <input class="form-control " type="text" name="quotation_prefix" placeholder="Purchase Ref Prefix">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-3">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fa fa-fw fa-lg fa-check-circle"></i>Update Settings
                                        </button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i
                                                class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection
