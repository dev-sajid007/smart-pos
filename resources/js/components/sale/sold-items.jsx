import React, {Component} from 'react';
import ModalTriggerBtn from './controller/modal-trigger-btn'

class SoldItems extends Component {

    render() {
        let items = this.props.items;
        return (
            <>
                <div className="col-md-12">
                    <div className="table-responsive">
                        <table className="table table-bordered table-sm" id="table">
                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th width="15%">Price</th>
                                {/*<th>Tax</th>*/}
                                <th width="12%">QTY</th>
                                <th style={{ width: "15%" }}>Subtotal</th>
                                <th width="1%" />

                            </tr>
                            </thead>
                            <tbody>
                            {items.map((data, index) => {
                                return (
                                    <tr key={data.id}>
                                        <td>
                                            {data.product.product_name}
                                        </td>
                                        <td>
                                            {data.unit_price}
                                        </td>
                                        <td hidden>
                                            {data.product.tax}}*/}
                                        </td>
                                        <td>
                                            {data.quantity}
                                        </td>

                                        <td>
                                            {data.product_sub_total}
                                        </td>

                                        <td className="d-flex">
                                            <div className="btn-group">
                                                <ModalTriggerBtn data={data}/>
                                            </div>
                                        </td>
                                    </tr>
                                )
                            })}
                            </tbody>
                        </table>
                    </div>
                </div>
            </>
        );
    }
}

export default SoldItems;