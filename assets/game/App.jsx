import React, {Component} from 'react';
import {connect} from 'react-redux';
import PropTypes from 'prop-types';
import White from './components/card/White';
import Black from "./components/card/Black";
import {load as loadGame, update as updateGame} from "./dispatcher/game";
import Hand from "./components/player/Hand";
import PlayerList from "./components/player/PlayerList";
import JoinGame from "./components/controls/JoinGame";
import StartRound from "./components/controls/StartRound";
import Submissions from "./components/controls/Submissions";

class App extends Component {
    static propTypes = {
        gameId: PropTypes.string.isRequired
    };

    componentDidMount() {
        this.props.load(this.props.gameId);

        setInterval(() => {
            this.props.update(this.props.gameId);
        }, 2000)
    }

    render() {
        if (this.props.error) {
            return (<div>
                <White>There was a problem loading the game.</White>
                <White>{this.props.error}</White>
            </div>);
        }

        return (
            <div>
                <div className="w-full m-5">
                    {this.controls()}
                </div>
                <div className="w-full m-5">
                    <Submissions/>
                </div>
                <div className="w-full m-5">
                    <Hand gameId={this.props.gameId}/>
                </div>
                {this.props.fetching && <div className='fixed text-white bg-blue-900 bottom-5 left-5 p-3 rounded font-bold'>Updating...</div>}
            </div>
        )
    }

    controls() {
        const hasActiveRound = this.props.currentRound !== null;
        const playerIsJoined = this.props.players.includes(this.props.currentPlayer);

        const controls = [];

        if (hasActiveRound) {
            controls.push(<Black key='current-card'>{this.props.currentRound.black_card.contents.replace('_', '__________')}</Black>);
        }

        if (!hasActiveRound && playerIsJoined) {
            controls.push(<StartRound key='start-round'/>);
        }

        if (!this.props.fetching && !playerIsJoined) {
            controls.push(<JoinGame key='join-game'/>);
        }

        controls.push(<White key='player-list'>
            <div className="block">
                Players:
                <PlayerList/>
            </div>
        </White>);

        return controls;
    }
}

export default connect(state => {
    return {
        fetching: state.game.fetching,
        error: state.game.error,
        players: state.game.players ?? [],
        submissions: state.game.currentRound ? state.game.currentRound.submissions : null,
        currentRound: state.game.currentRound,
        currentPlayer: state.game.currentPlayer
    }
}, dispatch => {
    return {
        load: id => dispatch(loadGame(id)),
        update: id => dispatch(updateGame(id)),
    }
})(App);
