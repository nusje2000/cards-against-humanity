import React, {Component} from 'react';
import {connect} from "react-redux";
import Username from "./Username";

class PlayerList extends Component {
    render() {
        if (!this.props.players) {
            return (
                <div>Such empty 0_0</div>
            )
        }

        return this.props.players.map((playerId) => {
            return (
                <div key={playerId} className='w-full'>
                    <Username playerId={playerId}/>
                </div>
            )
        });
    }
}

export default connect(state => {
    return {
        players: state.game.players ?? []
    }
})(PlayerList);
