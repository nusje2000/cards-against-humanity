import {Game} from "../action/game";
import {load as loadHand} from "./hand";

export function update(id) {
    return (dispatcher, getState) => {
        fetch(`/api/game/${id}/version`).then(response => {
            return response.json();
        }).then(response => {
            if (getState().game.version !== response.version) {
                dispatcher(load(id))
                dispatcher(loadHand(id))
            }
        });
    }
}

export function load(id) {
    return (dispatcher) => {
        dispatcher({type: Game.fetching, id});

        fetch(`/api/game/${id}`).then(response => {
            return response.json();
        }).then(game => {
            dispatcher({
                type: Game.fetched,
                players: Object.values(game.players.joined),
                currentRound: game.rounds.current,
                previousRound: game.rounds.previous,
                completedRounds: game.rounds.completed,
                version: game.version
            })
        }).catch(error => {
            console.error(error);

            dispatcher({type: Game.error, error})
        });
    }
}

export function join(id) {
    return (dispatcher) => {
        dispatcher({type: Game.joining, game: id});

        fetch(`/api/game/${id}/join`).then(response => {
            return response.json();
        }).then(player => {
            dispatcher({type: Game.joined, game: id, player: player.id});
        }).catch(error => {
            alert(`Could not join the game due to: ${error}`);

            dispatcher({type: Game.error, error})
        });
    };
}

export function submit(game, card) {
    return (dispatcher) => {
        fetch(`/api/game/${game}/round/submit/${card}`).then(() => load(game)(dispatcher))
    }
}
