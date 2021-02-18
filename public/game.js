$('.start-game').on('click', (event) => {
    const href = $(event.target).data('api');

    fetch(href).then((response) => {
        return response.json();
    }).then((json) => {
        window.location.href = `/game/${json.id}`;
    }).catch(error => alert(error));
});

$('[data-api-trigger]').on('click', (event) => {
    const href = $(event.target).data('api-trigger');

    fetch(href).then((response) => {
        if (response.status !== 200) {
            throw `Unexpected statuscode ${response.status}`;
        }

        window.location.reload();
    }).catch(error => alert(error));
});

$('[data-api-fetch]').each((index, element) => {
    const $element = $(element);
    const path = $element.data('api-fetch');

    $element.text('Loading...');

    const cache_key = `cache_${btoa(path)}`;

    const cached = sessionStorage.getItem(cache_key);
    if (!$element.data('disable-cache') && cached) {
        $element.text(cached);

        return;
    }

    fetch(path).then(response => {
        return response.json()
    }).then(decoded => {
        const property = decoded[$element.data('api-property')];
        sessionStorage.setItem(cache_key, property);
        return $element.text(property)
    }).catch(e => $element.text(e));
});

$('[data-api-playerlist]').each((index, element) => {
    const $element = $(element);
    const path = $element.data('api-playerlist');
    const container = $element.find('template').html();

    $element.text('Loading...');

    fetch(path).then(response => {
        return response.json()
    }).then(players => {
        $element.empty();

        for (const player of players) {
            const playerContainer = container;
            playerContainer.replace('__username__', player.username);
            $element.append(playerContainer.replace('__username__', player.username));
        }
    }).catch(e => $element.text(e));
});
