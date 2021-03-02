import {Hand} from "../action/hand";

export function load(id) {
    return (dispatcher) => {
        dispatcher({type: Hand.fetching});

        fetch(`/api/game/${id}/hand`).then(response => {
            if (response.status === 404) {
                return null;
            }

            return response.json();
        }).then(hand => {
            if (hand === null) {
                dispatcher({type: Hand.clear});

                return;
            }

            dispatcher({type: Hand.fetched, cards: Object.values(hand.contents)})
        }).catch(error => {
            console.warn(error);

            dispatcher({type: Hand.error, error})
        });
    }
}
