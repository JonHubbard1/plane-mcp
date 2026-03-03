# Usage Guide

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/technoliga/plane-mcp.git
   cd plane-mcp
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy and configure the environment file:
   ```bash
   cp .env.example .env
   ```
   Then edit `.env` with your Plane instance details.

## Basic Usage

### Initialize the Client

```php
use Technoliga\PlaneMcp\PlaneClient;
use Technoliga\PlaneMcp\PlaneMcpServer;
use Technoliga\PlaneMcp\McpHandler;

// Initialize with configuration
$config = [
    'base_url' => $_ENV['PLANE_BASE_URL'],
    'api_token' => $_ENV['PLANE_API_TOKEN'],
];

$client = new PlaneClient($config);
$server = new PlaneMcpServer($client);
$handler = new McpHandler($server);
```

### Using the MCP Handler

```php
// List all projects
$result = $handler->handleRequest('list_projects');
if ($result['success']) {
    foreach ($result['projects'] as $project) {
        echo $project['name'] . "\n";
    }
}

// Get a specific project
$result = $handler->handleRequest('get_project', [
    'project_slug' => 'supporthub'
]);

// List issues for a project
$result = $handler->handleRequest('list_issues', [
    'project_slug' => 'supporthub',
    'filters' => ['status' => 'open']
]);

// Create a new issue
$result = $handler->handleRequest('create_issue', [
    'project_slug' => 'supporthub',
    'data' => [
        'name' => 'Implement user authentication',
        'description' => 'Create login and registration functionality',
        'priority' => 'high'
    ]
]);
```

## MCP Tools Available

The Plane MCP server exposes the following tools:

1. **list_projects** - Get all projects
2. **get_project** - Get details of a specific project
3. **list_issues** - List issues with optional filters
4. **get_issue** - Get details of a specific issue
5. **create_issue** - Create a new issue
6. **update_issue** - Update an existing issue
7. **list_modules** - Get modules for a project
8. **list_cycles** - Get cycles for a project
9. **search_issues** - Search issues across projects

## Error Handling

All methods return a consistent response format:

```php
[
    'success' => boolean,
    'error' => string, // Only present if success is false
    // ... other data
]
```

Always check the `success` field before processing the response data.