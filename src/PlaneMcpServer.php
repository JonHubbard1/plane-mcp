<?php

namespace Technoliga\PlaneMcp;

use Illuminate\Http\Request;

class PlaneMcpServer
{
    protected PlaneClient $client;

    public function __construct(PlaneClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all projects in Plane
     */
    public function listProjects(): array
    {
        try {
            $projects = $this->client->getProjects();

            return [
                'success' => true,
                'projects' => $projects,
                'count' => count($projects),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get details of a specific project
     */
    public function getProject(string $projectSlug): array
    {
        try {
            $project = $this->client->getProject($projectSlug);

            return [
                'success' => true,
                'project' => $project,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List issues for a project
     */
    public function listIssues(string $projectSlug, array $filters = []): array
    {
        try {
            $issues = $this->client->getIssues($projectSlug, $filters);

            return [
                'success' => true,
                'issues' => $issues,
                'count' => count($issues),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get details of a specific issue
     */
    public function getIssue(string $projectSlug, string $issueId): array
    {
        try {
            $issue = $this->client->getIssue($projectSlug, $issueId);

            return [
                'success' => true,
                'issue' => $issue,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a new issue
     */
    public function createIssue(string $projectSlug, array $data): array
    {
        try {
            $issue = $this->client->createIssue($projectSlug, $data);

            return [
                'success' => true,
                'issue' => $issue,
                'message' => 'Issue created successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update an existing issue
     */
    public function updateIssue(string $projectSlug, string $issueId, array $data): array
    {
        try {
            $issue = $this->client->updateIssue($projectSlug, $issueId, $data);

            return [
                'success' => true,
                'issue' => $issue,
                'message' => 'Issue updated successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List modules for a project
     */
    public function listModules(string $projectSlug): array
    {
        try {
            $modules = $this->client->getModules($projectSlug);

            return [
                'success' => true,
                'modules' => $modules,
                'count' => count($modules),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List cycles for a project
     */
    public function listCycles(string $projectSlug): array
    {
        try {
            $cycles = $this->client->getCycles($projectSlug);

            return [
                'success' => true,
                'cycles' => $cycles,
                'count' => count($cycles),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Search issues across projects
     */
    public function searchIssues(string $query, ?string $projectSlug = null): array
    {
        try {
            $params = ['query' => $query];
            if ($projectSlug) {
                $params['project'] = $projectSlug;
            }

            // Assuming there's a search endpoint - this would need to be implemented
            // based on Plane's actual API
            $defaultProject = $_ENV['PLANE_DEFAULT_PROJECT'] ?? 'default';
            $issues = $this->client->getIssues($projectSlug ?? $defaultProject, $params);

            return [
                'success' => true,
                'issues' => $issues,
                'count' => count($issues),
                'query' => $query,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a new page
     */
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

    /**
     * Update an existing page
     */
    public function updatePage(string $projectSlug, string $pageId, array $data): array
    {
        try {
            $page = $this->client->updatePage($projectSlug, $pageId, $data);

            return [
                'success' => true,
                'page' => $page,
                'message' => 'Page updated successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get a specific page
     */
    public function getPage(string $projectSlug, string $pageId): array
    {
        try {
            $page = $this->client->getPage($projectSlug, $pageId);

            return [
                'success' => true,
                'page' => $page,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List pages for a project
     */
    public function getPages(string $projectSlug): array
    {
        try {
            $pages = $this->client->getPages($projectSlug);

            return [
                'success' => true,
                'pages' => $pages,
                'count' => count($pages),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a page
     */
    public function deletePage(string $projectSlug, string $pageId): array
    {
        try {
            $result = $this->client->deletePage($projectSlug, $pageId);

            return [
                'success' => true,
                'result' => $result,
                'message' => 'Page deleted successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a new project
     */
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

    /**
     * Update an existing project
     */
    public function updateProject(string $projectSlug, array $data): array
    {
        try {
            $project = $this->client->updateProject($projectSlug, $data);

            return [
                'success' => true,
                'project' => $project,
                'message' => 'Project updated successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a project
     */
    public function deleteProject(string $projectSlug): array
    {
        try {
            $result = $this->client->deleteProject($projectSlug);

            return [
                'success' => true,
                'result' => $result,
                'message' => 'Project deleted successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}