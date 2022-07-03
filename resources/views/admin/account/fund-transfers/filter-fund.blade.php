
<div class="row">
    <div class="col-md-12 no-print">
        <form action="" method="get">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="5%"></td>
                    <td class="no-print" width="20%">
                        <label class="control-label">Account Name</label>
                        <select name="account_id" class="form-control select2">
                            <option value="">All</option>
                            @foreach($accounts as $id => $name)
                                <option value="{{ $id }}" {{ $id == request('account_id') ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td class="no-print" width="8%">
                        <label class="control-label">Start Date</label>
                        <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d') }}" autocomplete="off">
                    </td>

                    <td class="no-print" width="8%">
                        <label class="control-label">End Date</label>
                        <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d') }}" autocomplete="off">
                    </td>


                    <td class="no-print" width="20%">
                        <div class="form-group" style="margin-top: 26px;">
                            <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                            <a href="{{ route('fund-transfers.index') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
                            <button type="button" class="btn btn-success" onclick="window.print()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
