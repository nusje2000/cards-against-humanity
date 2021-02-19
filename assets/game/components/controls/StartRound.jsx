import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from "react-redux";
import {start} from "../../dispatcher/round";
import Black from "../card/Black";

class StartRound extends Component {
    static propTypes = {
        bottomText: PropTypes.string,
        onClick: PropTypes.func
    };

    render() {
        if (!this.props.hasEnoughPlayers) {
            return (
                <Black>
                    Waiting for other players
                </Black>
            );
        }

        return (
            <Black onClick={() => start(this.props.gameId)} bottomText={'Click to start'}>
                Start round
            </Black>
        );
    }
}

export default connect(state => {
    return {
        gameId: state.game.id,
        hasEnoughPlayers: state.game.players && state.game.players.length > 1
    }
}, dispatch => {
    return {
        startRound: (game) => dispatch(start(game)),
    }
})(StartRound);
