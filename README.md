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
composer require technoliga/plane-mcp
```

## Configuration

Create a `.env` file with your Plane credentials:

```env
PLANE_BASE_URL=https://your-plane-instance.com
PLANE_API_TOKEN=your_api_token_here
```

## Usage

This package is designed to be used as an MCP server with AI tools like Claude Code.

## Development

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your settings
4. Run tests with `./vendor/bin/phpunit`

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.