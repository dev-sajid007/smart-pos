
<div class="row">
    <div class="col-md-12">
        <form action="" method="get">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="10%"></td>
                    <td class="no-print" width="20%">
                        <label class="control-label">Select Product </label>
                        <select name="product_id" class="form-control supplier_id select2">
                            <option value="">Select</option>
                            @foreach ($productList as $id => $name)
                                <option value="{{ $id }}" {{ $id == request('product_id') ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="no-print" width="20%">
                        <label class="control-label">Select Brand </label>
                        <select name="brand_id" class="form-control supplier_id select2">
                            <option value="">Select</option>
                            @foreach ($brands as $id => $name)
                                <option value="{{ $id }}" {{ $id == request('brand_id') ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="no-print" width="20%">
                        <label class="control-label">Select Category </label>
                        <select name="fk_category_id" class="form-control supplier_id select2">
                            <option value="">Select</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" {{ $id == request('fk_category_id') ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td width="20%" class="no-print">
                        <label class="control-label">Search by anything</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" autocomplete="off">
                    </td>

                    <td width="20%" class="no-print">
                        <div class="form-group" style="margin-top: 26px;">
                            <button class="btn btn-primary"><i class="fa fa-check"></i> Search </button>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
