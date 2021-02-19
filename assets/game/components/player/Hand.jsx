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
            {this.props.hand.map(card => {
                return (
                    <White key={card.id} disabled={!this.props.hasActiveRound}
                           onClick={() => this.props.submit(this.props.gameId, card.id)}>
                        {card.contents}
                    </White>
                );
            })}
        </div>;
    }
}

export default connect(state => {
    return {
        fetching: state.hand.fetching,
        error: state.hand.error,
        hand: state.hand.cards ?? [],
        hasActiveRound: state.game.currentRound !== null
    }
}, dispatch => {
    return {
        load: id => dispatch(loadHand(id)),
        submit: (game, card) => dispatch(submit(game, card)),
    }
})(Hand);
