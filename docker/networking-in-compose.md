# Networking in Compose

## Note

Note that this feature is not supported for Compose file version 1 which is already deprecated.

By default, Compose sets up a single network for your app. Each container for a service joins the default network and is both _reachable_ by other containers on that network, and _discoverable_ by them at a hostname to the constainer name. Read more about network (Docker engine) here: <https://docs.docker.com/engine/reference/commandline/network_create/>

## Example

For example, directory name of app or project is called `testapp`. The `docker-compose.yml` looks like this:

```yml
version: "3.9"
services:
  web:
    build: .
    ports:
      - "8000:8000"
  db:
    image: postgres
    ports:
      - "8001:5432"
```

When running `docker-compose up`, the following happens:

1. A network called `testapp_default` is created.
2. A container is created using `web`'s configuration. It joins the network `testapp_default` under the name `web`.
3. A container is created using `db`'s configuration. It joins the network `testapp_default` under the name `db`.

### Important Notes

- Each container can now look up the hostname `web` or `db`. For example, `web`'s application code could connect to the URL `postgres://db:5432` and start using the Postgres database.

- The distinction between `HOST_PORT` and `CONTAINER_POST` should be noted. In the example, the `HOST_PORT` for `db` is `8001` and the `CONTAINER_POST` is `5432`. Networked service-to-service communication uses the `CONTAINER_PORT`. When the `HOST_PORT` is defined, the service is accssible outside the swarm as well.

- Within the `web` container, the connection string to `db` would look like `postgres://db:5432`, and from the host machine, the connection string would look like `postgres://{DOCKER_IP}:8001`.

## When updating containers

If you make a configuration change to a service and run `docker-compose up` to update it, the **old container is removed** and the new one joins the network under a different IP address but the same name. Running containers can look up that name and connect to the new address, but the old address stops working.
