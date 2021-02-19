import React, {Component} from 'react';
import PropTypes from 'prop-types';

class Black extends Component {
    static propTypes = {
        bottomText: PropTypes.string,
        disabled: PropTypes.bool.isRequired,
        onClick: PropTypes.func,
    };

    static defaultProps = {
        disabled: false,
    };

    render() {
        const isDisabled = this.props.disabled;
        const canClick = !this.props.disabled && this.props.onClick;

        return (
            <div className={`card card__black ${isDisabled ? 'card__disabled' : null} ${canClick ? 'cursor-pointer' : null}`}
                 onClick={canClick ? this.props.onClick : null}>
                {this.props.children}
                {this.props.bottomText && <div className="card--bottom-text">{this.props.bottomText}</div>}
            </div>
        )
    }
}

export default Black;
