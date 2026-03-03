# Plane MCP Server

A Model Context Protocol (MCP) server for integrating with [Plane](https://plane.so), a modern project management tool.

## Overview

This MCP server allows AI assistants like Claude Code to interact directly with Plane instances, enabling:
- Reading project status and progress
- Creating and updating projects
- Managing modules and development cycles
- Creating and maintaining documentation pages
- Tracking work across multiple projects

## Features

- **Project Management**: Create, read, update, and delete projects
- **Module Management**: Create and manage feature modules
- **Cycle Management**: Create and manage development cycles/sprints
- **Page Management**: Create, update, and maintain documentation
- **Issue Management**: Create and update issues
- **Authentication**: Secure connection to Plane instances
- **Caching**: Built-in caching for improved performance

## Requirements

- PHP 8.1+
- Composer
- Access to a Plane instance (self-hosted or cloud)

## Installation

```bash
# Clone the repository
git clone https://github.com/your-username/plane-mcp.git
cd plane-mcp

# Install dependencies
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

### Project Management
- `create_project` - Create a new project
- `update_project` - Update an existing project
- `delete_project` - Delete a project
- `get_project` - Get details of a specific project
- `list_projects` - List all projects

### Module Management
- `create_module` - Create a new module
- `update_module` - Update an existing module
- `delete_module` - Delete a module
- `list_modules` - List modules for a project

### Cycle Management
- `create_cycle` - Create a new cycle/sprint
- `update_cycle` - Update an existing cycle
- `delete_cycle` - Delete a cycle
- `list_cycles` - List cycles for a project

### Page Management
- `create_page` - Create a new documentation page
- `update_page` - Update an existing page
- `delete_page` - Delete a page
- `list_pages` - List pages for a project
- `get_page` - Get details of a specific page

### Issue Management
- `create_issue` - Create a new issue
- `update_issue` - Update an existing issue
- `get_issue` - Get details of a specific issue
- `list_issues` - List issues for a project
- `search_issues` - Search issues across projects

## Integration Examples

### Creating a New Project
```javascript
const result = await mcp.call('create_project', {
  data: {
    name: 'Client Portal System',
    identifier: 'client-portal',
    description: 'Portal system for client self-service and support ticket management'
  }
});
```

### Creating Documentation
```javascript
const result = await mcp.call('create_page', {
  project_slug: 'client-portal',
  data: {
    name: 'Project Overview',
    content: '# Client Portal System\n\n## Overview\n...',
    description: 'Project overview and technical details'
  }
});
```

### Bootstrapping a Project
```javascript
// Create project
const project = await mcp.call('create_project', {
  data: {
    name: 'New Project',
    identifier: 'new-project',
    description: 'Description of the new project'
  }
});

// Create standard modules
await mcp.call('create_module', {
  project_slug: 'new-project',
  data: {
    name: 'Authentication',
    description: 'User authentication and authorization'
  }
});

// Create documentation
await mcp.call('create_page', {
  project_slug: 'new-project',
  data: {
    name: 'Requirements',
    content: '# Requirements\n\n## Functional Requirements\n...',
    description: 'Project requirements document'
  }
});
```

## Development

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your settings
4. Run tests with `./vendor/bin/phpunit`

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.