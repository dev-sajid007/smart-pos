import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import App from './app'

export default class Example extends Component {
    render() {
        return (
            <>
                <App />
            </>
        );
    }
}

if (document.getElementById('sale-return-root')) {
    ReactDOM.render(<Example />, document.getElementById('sale-return-root'));
}
