#!/bin/bash
set -e

cd "$(dirname "$0")/.."

docker compose -f docker-init/docker-compose.yml down || true
docker compose -f docker-init/docker-compose.yml up --build -d