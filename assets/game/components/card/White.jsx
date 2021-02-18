import React, {Component} from 'react';
import PropTypes from 'prop-types';

class White extends Component {
    static propTypes = {
        text: PropTypes.string.isRequired,
        bottomText: PropTypes.string,
        revealed: PropTypes.bool.isRequired,
        highlighted: PropTypes.bool.isRequired,
        onClick: PropTypes.func
    };

    static defaultProps = {
        revealed: true,
        highlighted: false
    };

    render() {
        return (
            <div className={`card card__white ${this.props.highlighted && 'card__highlight'}`} onClick={this.props.onClick}>
                {this.props.revealed && this.props.text}

                {this.props.revealed && this.props.bottomText && <div className="card--bottom-text">
                    {this.props.bottomText}
                </div>}
            </div>
        )
    }
}

export default White;
