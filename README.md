# UFODATA

## What is the project about?

Main goal of the project is to provide a place for storing, processing and providing access to all UAP-related data obtained by automatic stations (like [UFODAP](https://ufodap.com/)).
The second purpose is to create a community of researchers and investigators around those data and analyses.
We believe that only hard data and it's scientific verification can get us closer of this fascinating and still not well known topic.
For now the data of various character (videos, audio, radio frequency spectra et al.) can be uploaded manually using page (work in progress) or API.
The API can be used in automatic stations software to upload their observations.

## How can I set up the project?

The preferred way of setting up the project is through Docker containers. 
To do that you need to have [Docker](https://www.docker.com/products/docker-desktop/) and [docker-compose](https://docs.docker.com/compose/install/) installed locally.
If you have do the following steps:

1. Clone the repository locally.

```shell
git clone https://github.com/UFODATAcode/UFODATA_Project
```

2. Go into the cloned repository directory.

```shell
cd UFODATA_Project
```

4. Create local copy of `docker-compose.override.yaml` file.

```shell
cp docker-compose.override.yaml.dist docker-compose.override.yaml
```

3. Start application containers in the background by executing the following command.

```shell
docker-compose up -d
```

After starting the containers need some time to execute all required set up actions (make migrations generate JWT keys and so on).
To check if the application is ready to handle connections you can look inside `app` logs.

```shell
docker-compose logs -f app
```

If you see something like below as the last line of the log the app should be ready.

> ufodata.app    | [Tue Aug 09 19:27:04.463097 2022] [core:notice] [pid 1] AH00094: Command line: 'apache2 -D FOREGROUND'

## How can I run tests?

First set up the project. After that you can run the tests using the following command.

```shell
docker-compose exec app-test vendor/bin/codecept run api
```

## Where can I find what was changed?

Check out our [changelog](/CHANGELOG.md).
