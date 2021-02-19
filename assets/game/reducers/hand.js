import {Hand} from "../action/hand";

const defaultState = {
    cards: null,
    fetching: false,
    error: null
};

function hand(state = defaultState, action) {
    switch (action.type) {
        case Hand.fetching:
            return {
                ...state,
                fetching: true,
                error: null,
            };
        case Hand.fetched:
            return {
                ...state,
                cards: action.cards,
                fetching: false,
                error: null,
            };
        case Hand.error:
            return {
                ...state,
                cards: null,
                fetching: true,
                error: action.error,
            };
        default:
            return state;
    }
}

export default hand;
