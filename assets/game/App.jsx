import React, {Component} from 'react';
import {connect} from 'react-redux';
import PropTypes from 'prop-types';
import White from './components/card/White';
import Black from "./components/card/Black";
import {load as loadGame} from "./dispatcher/game";
import StartRound from "./components/controls/StartRound";
import Hand from "./components/player/Hand";
import PlayerList from "./components/player/PlayerList";

class App extends Component {
    static propTypes = {
        gameId: PropTypes.string.isRequired
    };

    componentDidMount() {
        this.props.load(this.props.gameId);
    }

    render() {
        if (this.props.error) {
            return (<div>
                <White>There was a problem loading the game.</White>
                <White>{this.props.error}</White>
            </div>);
        }

        if (this.props.fetching) {
            return (<div>
                Loading the game...
            </div>);
        }

        return (
            <div className="flex w-full mb-5">
                <div className="w-3/4 mx-2">
                    <Hand gameId={this.props.gameId}/>
                </div>
                <div className="w-1/4 mx-2">
                    {this.props.currentRound !== null ? <Black>
                        {this.props.currentRound.black_card.contents.replace('_', '__________')}
                    </Black> : <StartRound/>}
                    <White>
                        <div className="block">
                            <div>Players:</div>
                            <PlayerList/>
                        </div>
                    </White>
                </div>
            </div>
        )
    }
}

export default connect(state => {
    return {
        fetching: state.game.fetching,
        error: state.game.error,
        players: state.game.players,
        currentRound: state.game.currentRound,
    }
}, dispatch => {
    return {
        load: id => dispatch(loadGame(id)),
    }
})(App);
