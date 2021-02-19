import {Game} from "../action/game";

const defaultState = {
    players: null,
    currentRound: null,
    previousRound: null,
    completedRounds: null,
    gameFetchError: null,
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
        default:
            return state;
    }
}

export default game;
