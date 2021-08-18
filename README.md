# TIL

## TIL: Today I Learned

The following are the summary of the things that I learned in a daily basis whether by reading books, articles or by trying to implement state-of-the-art technologies. Note that what I have written are just concise explanations on various technologies that I am trying to learn. Also, some of them are mere summarized version of the official documentation since the documentation is better in explaining things than me :relaxed:. The things that I listed below are based on the learnings I had while doings tasks and/or knowledge acquired from implementing/coding whenever something catches my interest.

---

## Things I Learned

### Cheat Sheets

- [Git](cheat_sheets/git.md)
- [Linux Commands](cheat_sheets/linux.md)
- [cURL Commands](cheat_sheets/curl.md)

### Categories

- [Network Fundamentals](#network-fundamentals)
- [Docker](#docker)
- [Firebase](#firebase)
- [Laravel](#laravel)
- [Node.js](#nodejs)
- [HTML/CSS/JS](#htmlcssjs)
- [Github Workflow](#github-workflow)
- [Amazon Web Services (AWS)](#amazon-web-services-aws)
- [Google Cloud](#google-cloud)
- [Web HTTP](#web-http)

---

### Labels

[![label: summary][~summary]][summary]
[![label: read][~read]][read]
[![label: update][~update]][update]

```txt
- summary - I was able to learn but have not written summary yet
- read - Topics that I want to read in the future
- update - File is in need for an update
```

---

### Network Fundamentals

- [Internet and the OSI Model](network_fundamentals/internet.md)

### Docker

- [Dockerfile](docker/dockerfile-entries.md)
- [Networking in Compose](docker/networking-in-compose.md)
- [Dockerizing nodeJS application](docker/dockerizing-nodejs.md) [![label: summary][~summary]][summary]

### Firebase

- Android Client App
  - [Cloud Messaging using FirebaseMessaging API](firebase/android_client/messaging.md) [![label: update][~update]][update]

- Firebase Project
  - [Trigger a function on Cloud Storage changes](firebase/firebase_project/cloud_functions/cloud-storage-triggers.md) [![label: read][~read]][read] ![label: summary][~summary]

- Admin SDK for PHP
  - [Firebase For Laravel](firebase/laravel_server/firebase-for-laravel.md)
  - [Create a firebase user programmatically](firebase/laravel_server/create-user.md)
  - [Send a push notification to a specific device](firebase/laravel_server/cloud-messaging.md)
  - [Verify ID tokens using Authentication](firebase/laravel_server/authentication.md)

### Laravel

- [Create HTTP tests](laravel/http-test.md)
- [Handling Errors and Exceptions](laravel/error-handling.md)
- [Authenticate Users](laravel/authentication.md)
- [Authorizing Actions](laravel/authorization.md)
- [Inspect and filter HTTP requests using Middleware](laravel/middleware.md)
- [HTTP Responses](laravel/http-response.md)
- [HTTP Requests](laravel/http-request.md)
- [File Storage using Laravel's filesystem](laravel/file-storage.md)
- [Mocking Objects for Testing](laravel/mocking.md)
- [Add breadcrumbs using Tabuna/Breadcrumbs package](laravel/tabuna-breadcrumbs.md) ![label: summary][~summary]

### Node.js

- [Web application using Express framework](nodejs/express.md) [![label: read][~read]][read]
- [Network requests using Axios](nodejs/axios.md) [![label: read][~read]][read]
- [Use Embedded Javascript Templating (EJS) as Template Engine](node/ejs.md) [![label: read][~read]][read]

### HTML/CSS/JS

- [Build responsive sites with Bootstrapv4](html_css_js/bootstrap.md) ![label: summary][~summary] [![label: update][~update]][update]

### Github Workflow

- [About Github actions](github_workflow/github-actions.md) [![label: read][~read]][read]

### Amazon Web Services (AWS)

- [AWS Cloud Development Kit](aws/cdk.md) [![label: read][~read]][read]
- [Create a CI/CD pipeline for Amazon ECS with Github Actions](aws/ecs.md) [![label: read][~read]][read]
- [Serverless Architecture](aws/serverless.md) [![label: read][~read]][read]
- [Relational Database Service (RDS)](aws/rds.md) [![label: read][~read]][read]
- [Elastic Load Balancing (ELB)](aws/elb.md) [![label: read][~read]][read]

### Google Cloud

- [Speech-to-Text API](google_cloud/speech-to-text.md) [![label: read][~read]][read]
- [Cloud Storage](google_cloud/cloud-storage.md) [![label: read][~read]][read]

### Web HTTP

- [Cross-Origin Resource Sharing (CORS)](web_http/cors.md) [![label: read][~read]][read]

---

## Note

This repository is used for solely personal purposes. Everything written here are not used nor distributed for commercial purposes. Mostly written here are mere summaries of official documentations.

## References

- Laravel - <https://laravel.com/docs/8.x>
- Docker Reference - <https://docs.docker.com/reference/>
- Docker Product Manuals - <https://docs.docker.com/desktop/>
- Firebase Admin SDK for PHP - <https://firebase-php.readthedocs.io/en/5.x/>

## Supplementary

Personal Repositories:

- <https://github.com/chestercolita/punctuator_nodejs>
- <https://github.com/chestercolita/messaging-test-app>

Learning Links:

- <https://educative.io/signup?referralCode=chester-39ypR5yAKrr>

[~read]: https://img.shields.io/github/labels/chestercolita/til/read
[read]: https://github.com/chestercolita/til/labels/read
[~summary]: https://img.shields.io/github/labels/chestercolita/til/summary
[summary]: https://github.com/chestercolita/til/labels/summary
[~update]: https://img.shields.io/github/labels/chestercolita/til/update
[update]: https://github.com/chestercolita/til/labels/update
