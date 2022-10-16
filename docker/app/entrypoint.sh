#!/usr/bin/env bash

composer install -n

exec "$@"
