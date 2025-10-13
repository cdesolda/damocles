# DAMOCLES

## Table of Contents
1. [Description](#description)
2. [Installation](#installation)
    - [Installation with Docker](#installation-with-docker)
    - [Installation without Docker](#installation-without-docker)
3. [Login](#login)

## Description

The main purpose of the system is to support the design of education, training, and awareness interventions on phishing through the use of questionnaire campaigns and phishing email campaigns. It leverages Large Language Models (LLM) to generate phishing emails adapted to various human factors.

## Installation
First, clone the repository:
```bash
git clone https://github.com/cdesolda/DAMOCLES_PLATFORM
```

After the cloning, moving in the repository folder: 
```bash
cd DAMOCLES_PLATFORM
```

and copy the `.env.example` file rename it to `.env`:
```bash
cp .env.example .env
```

It is important to replace the example data indicated as `CHANGEME` in the `.env` file.
For local use and development, change the `APP_ENV` variable to `local`.

Now you can choose the type of installation, with or without Docker.

### Installation with Docker

Requirements:
- Docker

After cloning the repository and moving to the repository folder and copying the `.env.example` to the `.env` file and replacing the `CHANGEME` examples, run the following command to launch the containers using Docker Compose:

In the foreground:
```bash
docker compose up
```

or in the background:
```bash
docker compose up -d
```

The system is available on `localhost` or `http://127.0.0.1`.

### Installation without Docker

Requirements:
- Php
- Composer
- Node.js
- Mysql

Before we get started, it's important to install the dependencies. In the repository folder, run the following commands in a command line:

The backend dependencies:

```bash
composer install
```

The frontend dependencies with Node.js:

```bash
npm install
```
or
```bash
npm i
```

To migrate the database:

```bash
php artisan migrate
```

To seed the database:

```bash
php artisan db:seed
```

or use this command to migrate and seed:
```bash
php artisan migrate:refresh --seed
```

To generate a secure key in the .env file:
```bash
php artisan key:generate
```
To run the servers, use the following commands:

To start the backend:
```bash
php artisan serve
```

To start the frontend:
```bash
npm run dev
```

To monitor the jobs of the application, it is important to run the following command (from any command line) from the repository folder:
```bash
php artisan schedule:work
```

To monitor the SMTP server, it is important to run the following command (from any command line) from the repository folder:
```bash
php artisan queue:work
```

The system is available on `localhost:8000` or `http://127.0.0.1:8000`.

## Login
Access to the platform is possible through two available profiles and roles:
- "Admin" (email admin@damocles.com, password: secretAdmin)
- "Evaluator" (email evaluator@damocles.com, password: secretEvaluator)

While all new users who register will have the role of "User".
