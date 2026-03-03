<?php

namespace Technoliga\PlaneMcp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PlaneClient
{
    protected Client $httpClient;
    protected string $baseUrl;
    protected string $apiToken;
    protected bool $cacheEnabled;
    protected int $cacheTtl;
    protected array $cache = [];

    public function __construct(array $config = [])
    {
        $this->baseUrl = $config['base_url'] ?? ($_ENV['PLANE_BASE_URL'] ?? 'https://api.plane.so');
        $this->apiToken = $config['api_token'] ?? ($_ENV['PLANE_API_TOKEN'] ?? '');
        $this->cacheEnabled = $config['cache_enabled'] ?? filter_var($_ENV['PLANE_CACHE_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $this->cacheTtl = $config['cache_ttl'] ?? (int) ($_ENV['PLANE_CACHE_TTL'] ?? 300);

        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiToken,
                'Content-Type' => 'application/json',
            ],
            'timeout' => $config['timeout'] ?? (int) ($_ENV['PLANE_TIMEOUT'] ?? 30),
        ]);
    }

    /**
     * Simple in-memory cache implementation
     */
    protected function getFromCache(string $key)
    {
        if (!$this->cacheEnabled || !isset($this->cache[$key])) {
            return null;
        }

        $item = $this->cache[$key];
        if (time() > $item['expires']) {
            unset($this->cache[$key]);
            return null;
        }

        return $item['data'];
    }

    /**
     * Simple in-memory cache implementation
     */
    protected function setCache(string $key, $data)
    {
        if (!$this->cacheEnabled) {
            return;
        }

        $this->cache[$key] = [
            'data' => $data,
            'expires' => time() + $this->cacheTtl
        ];
    }

    /**
     * Get all projects
     */
    public function getProjects(): array
    {
        $cacheKey = 'plane.projects';

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get('/api/v1/projects');
            $projects = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $projects);

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

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}");
            $project = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $project);

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

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $queryString = http_build_query($params);
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/issues?{$queryString}");
            $issues = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $issues);

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

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/issues/{$issueId}");
            $issue = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $issue);

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
            unset($this->cache["plane.issues.{$projectSlug}"]);
            unset($this->cache["plane.project.{$projectSlug}"]);

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
            unset($this->cache["plane.issue.{$projectSlug}.{$issueId}"]);
            unset($this->cache["plane.issues.{$projectSlug}"]);

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

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/modules");
            $modules = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $modules);

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

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/cycles");
            $cycles = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $cycles);

            return $cycles;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch cycles: '.$e->getMessage());
        }
    }

    /**
     * Create a new module
     */
    public function createModule(string $projectSlug, array $data): array
    {
        try {
            $response = $this->httpClient->post("/api/v1/projects/{$projectSlug}/modules", [
                'json' => $data,
            ]);

            $module = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.modules.{$projectSlug}"]);
            unset($this->cache["plane.project.{$projectSlug}"]);

            return $module;
        } catch (RequestException $e) {
            throw new \Exception('Failed to create module: '.$e->getMessage());
        }
    }

    /**
     * Create a new cycle
     */
    public function createCycle(string $projectSlug, array $data): array
    {
        try {
            $response = $this->httpClient->post("/api/v1/projects/{$projectSlug}/cycles", [
                'json' => $data,
            ]);

            $cycle = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.cycles.{$projectSlug}"]);
            unset($this->cache["plane.project.{$projectSlug}"]);

            return $cycle;
        } catch (RequestException $e) {
            throw new \Exception('Failed to create cycle: '.$e->getMessage());
        }
    }

    /**
     * Create a new page/wiki entry
     */
    public function createPage(string $projectSlug, array $data): array
    {
        try {
            $response = $this->httpClient->post("/api/v1/projects/{$projectSlug}/pages", [
                'json' => $data,
            ]);

            $page = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.pages.{$projectSlug}"]);

            return $page;
        } catch (RequestException $e) {
            throw new \Exception('Failed to create page: '.$e->getMessage());
        }
    }

    /**
     * Update an existing page
     */
    public function updatePage(string $projectSlug, string $pageId, array $data): array
    {
        try {
            $response = $this->httpClient->patch("/api/v1/projects/{$projectSlug}/pages/{$pageId}", [
                'json' => $data,
            ]);

            $page = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.page.{$projectSlug}.{$pageId}"]);
            unset($this->cache["plane.pages.{$projectSlug}"]);

            return $page;
        } catch (RequestException $e) {
            throw new \Exception('Failed to update page: '.$e->getMessage());
        }
    }

    /**
     * Get a specific page
     */
    public function getPage(string $projectSlug, string $pageId): array
    {
        $cacheKey = "plane.page.{$projectSlug}.{$pageId}";

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/pages/{$pageId}");
            $page = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $page);

            return $page;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch page: '.$e->getMessage());
        }
    }

    /**
     * List pages for a project
     */
    public function getPages(string $projectSlug): array
    {
        $cacheKey = "plane.pages.{$projectSlug}";

        if ($cached = $this->getFromCache($cacheKey)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->get("/api/v1/projects/{$projectSlug}/pages");
            $pages = json_decode($response->getBody(), true);

            $this->setCache($cacheKey, $pages);

            return $pages;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch pages: '.$e->getMessage());
        }
    }

    /**
     * Delete a page
     */
    public function deletePage(string $projectSlug, string $pageId): array
    {
        try {
            $response = $this->httpClient->delete("/api/v1/projects/{$projectSlug}/pages/{$pageId}");
            $result = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.page.{$projectSlug}.{$pageId}"]);
            unset($this->cache["plane.pages.{$projectSlug}"]);

            return $result;
        } catch (RequestException $e) {
            throw new \Exception('Failed to delete page: '.$e->getMessage());
        }
    }

    /**
     * Create a new project
     */
    public function createProject(array $data): array
    {
        try {
            $response = $this->httpClient->post("/api/v1/projects/", [
                'json' => $data,
            ]);

            $project = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.projects"]);

            return $project;
        } catch (RequestException $e) {
            throw new \Exception('Failed to create project: '.$e->getMessage());
        }
    }

    /**
     * Update an existing project
     */
    public function updateProject(string $projectSlug, array $data): array
    {
        try {
            $response = $this->httpClient->patch("/api/v1/projects/{$projectSlug}", [
                'json' => $data,
            ]);

            $project = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.project.{$projectSlug}"]);
            unset($this->cache["plane.projects"]);

            return $project;
        } catch (RequestException $e) {
            throw new \Exception('Failed to update project: '.$e->getMessage());
        }
    }

    /**
     * Delete a project
     */
    public function deleteProject(string $projectSlug): array
    {
        try {
            $response = $this->httpClient->delete("/api/v1/projects/{$projectSlug}");
            $result = json_decode($response->getBody(), true);

            // Clear relevant caches
            unset($this->cache["plane.project.{$projectSlug}"]);
            unset($this->cache["plane.projects"]);

            return $result;
        } catch (RequestException $e) {
            throw new \Exception('Failed to delete project: '.$e->getMessage());
        }
    }
}