# Connecting Claude Code to Plane MCP Server

This guide explains how to connect Claude Code to the Plane MCP server for project management integration.

## Prerequisites

1. Plane MCP Server running (instructions in README.md)
2. Claude Code installed and configured
3. Plane instance with API access

## Configuration Steps

### 1. Start the Plane MCP Server

```bash
# Navigate to the Plane_MCP directory
cd /path/to/Plane_MCP

# Configure your Plane credentials
cp .env.example .env
# Edit .env with your actual Plane credentials

# Start the server
php -S localhost:8080 server.php
```

The server should now be running at `http://localhost:8080`.

### 2. Configure Claude Code

Claude Code can connect to the MCP server using the standard MCP protocol. The server exposes the following capabilities:

- **list_projects** - Get all projects
- **get_project** - Get details of a specific project
- **list_issues** - List issues for a project
- **get_issue** - Get details of a specific issue
- **create_issue** - Create a new issue
- **update_issue** - Update an existing issue
- **list_modules** - Get modules for a project
- **list_cycles** - Get cycles for a project
- **search_issues** - Search issues across projects

### 3. Using with Claude Code

Once connected, Claude Code can perform project management tasks such as:

```javascript
// Example of how Claude Code might use the MCP server
const projectStatus = await mcp.call('list_projects');
const issues = await mcp.call('list_issues', { project_slug: 'supporthub' });
const newIssue = await mcp.call('create_issue', {
  project_slug: 'supporthub',
  data: {
    name: 'Implement feature X',
    description: 'Detailed description of the feature',
    priority: 'high'
  }
});
```

### 4. Common Use Cases

#### Tracking Development Progress
Claude Code can periodically check project status and report on:
- Number of open issues
- Issues assigned to specific team members
- Progress through development cycles
- Module completion status

#### Automated Issue Creation
Claude Code can automatically create issues when:
- Code review identifies potential problems
- Testing reveals bugs
- New feature requests are discussed
- Technical debt is discovered

#### Project Reporting
Claude Code can generate reports on:
- Sprint progress
- Team velocity
- Issue resolution times
- Project milestone status

## Troubleshooting

### Connection Issues
- Ensure the MCP server is running (`php -S localhost:8080 server.php`)
- Verify the server URL is correct in Claude Code configuration
- Check that Plane API credentials are valid

### Authentication Errors
- Verify your Plane API token has appropriate permissions
- Ensure the token hasn't expired
- Check that the base URL is correct for your Plane instance

### Performance Issues
- Adjust cache settings in `.env` file
- Check network connectivity to your Plane instance
- Verify Plane instance performance

## Security Considerations

- Store API tokens securely in environment variables
- Use HTTPS for production deployments
- Limit API token permissions to only what's necessary
- Regularly rotate API tokens

## Extending the Integration

The Plane MCP server can be extended to support additional Plane features:
- Custom fields and metadata
- Time tracking integration
- Notification systems
- Advanced reporting capabilities

Refer to the Plane API documentation for available endpoints that can be added to the MCP server.