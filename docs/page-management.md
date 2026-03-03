# Page Management with Claude Code

This document explains how Claude Code can use the Plane MCP server to manage documentation pages and populate them with information.

## Available Page Management Methods

The Plane MCP server provides the following methods for page management:

### `create_page`
Create a new documentation page in Plane.

**Parameters:**
- `project_slug` (string, required) - The project identifier
- `data` (array, required) - Page data including:
  - `name` (string) - Page title
  - `content` (string) - Markdown content for the page
  - `description` (string, optional) - Brief description of the page

**Example:**
```javascript
const result = await mcp.call('create_page', {
  project_slug: 'supporthub',
  data: {
    name: 'AI Help Desk Implementation',
    content: '# AI Help Desk Implementation\n\n## Overview\n...',
    description: 'Technical documentation for AI Help Desk feature'
  }
});
```

### `update_page`
Update an existing documentation page.

**Parameters:**
- `project_slug` (string, required) - The project identifier
- `page_id` (string, required) - The page identifier
- `data` (array, required) - Updated page data

**Example:**
```javascript
const result = await mcp.call('update_page', {
  project_slug: 'supporthub',
  page_id: 'page-123',
  data: {
    content: '# Updated Content\n\n## New Section\n...',
    description: 'Updated documentation'
  }
});
```

### `get_page`
Retrieve a specific documentation page.

**Parameters:**
- `project_slug` (string, required) - The project identifier
- `page_id` (string, required) - The page identifier

**Example:**
```javascript
const result = await mcp.call('get_page', {
  project_slug: 'supporthub',
  page_id: 'page-123'
});
```

### `list_pages`
List all documentation pages in a project.

**Parameters:**
- `project_slug` (string, required) - The project identifier

**Example:**
```javascript
const result = await mcp.call('list_pages', {
  project_slug: 'supporthub'
});
```

### `delete_page`
Delete a documentation page.

**Parameters:**
- `project_slug` (string, required) - The project identifier
- `page_id` (string, required) - The page identifier

**Example:**
```javascript
const result = await mcp.call('delete_page', {
  project_slug: 'supporthub',
  page_id: 'page-123'
});
```

## How Claude Code Can Use Page Management

### Automatic Documentation Generation

Claude Code can automatically create documentation as you develop features:

```javascript
// When implementing a new feature, automatically create documentation
async function implementFeature(featureName, implementationDetails) {
  // Create the feature implementation
  await createFeatureImplementation(featureName, implementationDetails);

  // Automatically create documentation
  await mcp.call('create_page', {
    project_slug: 'supporthub',
    data: {
      name: `${featureName} Implementation`,
      content: `# ${featureName} Implementation\n\n## Overview\n${implementationDetails.overview}\n\n## Architecture\n${implementationDetails.architecture}\n\n## Configuration\n${implementationDetails.configuration}\n\n## Testing\n${implementationDetails.testing}`,
      description: `Technical documentation for the ${featureName} feature`
    }
  });
}
```

### Progressive Documentation Updates

As you refine and improve features, Claude Code can update documentation:

```javascript
// Update documentation when making improvements
async function improveFeature(featureName, improvements) {
  // Get the existing documentation
  const pages = await mcp.call('list_pages', { project_slug: 'supporthub' });
  const featurePage = pages.find(page => page.name.includes(featureName));

  if (featurePage) {
    // Update with new information
    await mcp.call('update_page', {
      project_slug: 'supporthub',
      page_id: featurePage.id,
      data: {
        content: featurePage.content + `\n\n## Improvements\n${improvements.map(i => `- ${i}`).join('\n')}`
      }
    });
  }
}
```

### Knowledge Base Population

Claude Code can populate a knowledge base with technical information:

```javascript
// Create a comprehensive knowledge base
async function populateKnowledgeBase(topics) {
  for (const topic of topics) {
    await mcp.call('create_page', {
      project_slug: 'supporthub',
      data: {
        name: topic.title,
        content: topic.content,
        description: topic.description
      }
    });
  }
}

// Example usage
populateKnowledgeBase([
  {
    title: 'Database Migration Guidelines',
    content: '# Database Migration Guidelines\n\n## Best Practices\n...',
    description: 'Guidelines for creating and running database migrations'
  },
  {
    title: 'API Response Formats',
    content: '# API Response Formats\n\n## Standard Responses\n...',
    description: 'Documentation of standard API response formats'
  }
]);
```

## Practical Examples

### Creating Module Documentation

When Claude Code creates a new module, it can automatically create corresponding documentation:

```javascript
// Create module and documentation together
const moduleResult = await mcp.call('create_module', {
  project_slug: 'supporthub',
  data: {
    name: 'Backup Monitoring',
    description: 'Backup monitoring system'
  }
});

if (moduleResult.success) {
  await mcp.call('create_page', {
    project_slug: 'supporthub',
    data: {
      name: 'Backup Monitoring Module',
      content: '# Backup Monitoring Module\n\n## Purpose\nMonitor backup systems and alert on failures.\n\n## Supported Providers\n- Time Machine Backup (TM)\n- Synology HyperBackup\n- Acronis Backup 4 Business\n- Acronis Backup 4 M365\n\n## Configuration\nSet up provider-specific configurations in the admin panel.',
      description: 'Documentation for the Backup Monitoring module'
    }
  });
}
```

### Development Environment Documentation

Claude Code can create and maintain setup documentation:

```javascript
// Create development environment documentation
await mcp.call('create_page', {
  project_slug: 'supporthub',
  data: {
    name: 'Development Environment Setup',
    content: '# Development Environment Setup\n\n## Requirements\n- PHP 8.3+\n- Composer\n- Node.js 18+\n- MySQL 8 or SQLite\n\n## Installation\n```bash\ncomposer install\nnpm install\nnpm run build\n```\n\n## Configuration\nCopy `.env.example` to `.env` and configure database, API keys, and mail settings.\n\n## Running Tests\n```bash\nphp artisan test\n```',
    description: 'Setup guide for the development environment'
  }
});
```

## Benefits

With these page management capabilities, Claude Code can:

1. **Automatically document** new features as they're developed
2. **Keep documentation current** as code evolves
3. **Create comprehensive knowledge bases** for complex systems
4. **Reduce manual documentation effort** by generating content programmatically
5. **Ensure consistency** between code and documentation
6. **Maintain a living documentation system** that grows with the project

This enables a truly integrated development workflow where documentation is not an afterthought but an integral part of the development process.