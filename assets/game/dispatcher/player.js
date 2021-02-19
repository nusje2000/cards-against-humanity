import {Player} from "../action/player";

export function load(id) {
    return (dispatcher, getState) => {
        const state = getState();
        if (state.players[id] && state.players[id].fetching) {
            return;
        }

        dispatcher({type: Player.fetching, id});

        fetch(`/api/player/${id}`).then((response) => {
            return response.json();
        }).then(player => {
            dispatcher({type: Player.fetched, id, username: player.username});
        }).catch(error => {
            dispatcher({type: Player.error, id, error});
        })
    }
}
