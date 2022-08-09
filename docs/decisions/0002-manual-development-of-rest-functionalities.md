---
status: accepted
date: 2022-06-26
deciders: [@kryst3q](https://github.com/kryst3q)
---
# Manual development of REST functionalities

## Context and Problem Statement

There are some very good, verified libraries that lets build a REST application fast and easy with minimal code creation.
There are few problems with this approach:

* problems with limitations that appears when a project's business logic grows and starts beeing more complex,
* vendor locking,
* such solutions not always fit to clean architecture rules,
* such solutions provides excessive amount of unneded functionalities,
* lack of specialist at the market - especially from the given, chosen technology,
* problems during code refactoring to apply Domain Driven Design.

## Decision Drivers

* the project must be as much vendor-lock free as it is possible,
* current project structure can be easily moved into DDD one,
* the project must apply clean architecture rules as much as possible.

## Considered Options

1. Manual development of the REST functionalities.
2. [API Platform](https://api-platform.com/).

## Decision Outcome

Chosen option: "1", because
It's the only option, which meets decision drivers criteria.

### Positive Consequences

* no vendor-lock,
* easy move to DDD architecture,
* pure php code that should be understandable for most php developers.

### Negative Consequences

* bigger amount of code,
* bigger amount of abstractions.
