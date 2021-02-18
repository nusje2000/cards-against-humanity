import React, {Component} from 'react';
import {connect} from 'react-redux';
import PropTypes from 'prop-types';
import White from './components/card/White';
import Black from "./components/card/Black";
import {load as loadGame} from "./dispatcher/game";
import {load as loadHand} from "./dispatcher/hand";

class App extends Component {
    static propTypes = {
        gameId: PropTypes.string.isRequired
    };

    componentDidMount() {
        this.props.load(this.props.gameId);
    }

    render() {
        return (
            <div>
                {this.props.hand && this.props.hand.map(card => <White key={card.id} text={card.contents}/>)}
                {this.props.currentRound && <Black text={this.props.currentRound.black_card.contents}/>}
            </div>
        )
    }
}

export default connect(state => {
    return {
        hand: state.hand.cards,
        players: state.game.players,
        currentRound: state.game.currentRound,
    }
}, dispatch => {
    return {
        load: id => {
            dispatch(loadGame(id));
            dispatch(loadHand(id));
        }
    }
})(App);
