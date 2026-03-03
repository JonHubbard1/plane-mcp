# Plane MCP Server Extensions

This document outlines additional capabilities that can be added to the Plane MCP server.

## Current Extensions (Already Implemented)

### Project Management Methods
- `create_project` - Create a new project *(can be added)*
- `update_project` - Update project details *(can be added)*
- `delete_project` - Delete a project *(can be added)*

### Module Management Methods
- `create_module` - Create a new module
- `update_module` - Update module details *(can be added)*
- `delete_module` - Delete a module *(can be added)*
- `list_modules` - Already implemented

### Cycle Management Methods
- `create_cycle` - Create a new cycle/sprint
- `update_cycle` - Update cycle details *(can be added)*
- `delete_cycle` - Delete a cycle *(can be added)*
- `list_cycles` - Already implemented

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

## Example Implementation (create_page)

```php
// In PlaneClient.php
public function createPage(string $projectSlug, array $data): array
{
    try {
        $response = $this->httpClient->post("/api/v1/projects/{$projectSlug}/pages", [
            'json' => $data,
        ]);

        $page = json_decode($response->getBody(), true);
        return $page;
    } catch (RequestException $e) {
        throw new \Exception('Failed to create page: '.$e->getMessage());
    }
}

// In PlaneMcpServer.php
public function createPage(string $projectSlug, array $data): array
{
    try {
        $page = $this->client->createPage($projectSlug, $data);
        return [
            'success' => true,
            'page' => $page,
            'message' => 'Page created successfully',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}

// In McpHandler.php handleRequest method
case 'create_page':
    $projectSlug = $params['project_slug'] ?? null;
    $data = $params['data'] ?? [];

    if (!$projectSlug) {
        return ['success' => false, 'error' => 'Project slug is required'];
    }

    if (empty($data)) {
        return ['success' => false, 'error' => 'Page data is required'];
    }

    return $this->server->createPage($projectSlug, $data);
```

## Benefits for Claude Code Integration

With these extensions, Claude Code can:
- Automatically create modules for new feature areas
- Set up development cycles/sprints
- Create and maintain comprehensive documentation
- Manage project structure programmatically
- Generate reports on development progress
- Handle team assignments and permissions

This enables a fully automated project management workflow where Claude Code can not only track progress but actively manage and document the entire project structure in Plane.

## Current Status

✅ **Implemented**: Module creation, Cycle creation, Page management (create, update, get, list, delete)
🚧 **Planned**: Project management, Enhanced issue management, Team/user management, Reporting

The currently implemented features provide a solid foundation for Claude Code to populate pages with information and manage project documentation automatically.