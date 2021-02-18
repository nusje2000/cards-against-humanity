import {Hand} from "../action/hand";

export function load(id) {
    return (dispatcher) => {
        dispatcher({type: Hand.fetching});

        fetch(`/api/game/${id}/hand`).then(response => {
            return response.json();
        }).then(hand => {
            dispatcher({
                type: Hand.fetched,
                cards: Object.values(hand.contents),
            })
        }).catch(error => {
            console.error(error);

            dispatcher({type: Hand.error, error})
        });
    }
}
