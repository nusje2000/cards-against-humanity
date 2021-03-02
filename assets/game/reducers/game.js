import {Game} from "../action/game";

const defaultState = {
    players: null,
    currentRound: null,
    previousRound: null,
    completedRounds: null,
    gameFetchError: null,
    joining: false,
    currentPlayer: null,
};

function game(state = defaultState, action) {
    switch (action.type) {
        case Game.fetching:
            return {
                ...state,
                id: action.id,
                fetching: true,
                gameFetchError: null,
            };
        case Game.fetched:
            return {
                ...state,
                players: action.players,
                currentRound: action.currentRound,
                previousRound: action.currentRound,
                completedRounds: action.completedRounds,
                version: action.version,
                fetching: false,
                gameFetchError: null,
            };
        case Game.error:
            return {
                ...state,
                players: null,
                currentRound: null,
                previousRound: null,
                completedRounds: null,
                fetching: false,
                gameFetchError: action.error,
            };
        case Game.joining:
            return {
                ...state,
                joining: true,
                currentPlayer: null
            };
        case Game.joined:
            return {
                ...state,
                joining: false,
                currentPlayer: action.player
            };
        case Game.join_failed:
            return {
                ...state,
                joining: false,
                currentPlayer: null
            };
        default:
            return state;
    }
}

export default game;
