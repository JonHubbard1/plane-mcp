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

            case 'create_cycle':
                $projectSlug = $params['project_slug'] ?? null;
                $data = $params['data'] ?? [];

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (empty($data)) {
                    return ['success' => false, 'error' => 'Cycle data is required'];
                }

                return $this->server->createCycle($projectSlug, $data);

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

            case 'update_page':
                $projectSlug = $params['project_slug'] ?? null;
                $pageId = $params['page_id'] ?? null;
                $data = $params['data'] ?? [];

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (!$pageId) {
                    return ['success' => false, 'error' => 'Page ID is required'];
                }

                if (empty($data)) {
                    return ['success' => false, 'error' => 'Update data is required'];
                }

                return $this->server->updatePage($projectSlug, $pageId, $data);

            case 'get_page':
                $projectSlug = $params['project_slug'] ?? null;
                $pageId = $params['page_id'] ?? null;

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (!$pageId) {
                    return ['success' => false, 'error' => 'Page ID is required'];
                }

                return $this->server->getPage($projectSlug, $pageId);

            case 'list_pages':
                $projectSlug = $params['project_slug'] ?? null;

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                return $this->server->getPages($projectSlug);

            case 'delete_page':
                $projectSlug = $params['project_slug'] ?? null;
                $pageId = $params['page_id'] ?? null;

                if (!$projectSlug) {
                    return ['success' => false, 'error' => 'Project slug is required'];
                }

                if (!$pageId) {
                    return ['success' => false, 'error' => 'Page ID is required'];
                }

                return $this->server->deletePage($projectSlug, $pageId);

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
            ],
            'create_module' => [
                'description' => 'Create a new module',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'data' => 'array (required) - Module data'
                ]
            ],
            'create_cycle' => [
                'description' => 'Create a new cycle',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'data' => 'array (required) - Cycle data'
                ]
            ],
            'create_page' => [
                'description' => 'Create a new page',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'data' => 'array (required) - Page data'
                ]
            ],
            'update_page' => [
                'description' => 'Update an existing page',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'page_id' => 'string (required) - The page ID',
                    'data' => 'array (required) - Update data'
                ]
            ],
            'get_page' => [
                'description' => 'Get details of a specific page',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'page_id' => 'string (required) - The page ID'
                ]
            ],
            'list_pages' => [
                'description' => 'List pages for a project',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug'
                ]
            ],
            'delete_page' => [
                'description' => 'Delete a page',
                'parameters' => [
                    'project_slug' => 'string (required) - The project slug',
                    'page_id' => 'string (required) - The page ID'
                ]
            ]
        ];
    }
}