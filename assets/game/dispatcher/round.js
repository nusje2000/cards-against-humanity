import {load} from "./game";

export function start(game) {
    return (dispatcher) => {
        fetch(`/api/game/${game}/round/start`).then(() => load(game)(dispatcher))
    }
}

export function complete(game, player) {
    return (dispatcher) => {
        fetch(`/api/game/${game}/round/complete/${player}`).then(() => load(game)(dispatcher))
    }
}
