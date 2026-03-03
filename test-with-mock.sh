#!/bin/bash

# Test script for Plane MCP Server with Mock API

echo "Testing Plane MCP Server with Mock API..."
echo "=========================================="

# Start the mock API server in the background
echo "Starting mock API server..."
php -S localhost:8081 mock-api.php > mock-api.log 2>&1 &
MOCK_API_PID=$!

# Wait a moment for server to start
sleep 2

# Update the .env to use our mock API
echo "PLANE_BASE_URL=http://localhost:8081" > .env
echo "PLANE_API_TOKEN=test-token" >> .env
echo "PLANE_DEFAULT_PROJECT=supporthub" >> .env

# Start the MCP server in the background
echo "Starting MCP server..."
php -S localhost:8080 server.php > server.log 2>&1 &
SERVER_PID=$!

# Wait a moment for server to start
sleep 2

# Test GET request (should return server info)
echo "Testing GET request..."
curl -s http://localhost:8080

echo ""
echo ""
echo "Testing POST request (list_projects)..."
# Test POST request
curl -s -X POST http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"method": "list_projects", "params": {}}'

echo ""
echo ""
echo "Testing POST request (get_project)..."
# Test get_project request
curl -s -X POST http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"method": "get_project", "params": {"project_slug": "supporthub"}}'

echo ""
echo ""
echo "Stopping servers..."
kill $SERVER_PID $MOCK_API_PID 2>/dev/null

echo "Test complete!"