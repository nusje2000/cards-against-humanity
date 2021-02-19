import {Game} from "../action/game";

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
            })
        }).catch(error => {
            console.error(error);

            dispatcher({type: Game.error, error})
        });
    }
}

export function submit(game, card) {
    return (dispatcher) => {
        fetch(`/api/game/${game}/round/submit/${card}`).then(() => load(game)(dispatcher))
    }
}
