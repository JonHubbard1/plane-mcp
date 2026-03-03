# Plane MCP Server

A Model Context Protocol (MCP) server for integrating with [Plane](https://plane.so), a modern project management tool.

## Overview

This MCP server allows AI assistants like Claude Code to interact directly with Plane instances, enabling:
- Reading project status and progress
- Creating and updating issues
- Managing sprints and milestones
- Tracking work across multiple projects

## Features

- **Authentication**: Secure connection to Plane instances
- **Project Management**: List projects, modules, and cycles
- **Issue Operations**: Create, read, update, and delete issues
- **Progress Tracking**: Update statuses, assignments, and progress
- **Search**: Find issues, projects, and team members

## Requirements

- PHP 8.1+
- Composer
- Access to a Plane instance (self-hosted or cloud)

## Installation

```bash
composer install
```

## Configuration

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` with your Plane instance details:
   ```env
   PLANE_BASE_URL=https://your-plane-instance.com
   PLANE_API_TOKEN=your_api_token_here
   PLANE_DEFAULT_PROJECT=your-default-project-slug
   ```

3. Obtain your API token from Plane:
   - Go to your Plane instance
   - Navigate to Profile Settings > API Tokens
   - Create a new token with appropriate permissions

## Usage

### Starting the Server

```bash
php -S localhost:8080 server.php
```

The server will be available at `http://localhost:8080`.

### Testing the Server

```bash
# Test server info
curl http://localhost:8080

# Test listing projects (requires valid credentials)
curl -X POST http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"method": "list_projects", "params": {}}'
```

### Using with Claude Code

Once the server is running, you can connect Claude Code to it by configuring an MCP client to point to `http://localhost:8080`.

## Available Methods

The server exposes the following MCP methods:

- `list_projects` - Get all projects
- `get_project` - Get details of a specific project
- `list_issues` - List issues for a project
- `get_issue` - Get details of a specific issue
- `create_issue` - Create a new issue
- `update_issue` - Update an existing issue
- `list_modules` - Get modules for a project
- `list_cycles` - Get cycles for a project
- `search_issues` - Search issues across projects

## Development

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your settings
4. Run tests with `./vendor/bin/phpunit`

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.