# Plane MCP Server Extensions

This document outlines additional capabilities that can be added to the Plane MCP server.

## Current Extensions (Already Implemented)

### Project Management Methods
- `create_project` - Create a new project
- `update_project` - Update project details
- `delete_project` - Delete a project
- `get_project` - Get specific project details (already implemented)
- `list_projects` - List all projects (already implemented)

### Module Management Methods
- `create_module` - Create a new module
- `update_module` - Update module details *(can be added)*
- `delete_module` - Delete a module *(can be added)*
- `list_modules` - List modules for a project (already implemented)

### Cycle Management Methods
- `create_cycle` - Create a new cycle/sprint
- `update_cycle` - Update cycle details *(can be added)*
- `delete_cycle` - Delete a cycle *(can be added)*
- `list_cycles` - List cycles for a project (already implemented)

### Page/Wiki Management Methods
- `create_page` - Create documentation pages
- `update_page` - Update page content
- `delete_page` - Delete a page
- `list_pages` - List wiki pages
- `get_page` - Get specific page details

### Enhanced Issue Management
- `bulk_create_issues` - Create multiple issues at once *(can be added)*
- `bulk_update_issues` - Update multiple issues *(can be added)*
- `move_issue` - Move issue between projects *(can be added)*
- `link_issues` - Link related issues *(can be added)*

### Team/User Management
- `list_team_members` - List project team members *(can be added)*
- `assign_issue` - Assign issues to team members *(can be added)*
- `update_user_permissions` - Manage user permissions *(can be added)*

### Reporting Methods
- `get_project_metrics` - Get project statistics *(can be added)*
- `get_velocity_report` - Get team velocity metrics *(can be added)*
- `get_burndown_chart` - Get sprint burndown data *(can be added)*
- `export_issues` - Export issues in various formats *(can be added)*

## Implementation Approach

Each method follows the same pattern:
1. Add method to PlaneClient for API interaction
2. Add corresponding method to PlaneMcpServer
3. Register method in McpHandler
4. Update documentation

## Example Implementation (create_project)

```php
// In PlaneClient.php
public function createProject(array $data): array
{
    try {
        $response = $this->httpClient->post("/api/v1/projects/", [
            'json' => $data,
        ]);

        $project = json_decode($response->getBody(), true);
        return $project;
    } catch (RequestException $e) {
        throw new \Exception('Failed to create project: '.$e->getMessage());
    }
}

// In PlaneMcpServer.php
public function createProject(array $data): array
{
    try {
        $project = $this->client->createProject($data);
        return [
            'success' => true,
            'project' => $project,
            'message' => 'Project created successfully',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}

// In McpHandler.php handleRequest method
case 'create_project':
    $data = $params['data'] ?? [];

    if (empty($data)) {
        return ['success' => false, 'error' => 'Project data is required'];
    }

    return $this->server->createProject($data);
```

## Benefits for Claude Code Integration

With these extensions, Claude Code can:
- Automatically create new projects for client work
- Bootstrap projects with standard structures and documentation
- Manage project lifecycle from inception to completion
- Create and maintain comprehensive documentation
- Set up development workflows with modules and cycles
- Generate reports on development progress

This enables a fully automated project management workflow where Claude Code can not only track progress but actively create, manage, and document entire projects in Plane.

## Current Status

✅ **Implemented**: Project management (create, update, delete), Module creation, Cycle creation, Page management (create, update, get, list, delete)
🚧 **Planned**: Enhanced issue management, Team/user management, Reporting

The currently implemented features provide a complete foundation for Claude Code to create and manage projects, populate them with information, and maintain comprehensive documentation automatically.