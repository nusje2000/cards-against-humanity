import {load} from "./game";

export function start(game) {
    return (dispatcher) => {
        fetch(`/api/game/${game}/round/start`).then(() => load(game)(dispatcher))
    }
}
