<?php

namespace Technoliga\PlaneMcp;

class McpHandler
{
    protected PlaneMcpServer $server;

    public function __construct(PlaneMcpServer $server)
    {
        $this->server = $server;
    }

    /**
     * Handle MCP requests
     */
    public function handleRequest(string $method, array $params = []): array
    {
        switch ($method) {
            case 'list_projects':
                return $this->server->listProjects();

            case 'get_project':
                $projectSlug = $params['project_slug'] ?? null;
                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }
                return $this->server->getProject($projectSlug);

            case 'list_issues':
                $projectSlug = $params['project_slug'] ?? null;
                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }
                $filters = $params['filters'] ?? [];
                return $this->server->listIssues($projectSlug, $filters);

            case 'get_issue':
                $projectSlug = $params['project_slug'] ?? null;
                $issueId = $params['issue_id'] ?? null;

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (!$issueId) {
                    return ['success' => false, 'error' => 'Issue ID is required'];
                }

                return $this->server->getIssue($projectSlug, $issueId);

            case 'create_issue':
                $projectSlug = $params['project_slug'] ?? null;
                $data = $params['data'] ?? [];

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (empty($data)) {
                    return ['success' => false, 'error' => 'Issue data is required'];
                }

                return $this->server->createIssue($projectSlug, $data);

            case 'update_issue':
                $projectSlug = $params['project_slug'] ?? null;
                $issueId = $params['issue_id'] ?? null;
                $data = $params['data'] ?? [];

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (!$issueId) {
                    return ['success' => false, 'error' => 'Issue ID is required'];
                }

                if (empty($data)) {
                    return ['success' => false, 'error' => 'Update data is required'];
                }

                return $this->server->updateIssue($projectSlug, $issueId, $data);

            case 'list_modules':
                $projectSlug = $params['project_slug'] ?? null;
                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }
                return $this->server->listModules($projectSlug);

            case 'list_cycles':
                $projectSlug = $params['project_slug'] ?? null;
                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }
                return $this->server->listCycles($projectSlug);

            case 'search_issues':
                $query = $params['query'] ?? null;
                if (!$query) {
                    return ['success' => false, 'error' => 'Search query is required'];
                }
                $projectSlug = $params['project_slug'] ?? null;
                return $this->server->searchIssues($query, $projectSlug);

            default:
                return ['success' => false, 'error' => 'Unknown method: ' . $method];
        }
    }

    /**
     * Get available tools/methods
     */
    public function getAvailableTools(): array
    {
        return [
            'list_projects' => [
                'description' => 'List all projects in Plane',
                'parameters' => []
            ],
            'get_project' => [
                'description' => 'Get details of a specific project',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug'
                ]
            ],
            'list_issues' => [
                'description' => 'List issues for a project',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'filters' => 'array (optional) - Filter parameters'
                ]
            ],
            'get_issue' => [
                'description' => 'Get details of a specific issue',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'issue_id' => 'string (required) - The issue ID'
                ]
            ],
            'create_issue' => [
                'description' => 'Create a new issue',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'data' => 'array (required) - Issue data'
                ]
            ],
            'update_issue' => [
                'description' => 'Update an existing issue',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'issue_id' => 'string (required) - The issue ID',
                    'data' => 'array (required) - Update data'
                ]
            ],
            'list_modules' => [
                'description' => 'List modules for a project',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug'
                ]
            ],
            'list_cycles' => [
                'description' => 'List cycles for a project',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug'
                ]
            ],
            'search_issues' => [
                'description' => 'Search issues across projects',
                'parameters' => [
                    'query' => 'string (required) - Search query',
                    'project_slug' => 'string (optional) - Limit search to specific project'
                ]
            ]
        ];
    }
}