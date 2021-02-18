import React from 'react';
import ReactDOM from 'react-dom';

import {Provider} from 'react-redux';
import {applyMiddleware, combineReducers, compose, createStore} from 'redux';
import ReduxThunk from 'redux-thunk';
import game from './reducers/game';
import hand from './reducers/hand';
import App from './App';


for (const element of document.getElementsByClassName('game-container')) {
    const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;

    const store = createStore(combineReducers({game, hand}), composeEnhancers(applyMiddleware(ReduxThunk)));
    const gameId = element.getAttribute('data-id');

    ReactDOM.render(<Provider store={store}><App gameId={gameId}/></Provider>, element);
}
