import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from "react-redux";
import {join} from "../../dispatcher/game";
import Black from "../card/Black";

class JoinGame extends Component {
    static propTypes = {
        bottomText: PropTypes.string,
        onClick: PropTypes.func
    };

    render() {
        return (
            <Black onClick={() => this.props.join(this.props.gameId)} bottomText={'Click to join'} disabled={this.props.joining}>
                Join Game
            </Black>
        );
    }
}

export default connect(state => {
    return {
        gameId: state.game.id,
        joining: state.game.joining
    }
}, dispatch => {
    return {
        join: (game) => dispatch(join(game)),
    }
})(JoinGame);
