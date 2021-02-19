import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from "react-redux";
import {load} from "../../dispatcher/player";

class Username extends Component {
    static propTypes = {
        playerId: PropTypes.string.isRequired
    };

    componentDidMount() {
        if (!this.props.players[this.props.playerId]) {
            this.props.load(this.props.playerId);
        }
    }

    render() {
        if (!this.props.players[this.props.playerId] || this.props.players[this.props.playerId].fetching) {
            return <span>Loading...</span>;
        }

        if (this.props.players[this.props.playerId].error !== null) {
            return (
                <span title={this.props.players[this.props.playerId].error}>
                    Unknown
                </span>
            )
        }

        return (
            <span>
                {this.props.players[this.props.playerId].username}
            </span>
        );
    }
}

export default connect(state => {
    return {
        players: state.players ?? {}
    }
}, dispatch => {
    return {
        load: (id) => dispatch(load(id)),
    }
})(Username);
