# Inventory Management System

A self-hosted inventory management application for tracking physical items across locations. Built with Symfony 7 and an EasyAdmin UI.

## Features

Organise your inventory using a three-level hierarchy:

- **Zones** — top-level physical locations (rooms, areas, storage units)
- **Containers** — storage containers within a zone, identified by a short code (boxes, bins, shelves)
- **Items** — individual inventory items, assigned to a category and optionally a container or zone, with description, code, quantity, price, and an image

Items can be browsed and managed via the admin dashboard, with support for image uploads to S3-compatible storage.

## Tech Stack

- PHP 8.2+ / Symfony 7
- EasyAdmin 4
- MySQL
- Nginx
- Docker / Docker Compose

## Getting Started

### Prerequisites

- Docker and Docker Compose

### 1. Configure environment

Copy the example env file and set the port the application will be served on:

```bash
cp .env.example .env
```

Edit `.env` and set `APP_PORT` to your preferred port (default: `10000`):

```
APP_PORT=10000
```

### 2. Start the application

```bash
docker compose up -d
```

This will:
- Build and start the PHP application container
- Start Nginx on the configured port
- Start a MySQL database
- Automatically run `composer install` and database migrations on first boot

### 3. Access the app

Open your browser at:

```
http://localhost:10000
```

The admin dashboard is served at the root path.

## Configuration

| Variable   | Description                        | Default |
|------------|------------------------------------|---------|
| `APP_PORT` | Host port to expose the application | `10000` |

For S3 image uploads, configure the relevant Flysystem/AWS credentials in `app/.env`.

## Development

The dev Docker image mounts `./app` into the container, so changes to source files are reflected immediately without rebuilding.

To run the test suite inside the app container:

```bash
docker compose exec app composer run-tests
```
