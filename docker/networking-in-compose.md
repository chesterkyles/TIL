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
