import React, {Component} from 'react';
import {connect} from "react-redux";
import {complete} from "../../dispatcher/round";
import White from "../card/White";

class Submissions extends Component {
    render() {
        if (this.props.currentRound === null) {
            return [];
        }

        const revealCards = this.props.playerCount - 1 === this.props.submissionCount;

        return (
            <div>
                <h3 className='text-4xl mb-2 text-white'>Submissions:</h3>
                {this.props.submissions.map(submission => {
                    return <White key={submission.player} revealed={revealCards}
                                  onClick={this.canCompleteRound() ? () => this.complete(submission.player) : null}
                                  bottomText={this.canCompleteRound() ? 'Click to complete' : null}>
                        {submission.card.contents}
                    </White>
                })}
            </div>
        );
    }

    complete(player) {
        this.props.complete(this.props.gameId, player)
    }

    canCompleteRound() {
        const currentRound = this.props.currentRound;

        return currentRound !== null && currentRound.card_czar === this.props.currentPlayer;
    }
}

export default connect(state => {
    return {
        gameId: state.game.id,
        currentRound: state.game.currentRound,
        submissions: state.game.currentRound ? state.game.currentRound.submissions : null,
        currentPlayer: state.game.currentPlayer,
        playerCount: state.game.players ? state.game.players.length : 0,
        submissionCount: state.game.currentRound ? state.game.currentRound.submissions.length : 0
    }
}, dispatch => {
    return {
        complete: (game, player) => dispatch(complete(game, player)),
    }
})(Submissions);
