<?php

/**
 * Mock Plane API for testing
 *
 * This script simulates a Plane API for testing the MCP server
 * without needing actual Plane credentials.
 */

// Set JSON headers
header('Content-Type: application/json');

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $_SERVER['REQUEST_URI'] ?? '/';
$queryString = $_SERVER['QUERY_STRING'] ?? '';

// Parse input for POST/PUT/PATCH requests
$input = null;
if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
    $input = json_decode(file_get_contents('php://input'), true);
}

// Simulate different API endpoints
if (strpos($path, '/api/v1/projects') === 0) {
    if ($method === 'GET' && $path === '/api/v1/projects') {
        // List projects
        echo json_encode([
            [
                'id' => 'proj-1',
                'name' => 'SupportHub',
                'identifier' => 'supporthub',
                'description' => 'SupportHub project for IT management'
            ],
            [
                'id' => 'proj-2',
                'name' => 'Personal Projects',
                'identifier' => 'personal',
                'description' => 'Personal development projects'
            ]
        ]);
    } elseif ($method === 'POST' && $path === '/api/v1/projects') {
        // Create project
        echo json_encode([
            'id' => 'proj-3',
            'name' => $input['name'] ?? 'New Project',
            'identifier' => $input['identifier'] ?? 'new-project',
            'description' => $input['description'] ?? 'New project description'
        ]);
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)$/', $path, $matches)) {
        // Get specific project
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'proj-1',
            'name' => 'SupportHub',
            'identifier' => $projectSlug,
            'description' => 'SupportHub project for IT management'
        ]);
    } elseif ($method === 'PATCH' && preg_match('/\/api\/v1\/projects\/([^\/]+)$/', $path, $matches)) {
        // Update project
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'proj-1',
            'name' => $input['name'] ?? 'SupportHub',
            'identifier' => $projectSlug,
            'description' => $input['description'] ?? 'Updated description'
        ]);
    } elseif ($method === 'DELETE' && preg_match('/\/api\/v1\/projects\/([^\/]+)$/', $path, $matches)) {
        // Delete project
        $projectSlug = $matches[1];
        echo json_encode(['message' => 'Project deleted successfully']);
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/issues/', $path, $matches)) {
        // Get issues for project
        $projectSlug = $matches[1];
        echo json_encode([
            [
                'id' => 'issue-1',
                'name' => 'Implement user authentication',
                'description' => 'Create login and registration functionality',
                'priority' => 'high',
                'state' => 'started'
            ],
            [
                'id' => 'issue-2',
                'name' => 'Design dashboard UI',
                'description' => 'Create wireframes and mockups for dashboard',
                'priority' => 'medium',
                'state' => 'unstarted'
            ]
        ]);
    } elseif ($method === 'POST' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/issues/', $path, $matches)) {
        // Create issue
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'issue-3',
            'name' => $input['name'] ?? 'New Issue',
            'description' => $input['description'] ?? 'No description',
            'priority' => $input['priority'] ?? 'medium',
            'state' => 'unstarted'
        ]);
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/modules/', $path, $matches)) {
        // Get modules for project
        $projectSlug = $matches[1];
        echo json_encode([
            [
                'id' => 'mod-1',
                'name' => 'Core Platform',
                'description' => 'Core platform functionality'
            ],
            [
                'id' => 'mod-2',
                'name' => 'AI Helpdesk',
                'description' => 'AI-powered helpdesk features'
            ]
        ]);
    } elseif ($method === 'POST' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/modules/', $path, $matches)) {
        // Create module
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'mod-3',
            'name' => $input['name'] ?? 'New Module',
            'description' => $input['description'] ?? 'No description'
        ]);
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/cycles/', $path, $matches)) {
        // Get cycles for project
        $projectSlug = $matches[1];
        echo json_encode([
            [
                'id' => 'cycle-1',
                'name' => 'V1 MVP',
                'description' => 'Minimum viable product release'
            ],
            [
                'id' => 'cycle-2',
                'name' => 'V1.5 Status Board',
                'description' => 'Status board release'
            ]
        ]);
    } elseif ($method === 'POST' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/cycles/', $path, $matches)) {
        // Create cycle
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'cycle-3',
            'name' => $input['name'] ?? 'New Cycle',
            'description' => $input['description'] ?? 'No description',
            'start_date' => $input['start_date'] ?? date('Y-m-d'),
            'end_date' => $input['end_date'] ?? date('Y-m-d', strtotime('+2 weeks'))
        ]);
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/pages/', $path, $matches)) {
        // Get pages for project
        $projectSlug = $matches[1];
        echo json_encode([
            [
                'id' => 'page-1',
                'name' => 'Project Overview',
                'content' => 'Overview of the SupportHub project'
            ],
            [
                'id' => 'page-2',
                'name' => 'API Documentation',
                'content' => 'API endpoints and usage examples'
            ]
        ]);
    } elseif ($method === 'POST' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/pages/', $path, $matches)) {
        // Create page
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'page-3',
            'name' => $input['name'] ?? 'New Page',
            'content' => $input['content'] ?? 'Page content',
            'description' => $input['description'] ?? ''
        ]);
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/pages\/([^\/]+)/', $path, $matches)) {
        // Get specific page
        $projectSlug = $matches[1];
        $pageId = $matches[2];
        echo json_encode([
            'id' => $pageId,
            'name' => 'Sample Page',
            'content' => 'This is the content of the sample page',
            'description' => 'A sample page for demonstration'
        ]);
    } elseif ($method === 'PATCH' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/pages\/([^\/]+)/', $path, $matches)) {
        // Update page
        $projectSlug = $matches[1];
        $pageId = $matches[2];
        echo json_encode([
            'id' => $pageId,
            'name' => $input['name'] ?? 'Updated Page',
            'content' => $input['content'] ?? 'Updated content',
            'description' => $input['description'] ?? 'Updated description'
        ]);
    } elseif ($method === 'DELETE' && preg_match('/\/api\/v1\/projects\/([^\/]+)\/pages\/([^\/]+)/', $path, $matches)) {
        // Delete page
        $projectSlug = $matches[1];
        $pageId = $matches[2];
        echo json_encode(['message' => 'Page deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Page not found.']);
}