# Plane MCP Server Extensions

This document outlines additional capabilities that can be added to the Plane MCP server.

## Planned Extensions

### Project Management Methods
- create_project(project_data)
- update_project(project_slug, update_data)
- delete_project(project_slug)

### Module Management Methods
- create_module(project_slug, module_data)
- update_module(project_slug, module_id, update_data)
- delete_module(project_slug, module_id)

### Cycle Management Methods
- create_cycle(project_slug, cycle_data)
- update_cycle(project_slug, cycle_id, update_data)
- delete_cycle(project_slug, cycle_id)

### Page/Wiki Management Methods
- create_page(project_slug, page_data)
- update_page(project_slug, page_id, update_data)
- delete_page(project_slug, page_id)
- list_pages(project_slug)

### Enhanced Issue Management
- bulk_create_issues(project_slug, issues_data)
- bulk_update_issues(project_slug, updates)
- move_issue(issue_id, target_project_slug)
- link_issues(issue_id_1, issue_id_2)

### Team/User Management
- list_team_members(project_slug)
- assign_issue(issue_id, user_id)
- update_user_permissions(user_id, permissions)

### Reporting Methods
- get_project_metrics(project_slug)
- get_velocity_report(project_slug)
- get_burndown_chart(cycle_id)
- export_issues(project_slug, format)

## Implementation Approach

Each method would follow the same pattern as existing methods:
1. Add method to PlaneClient for API interaction
2. Add corresponding method to PlaneMcpServer
3. Register method in McpHandler
4. Update documentation

## Example Implementation (create_module)

```php
// In PlaneClient.php
public function createModule(string $projectSlug, array $data): array
{
    try {
        $response = $this->httpClient->post("/api/v1/projects/{$projectSlug}/modules", [
            'json' => $data,
        ]);

        $module = json_decode($response->getBody(), true);
        return $module;
    } catch (RequestException $e) {
        throw new \Exception('Failed to create module: '.$e->getMessage());
    }
}

// In PlaneMcpServer.php
public function createModule(string $projectSlug, array $data): array
{
    try {
        $module = $this->client->createModule($projectSlug, $data);
        return [
            'success' => true,
            'module' => $module,
            'message' => 'Module created successfully',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}

// In McpHandler.php handleRequest method
case 'create_module':
    $projectSlug = $params['project_slug'] ?? null;
    $data = $params['data'] ?? [];

    if (!$projectSlug) {
        return ['success' => false, 'error' => 'Project slug is required'];
    }

    if (empty($data)) {
        return ['success' => false, 'error' => 'Module data is required'];
    }

    return $this->server->createModule($projectSlug, $data);
```

## Benefits for Claude Code Integration

With these extensions, Claude Code could:
- Automatically create modules for new feature areas
- Set up development cycles/sprints
- Create documentation pages for new features
- Manage project structure programmatically
- Generate reports on development progress
- Handle team assignments and permissions

This would enable a fully automated project management workflow where Claude Code can not only track progress but actively manage the project structure in Plane.