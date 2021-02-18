# Nusje2000/CAH

An online version of the party game Cards Against Humanity.

## Installation

```shell
npm install # install dependencies
npm run build # build frontend assets

composer install # install dependencies
bin/console doctrine:database:create # create database
bin/console doctrine:schema:update -f # prepare database schema
bin/console doctrine:fixtures:load # load test fixtures (optional)
symfony serve # run webserver
```

## Motivation

I love the original card game but due to COVID-19, it's not possible to gather some friends and play this game. I'm always looking for new things to learn in my
development career and this project is basically some form of sandbox for me, but with the goal to become an actual playable game.

### New techniques

This project is my way of trying out new techniques. The main focus of this project was to expiriment with DDD, CQRS and Event Sourcing. This list will probably
grow larger over times when new techniques join the party

### Project structure

For me, this project uses a unique file structure where mutliple layers of logic are seperated from eachother. The current project consists of three types of
layers:

1. Domain, containing all the domain logic of the application
2. Infrastructure, containing implementation logic for things like frameworks, database systems, etc. (mostly integrating third party packages)
3. Application, containing the actual application (controllers, commands, etc.)

### Replacement of the original?

The target of this project is NOT to replace the original "cardboard" version. I do not encourage people to use this version instead of buying the actual game
itself. If this reaches a playable state, I will invest time in making sure I'm not replacing the original game. I don't have a solution for this problem YET.

## Contributing

### Code contibution

Currently, contributing to this package might be difficult because there are no clear issues yet. I'm still exploring new techniques and don't really have a
clear endgoal yet.

### Code review

Although you might not be able to contribute by writing code yourself, feel free to review the code that is already there. I'm always looking for things to
improve. Don't feel discouraged to review my code if you are a beginner, I'm open for helping others improve as well, so if you have a question, just ask it.

Questions can be asked on PR's or by creating a question as issue. Please use the `[QUESTION]` prefix in your issue title.
