import React, {Component} from 'react';
import ReturnModal from './../presenter/return-modal'

class ModalTriggerBtn extends Component{

    state = {
        show: false
    }

    handleClick = (e) => {
        return this.setState({show: this.state.show = true})
    }
    render() {
       return (
           <>
               <ReturnModal show={this.state.show} toggleModal={this.handleclick}/>

           </>
       )
    }
}

export default ModalTriggerBtn;