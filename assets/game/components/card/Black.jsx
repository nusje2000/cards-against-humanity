import React, {Component} from 'react';
import PropTypes from 'prop-types';

class Black extends Component {
    static propTypes = {
        text: PropTypes.string.isRequired,
        bottomText: PropTypes.string
    };

    render() {
        return (
            <div className={`card card__black`}>
                {this.props.text.replace('_', '__________')}

                {this.props.bottomText && <div className="card--bottom-text">
                    {this.props.bottomText}
                </div>}
            </div>
        )
    }
}

export default Black;
