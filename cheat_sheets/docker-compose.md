# Docker-Compose Commands

## docker-compose build

This command builds images of the mentioned services in the `docker-compose.yml` file for which a `Dockerfile` is provided.

```sh
$ docker-compose build

database uses an image, skipping
Building web
Step 1/11 : FROM python:3.9-rc-buster
 ---> 2e0edf7d3a8a
Step 2/11 : RUN apt-get update && apt-get install -y docker.io
```

## docker-compose images

This command lists images built using the current docker-compose file.

```sh
```