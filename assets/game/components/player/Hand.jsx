import React, {Component} from 'react';
import PropTypes from "prop-types";
import White from "../card/White";
import {connect} from "react-redux";
import {submit} from "../../dispatcher/game";
import {load as loadHand} from "../../dispatcher/hand";

class Hand extends Component {
    static propTypes = {
        gameId: PropTypes.string.isRequired
    };

    componentDidMount() {
        this.props.load(this.props.gameId);
    }

    render() {
        if (this.props.error) {
            return (<div>
                <White>There was a problem loading your hand.</White>
                <White>{this.props.error}</White>
            </div>);
        }

        if (this.props.fetching) {
            return (<div>
                Loading your hand...
            </div>);
        }

        return <div>
            <h3 className='text-4xl mb-2 text-white'>Your hand:</h3>
            {this.props.hand.map(card => {
                return (
                    <White key={card.id} disabled={!this.canSubmit()}
                           onClick={() => this.props.submit(this.props.gameId, card.id)}
                           bottomText={this.canSubmit() ? 'Click to submit' : null}>
                        {card.contents}
                    </White>
                );
            })}
        </div>;
    }

    canSubmit() {
        if (!this.props.hasActiveRound) {
            return false;
        }

        if (this.props.currentRound.card_czar === this.props.currentPlayer) {
            return false;
        }

        for (const submission of this.props.currentRound.submissions) {
            if (submission.player === this.props.currentPlayer) {
                return false;
            }
        }

        return true;
    }
}

export default connect(state => {
    return {
        fetching: state.hand.fetching,
        error: state.hand.error,
        hand: state.hand.cards ?? [],
        currentRound: state.game.currentRound,
        currentPlayer: state.game.currentPlayer,
        hasActiveRound: state.game.currentRound !== null
    }
}, dispatch => {
    return {
        load: id => dispatch(loadHand(id)),
        update: id => dispatch(updateHand(id)),
        submit: (game, card) => dispatch(submit(game, card)),
    }
})(Hand);
