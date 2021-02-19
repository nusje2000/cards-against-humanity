import React, {Component} from 'react';
import PropTypes from 'prop-types';

class White extends Component {
    static propTypes = {
        bottomText: PropTypes.string,
        revealed: PropTypes.bool.isRequired,
        disabled: PropTypes.bool.isRequired,
        highlighted: PropTypes.bool.isRequired,
        onClick: PropTypes.func
    };

    static defaultProps = {
        revealed: true,
        disabled: false,
        highlighted: false
    };

    render() {
        const isHighlighted = this.props.highlighted;
        const isDisabled = this.props.disabled;
        const canClick = !this.props.disabled && this.props.onClick;

        return (
            <div
                className={`card card__white ${isHighlighted ? 'card__highlight' : null} ${isDisabled ? 'card__disabled' : null} ${canClick ? 'cursor-pointer' : null}`}
                onClick={canClick ? this.props.onClick : null}>
                {this.props.revealed && this.props.children}

                {this.props.revealed && this.props.bottomText && <div className="card--bottom-text">
                    {this.props.bottomText}
                </div>}
            </div>
        )
    }
}

export default White;
