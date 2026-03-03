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
    } elseif ($method === 'GET' && preg_match('/\/api\/v1\/projects\/([^\/]+)$/', $path, $matches)) {
        // Get specific project
        $projectSlug = $matches[1];
        echo json_encode([
            'id' => 'proj-1',
            'name' => 'SupportHub',
            'identifier' => $projectSlug,
            'description' => 'SupportHub project for IT management'
        ]);
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
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Page not found.']);
}