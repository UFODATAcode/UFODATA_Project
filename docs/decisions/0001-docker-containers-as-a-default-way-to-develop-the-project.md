---
status: accepted
date: 2022-06-26
deciders: [@kryst3q](https://github.com/kryst3q)
---
# PHP applications can be hard to develop on different machines and Docker containers let simplify the whole process

## Context and Problem Statement

Developing php applications on different machines can be hard because of many elements, eg.:

- not compatible php versions installed
- lacking php modules
- local systems limitations

We can provide a list of steps that need to be done to set up the project but this can be hard because of a lot of different hardware and operating systems exists.
The list would be long, complicated and most probably incomplete.

## Decision Drivers

* the solution must be standard in the industry
* the solution must be well known among programmers
* the solution must be easy to use
* the solution must be fast
* the solution must works on most popular operating systems

## Considered Options

* [Docker](https://www.docker.com/)
* raw-php development

## Decision Outcome

Chosen option: "Docker", because
it is the only option that makes development fast and simple.

### Positive Consequences

* simple and fast project set up
* unified development environment among all project programmers

### Negative Consequences

* vendor locking
