# Demo app

## Setup with Docker Compose and run

1. Copy 3 environment files that used for project setup:
```bash
# Docker Compose
cp .env.example .env
# Frontend
cp src/frontend/.env.example src/frontend/.env
# Backend
cp  src/backend/.env  src/backend/.env.local
```

2. Change some values if required
    - Docker Compose .env - can be redefined host port mapping and UID/GID that will be used inside docker
    - Frontend .env - must be changed host http port
    - Backend .env.local - can be changed app mode, but it will work without any changed anyway

3. Run `docker-compose up`. All other setups (dependencies installation, migrations, builds etc.) will be finished inside containers

4. Wait a bit when finish containers built and it inner setups

### Setup without docker

This project has been built with a focus on containerization so has no standard way to run directly on the host or something other.
In theory, it can be run as-is, if do all setups that happens in `docker/*` folders and change backend-frontend env

## Usage

With default configurations when all prepared finished project will be available by url [http://localhost:8096/](http://localhost:8096/)

By default, will be created 2 users with next passwords:
- `user` - `userpass` - Regular user
- `admin` - `adminpass` - Admin user

For regular user available only fetching wallet information (balance, refunded value for next 7 days and wallet history list);
Admin user - can add new wallet transactions to history also  
