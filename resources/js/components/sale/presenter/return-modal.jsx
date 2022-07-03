import React, {Component, useState} from 'react';
import {Modal, Button} from 'react-bootstrap'

// class ReturnModal extends Component {
//
//
//     render() {
//         let show = this.props.show;
//
//         return (
//
//             <>
//                 {/*<Modal*/}
//                     {/*size="lg"*/}
//                     {/*// show={show}*/}
//                     {/*// onHide={show}*/}
//                     {/*aria-labelledby="example-modal-sizes-title-lg"*/}
//                 {/*>*/}
//                     {/*<Modal.Header closeButton>*/}
//                         {/*<Modal.Title id="example-modal-sizes-title-lg">*/}
//                             {/*Return Product*/}
//                         {/*</Modal.Title>*/}
//                     {/*</Modal.Header>*/}
//                     {/*<Modal.Body>...</Modal.Body>*/}
//                 {/*</Modal>*/}
//             </>
//         );
//     }
// }
// export default ReturnModal;



const ReturnModal = (props) => {
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    console.log(props.show)

    return (
        <>
            {/*<Button variant="primary" onClick={handleShow}>*/}
                {/*Launch demo modal*/}
            {/*</Button>*/}
            <Button
                type="button"
                onClick={props.handleClick}
                onClick={handleShow}
                tabIndex={-1}
                data-toggle="modal"
                className="btn btn-primary btn-sm issue-btn "
            >
                <i className="fa fa-refresh"/>
            </Button>

            <Modal
                show={show}
                onHide={handleClose}
                size="lg"
            >
                <Modal.Header closeButton>
                    <Modal.Title>Return Product</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    Woohoo, you're reading this text in a modal!
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Close
                    </Button>
                    <Button variant="primary" onClick={handleClose}>
                        Save Changes
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    );
}
export default ReturnModal;