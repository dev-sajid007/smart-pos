import React, {Component} from 'react';
import SoldItems from "./sold-items";
import Axios from "axios";

class SaleWrapper extends Component {

    state = {
        items: [],
        customerName: '',
        saleId: ''
    }

    componentDidMount() {
        Axios.post('/sale-returns/create', {id: 43})
            .then(response => {
                this.setState({
                    items: this.state.items = response.data.sale_details,
                    customerName: this.state.customerName = response.data.customer.name,
                    saleId: this.state.saleId = response.data.id

                })
            })
            .catch(err => console.log(err))
        // console.log(this.state)
    }


    render() {
        return (
            <div className="col-md-9">
                <input type="hidden" name="id" defaultValue="{}"/>
                {/*<input type="hidden" name="sale_id" defaultValue="{}" />*/}
                <input type="hidden" name="sale_date" defaultValue="{{ date('Y-m-d') }}"/>
                <div className="bs-component">
                    <div className="card">
                        <h4 className="card-header bg-primary text-white">
                            <span className="float-left">Return Sale</span>
                            <span className="float-right" id="changeAmount"/>
                        </h4>
                        <div className="card-body">
                            <div className="row d-flex">
                                <div className="col-md-4 ml-0">
                                    <div className="form-group">
                                        <label htmlFor="customer" className="h5">Customer Name</label>
                                        <input type="text" id="customer" name="customer" required readOnly
                                               value={this.state.customerName}
                                               className="form-control" placeholder="Search Customer.."/>
                                        {/*<input type="text" id="id" name="fk_customer_id" defaultValue="{{ $sale->fk_customer_id }}" className="form-control" style={{display: 'none'}} />*/}
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <SoldItems items={this.state.items}/>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default SaleWrapper;