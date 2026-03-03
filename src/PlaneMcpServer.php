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
    public function searchIssues(string $query, string $projectSlug = null): array
    {
        try {
            $params = ['query' => $query];
            if ($projectSlug) {
                $params['project'] = $projectSlug;
            }

            // Assuming there's a search endpoint - this would need to be implemented
            // based on Plane's actual API
            $issues = $this->client->getIssues($projectSlug ?? config('plane-mcp.default_project'), $params);

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
}