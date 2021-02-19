import {Player} from "../action/player";

const defaultState = {};

function game(state = defaultState, action) {
    switch (action.type) {
        case Player.fetching:
            return {
                ...state,
                [action.id]: {
                    id: action.id,
                    username: null,
                    fetching: true,
                    error: null
                }
            };
        case Player.fetched:
            return {
                ...state,
                [action.id]: {
                    username: action.username,
                    fetching: false,
                    error: null
                }
            };
        case Player.error:
            return {
                ...state,
                [action.id]: {
                    username: null,
                    fetching: false,
                    error: action.error
                }
            };
        default:
            return state;
    }
}

export default game;
