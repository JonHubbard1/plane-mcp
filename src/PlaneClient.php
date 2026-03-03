<?php

namespace Technoliga\PlaneMcp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class PlaneClient
{
    protected Client $httpClient;
    protected string $baseUrl;
    protected string $apiToken;
    protected bool $cacheEnabled;
    protected int $cacheTtl;

    public function __construct(array $config = [])
    {
        $this->baseUrl = $config['base_url'] ?? config('plane-mcp.base_url');
        $this->apiToken = $config['api_token'] ?? config('plane-mcp.api_token');
        $this->cacheEnabled = $config['cache_enabled'] ?? config('plane-mcp.cache_enabled', true);
        $this->cacheTtl = $config['cache_ttl'] ?? config('plane-mcp.cache_ttl', 300);

        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiToken,
                'Content-Type' => 'application/json',
            ],
            'timeout' => $config['timeout'] ?? config('plane-mcp.timeout', 30),
        ]);
    }

    /**
     * Get all projects
     */
    public function getProjects(): array
    {
        $cacheKey = 'plane.projects';

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->httpClient->get('/api/v1/projects');
            $projects = json_decode($response->getBody(), true);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $projects, $this->cacheTtl);
            }

            return $projects;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch projects: '.$e->getMessage());
        }
    }

    /**
     * Get project by slug
     */
    public function getProject(string $projectSlug): array
    {
        $cacheKey = "plane.project.{$projectSlug}";

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}");
            $project = json_decode($response->getBody(), true);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $project, $this->cacheTtl);
            }

            return $project;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch project: '.$e->getMessage());
        }
    }

    /**
     * Get issues for a project
     */
    public function getIssues(string $projectSlug, array $params = []): array
    {
        $cacheKey = "plane.issues.{$projectSlug}.".md5(serialize($params));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $queryString = http_build_query($params);
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/issues?{$queryString}");
            $issues = json_decode($response->getBody(), true);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $issues, $this->cacheTtl);
            }

            return $issues;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch issues: '.$e->getMessage());
        }
    }

    /**
     * Get a specific issue
     */
    public function getIssue(string $projectSlug, string $issueId): array
    {
        $cacheKey = "plane.issue.{$projectSlug}.{$issueId}";

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/issues/{$issueId}");
            $issue = json_decode($response->getBody(), true);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $issue, $this->cacheTtl);
            }

            return $issue;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch issue: '.$e->getMessage());
        }
    }

    /**
     * Create a new issue
     */
    public function createIssue(string $projectSlug, array $data): array
    {
        try {
            $response = $this->httpClient->post("/api/v1/projects/{$projectSlug}/issues", [
                'json' => $data,
            ]);

            $issue = json_decode($response->getBody(), true);

            // Clear relevant caches
            Cache::forget("plane.issues.{$projectSlug}");
            Cache::forget("plane.project.{$projectSlug}");

            return $issue;
        } catch (RequestException $e) {
            throw new \Exception('Failed to create issue: '.$e->getMessage());
        }
    }

    /**
     * Update an existing issue
     */
    public function updateIssue(string $projectSlug, string $issueId, array $data): array
    {
        try {
            $response = $this->httpClient->patch("/api/v1/projects/{$projectSlug}/issues/{$issueId}", [
                'json' => $data,
            ]);

            $issue = json_decode($response->getBody(), true);

            // Clear relevant caches
            Cache::forget("plane.issue.{$projectSlug}.{$issueId}");
            Cache::forget("plane.issues.{$projectSlug}");

            return $issue;
        } catch (RequestException $e) {
            throw new \Exception('Failed to update issue: '.$e->getMessage());
        }
    }

    /**
     * Get modules for a project
     */
    public function getModules(string $projectSlug): array
    {
        $cacheKey = "plane.modules.{$projectSlug}";

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/modules");
            $modules = json_decode($response->getBody(), true);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $modules, $this->cacheTtl);
            }

            return $modules;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch modules: '.$e->getMessage());
        }
    }

    /**
     * Get cycles for a project
     */
    public function getCycles(string $projectSlug): array
    {
        $cacheKey = "plane.cycles.{$projectSlug}";

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/cycles");
            $cycles = json_decode($response->getBody(), true);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $cycles, $this->cacheTtl);
            }

            return $cycles;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch cycles: '.$e->getMessage());
        }
    }
}