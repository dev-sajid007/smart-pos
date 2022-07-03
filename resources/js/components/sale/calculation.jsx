import React, {Component} from 'react';

class Calculation extends Component {
    render() {
        return (
            <>
                <div className="col-sm-3">
                    <div className="bs-component">
                        <div className="card">
                            <h4 className="card-header bg-primary text-white">Total</h4>
                            <div className="card-body">
                                <div className="form-group">
                                    <label htmlFor="my-input">Sub Total</label>
                                    <input type="number" step={0.00}
                                           className="form-control{{ $errors->has('sub_total') ? ' is-invalid' : '' }}"
                                           name="sub_total" id="subTotal" defaultValue="{{ $sale->sub_total }}" required
                                           readOnly tabIndex={-1} placeholder="Subtotal" autoComplete="off"
                                           onKeyPress="return IsNumeric(event);" onDrop="return false;"
                                           onPaste="return false;"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="my-input">Discount (Deduction)</label>
                                    <input type="number" step={0.00} className="form-control" id="discount"
                                           defaultValue="{{ $sale->invoice_discount }}" name="invoice_discount"
                                           placeholder="Discount" autoComplete="off"
                                           onKeyPress="return IsNumeric(event);" onDrop="return false;"
                                           onPaste="return false;"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="my-input">Total Payable</label>
                                    <input type="number" step={0.00}
                                           className="form-control{{ $errors->has('grand_total') ? ' is-invalid' : '' }}"
                                           required name="total_payable" defaultValue="{{ $sale->total_payable }}"
                                           readOnly tabIndex={-1} id="totalAftertax" placeholder="Total"
                                           style={{fontSize: '24px', fontWeight: 'bolder', color: '#0d1214'}}
                                           autoComplete="off" onKeyPress="return IsNumeric(event);"
                                           onDrop="return false;" onPaste="return false;"/>
                                </div>
                                {/*@if($account_linked=1)*/}
                                <div className="form-group">
                                    <label htmlFor="my-input">Paid Amount</label>
                                    <input type="number" step={0.00}
                                           className="form-control{{ $errors->has('amount_paid') ? ' is-invalid' : '' }}"
                                           required name="paid_amount" defaultValue="{{ $sale->paid_amount }}"
                                           id="paid_amount" tabIndex={-1} placeholder="Paid Amount" autoComplete="off"
                                           onKeyPress="return IsNumeric(event);" onDrop="return false;"
                                           onPaste="return false;"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="my-input">Due Amount</label>
                                    <input type="number" step={0.00} readOnly tabIndex={-1}
                                           className="form-control amountDue" name="amountDue"
                                           defaultValue="{{ $sale->total_payable-$sale->receive_amount }}"
                                           id="amountDue" placeholder="Amount Due" autoComplete="off"
                                           onChange="changeAmountShow()"/>
                                </div>
                                @endif
                                <div className="form-group">
                                    <label htmlFor="my-input">Receive Amount</label>
                                    <input type="number" step={0.00}
                                           className="form-control{{ $errors->has('amount_paid') ? ' is-invalid' : '' }}"
                                           required name="receive_amount" defaultValue="{{ $sale->receive_amount }}"
                                           id="amountPaid" onKeyUp="amount()" min={0} placeholder="Receive Amount"
                                           autoComplete="off"
                                           style={{fontSize: '24px', fontWeight: 'bolder', color: '#0d1214'}}/>
                                </div>
                                <div className="row">
                                    <div className="col-sm-8">
                                        <button className="btn btn-sm btn-info btn-block">Update</button>
                                    </div>
                                    <div className="col-sm-4">
                                        <a href="{{ route('sales.index') }}"
                                           className="btn btn-sm btn-danger btn-block">List</a>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12">
                                        <div className="form-group">
                                            <div className="animated-radio-button mt-3">
                                                <label>
                                                    <input type="radio" name="print_type" defaultChecked
                                                           defaultValue={1}/><span
                                                    className="label-text">Pos Print</span>
                                                </label>
                                                &nbsp;
                                                &nbsp;
                                                &nbsp;
                                                <label>
                                                    <input type="radio" name="print_type" defaultValue={0}/><span
                                                    className="label-text">Normal Print</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="account_info">Account Information</label>
                                            <select name="account_information" id="account_info" className="select2">
                                                {/*{'{'}{'{'}--@foreach($account_infos as $account_info)--{'}'}{'}'}*/}
                                                {/*{'{'}{'{'}--{'{'}{'{'} $account_info-&gt;account_name .' , '.*/}
                                                {/*$account_info-&gt;account_no {'}'}{'}'}--{'}'}{'}'}*/}
                                                {/*{'{'}{'{'}--@endforeach--{'}'}{'}'}*/}
                                            </select>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="account_info">Transactions Id</label>
                                            <input type="text" name="transaction_id"
                                                   defaultValue="{{ $sale->transaction_id }}" className="form-control"
                                                   placeholder="Transactions Id"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}

export default Calculation;