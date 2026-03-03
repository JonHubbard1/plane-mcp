# Project Management with Claude Code

This document explains how Claude Code can use the Plane MCP server to create and manage new projects.

## Available Project Management Methods

The Plane MCP server provides the following methods for project management:

### `create_project`
Create a new project in Plane.

**Parameters:**
- `data` (array, required) - Project data including:
  - `name` (string) - Project name
  - `identifier` (string) - Unique project identifier/slug
  - `description` (string, optional) - Brief description of the project

**Example:**
```javascript
const result = await mcp.call('create_project', {
  data: {
    name: 'Client Portal System',
    identifier: 'client-portal',
    description: 'Portal system for client self-service and support ticket management'
  }
});
```

### `update_project`
Update an existing project.

**Parameters:**
- `project_slug` (string, required) - The project identifier
- `data` (array, required) - Updated project data

**Example:**
```javascript
const result = await mcp.call('update_project', {
  project_slug: 'client-portal',
  data: {
    description: 'Enhanced portal system with additional client management features'
  }
});
```

### `delete_project`
Delete a project.

**Parameters:**
- `project_slug` (string, required) - The project identifier

**Example:**
```javascript
const result = await mcp.call('delete_project', {
  project_slug: 'client-portal'
});
```

### `get_project`
Retrieve a specific project (already implemented).

### `list_projects`
List all projects (already implemented).

## How Claude Code Can Use Project Management

### Automatic Project Creation

Claude Code can automatically create new projects when starting work on new initiatives:

```javascript
// Create a new project when starting work on a client request
async function startNewProject(clientName, projectName, description) {
  // Create the project
  const projectResult = await mcp.call('create_project', {
    data: {
      name: `${clientName} - ${projectName}`,
      identifier: `${clientName.toLowerCase().replace(/\s+/g, '-')}-${projectName.toLowerCase().replace(/\s+/g, '-')}`,
      description: description
    }
  });

  if (projectResult.success) {
    console.log(`Created project: ${projectResult.project.name}`);

    // Automatically create initial documentation
    await mcp.call('create_page', {
      project_slug: projectResult.project.identifier,
      data: {
        name: 'Project Overview',
        content: `# ${projectName}\n\n## Client\n${clientName}\n\n## Overview\n${description}\n\n## Objectives\n- Objective 1\n- Objective 2\n- Objective 3`,
        description: `Overview document for ${projectName}`
      }
    });

    // Create initial modules
    const modules = ['Core Platform', 'Authentication', 'Dashboard', 'API Integration'];
    for (const moduleName of modules) {
      await mcp.call('create_module', {
        project_slug: projectResult.project.identifier,
        data: {
          name: moduleName,
          description: `Module for ${moduleName.toLowerCase()} functionality`
        }
      });
    }

    // Create initial development cycle
    await mcp.call('create_cycle', {
      project_slug: projectResult.project.identifier,
      data: {
        name: 'Phase 1 - MVP',
        description: 'Minimum viable product development',
        start_date: new Date().toISOString().split('T')[0],
        end_date: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] // 30 days from now
      }
    });

    return projectResult.project;
  } else {
    throw new Error(`Failed to create project: ${projectResult.error}`);
  }
}
```

### Project Bootstrapping

Claude Code can bootstrap entire projects with standard structure:

```javascript
// Bootstrap a new project with standard components
async function bootstrapProject(projectName, clientName) {
  // Create project
  const project = await startNewProject(clientName, projectName, `Project for ${clientName}`);

  // Create standard documentation pages
  const standardDocs = [
    {
      name: 'Requirements',
      content: '# Requirements\n\n## Functional Requirements\n\n## Non-Functional Requirements\n\n## Constraints',
      description: 'Project requirements document'
    },
    {
      name: 'Architecture',
      content: '# Architecture\n\n## High-Level Design\n\n## Technology Stack\n\n## Data Flow',
      description: 'System architecture document'
    },
    {
      name: 'Development Guidelines',
      content: '# Development Guidelines\n\n## Coding Standards\n\n## Branching Strategy\n\n## Testing Approach',
      description: 'Development process guidelines'
    }
  ];

  for (const doc of standardDocs) {
    await mcp.call('create_page', {
      project_slug: project.identifier,
      data: doc
    });
  }

  // Create standard modules based on project type
  const standardModules = [
    { name: 'Authentication', description: 'User authentication and authorization' },
    { name: 'Dashboard', description: 'Main user interface and navigation' },
    { name: 'API', description: 'REST API endpoints' },
    { name: 'Database', description: 'Database schema and migrations' },
    { name: 'Testing', description: 'Unit and integration tests' }
  ];

  for (const module of standardModules) {
    await mcp.call('create_module', {
      project_slug: project.identifier,
      data: module
    });
  }

  console.log(`Project ${project.name} bootstrapped successfully!`);
  return project;
}
```

### Multi-Client Project Management

For agencies or consultants managing multiple clients, Claude Code can create standardized project structures:

```javascript
// Create standardized projects for different client types
async function createClientProject(clientType, clientName) {
  const projectTemplates = {
    'ecommerce': {
      name: `${clientName} E-commerce Platform`,
      description: `E-commerce platform for ${clientName}`,
      modules: ['Product Catalog', 'Shopping Cart', 'Payment Processing', 'Order Management', 'Customer Accounts'],
      docs: ['Product Requirements', 'Payment Integration Guide', 'SEO Strategy']
    },
    'saas': {
      name: `${clientName} SaaS Application`,
      description: `SaaS application for ${clientName}`,
      modules: ['Subscription Management', 'User Management', 'Billing', 'Analytics', 'API'],
      docs: ['API Documentation', 'Billing Integration Guide', 'User Onboarding Process']
    },
    'website': {
      name: `${clientName} Website`,
      description: `Corporate website for ${clientName}`,
      modules: ['Homepage', 'About Us', 'Services', 'Blog', 'Contact'],
      docs: ['Content Strategy', 'SEO Guidelines', 'Maintenance Schedule']
    }
  };

  const template = projectTemplates[clientType];
  if (!template) {
    throw new Error(`Unknown client type: ${clientType}`);
  }

  // Create project
  const projectResult = await mcp.call('create_project', {
    data: {
      name: template.name,
      identifier: `${clientName.toLowerCase().replace(/\s+/g, '-')}-${clientType}`,
      description: template.description
    }
  });

  if (projectResult.success) {
    // Create modules
    for (const moduleName of template.modules) {
      await mcp.call('create_module', {
        project_slug: projectResult.project.identifier,
        data: {
          name: moduleName,
          description: `${moduleName} module for ${clientName}`
        }
      });
    }

    // Create documentation
    for (const docName of template.docs) {
      await mcp.call('create_page', {
        project_slug: projectResult.project.identifier,
        data: {
          name: docName,
          content: `# ${docName}\n\nContent for ${docName} will be added here.`,
          description: `${docName} document for ${clientName}`
        }
      });
    }

    return projectResult.project;
  }
}
```

## Practical Examples

### Creating a New Client Project

```javascript
// When a new client signs up, automatically create their project space
const newProject = await mcp.call('create_project', {
  data: {
    name: 'Acme Corporation - Customer Portal',
    identifier: 'acme-corp-customer-portal',
    description: 'Customer portal for Acme Corporation with self-service features'
  }
});

// Create initial project structure
await mcp.call('create_module', {
  project_slug: 'acme-corp-customer-portal',
  data: {
    name: 'User Management',
    description: 'User registration, authentication, and profile management'
  }
});

await mcp.call('create_page', {
  project_slug: 'acme-corp-customer-portal',
  data: {
    name: 'Project Kickoff Notes',
    content: '# Project Kickoff Meeting\n\n## Attendees\n- John Smith (Client)\n- Jane Doe (Developer)\n\n## Requirements\n1. User registration\n2. Password reset\n3. Profile management\n\n## Timeline\n- Phase 1: 2 weeks\n- Phase 2: 3 weeks',
    description: 'Notes from the project kickoff meeting'
  }
});
```

### Project Template System

Claude Code can maintain and use project templates:

```javascript
// Maintain project templates
const PROJECT_TEMPLATES = {
  'web-app': {
    modules: ['Frontend', 'Backend', 'Database', 'Authentication', 'API'],
    docs: ['Requirements', 'Architecture', 'Development Guidelines', 'Deployment Process'],
    cycles: [
      { name: 'Phase 1 - Foundation', duration: 4 }, // weeks
      { name: 'Phase 2 - Core Features', duration: 6 },
      { name: 'Phase 3 - Polish & Launch', duration: 2 }
    ]
  }
};

// Use template to create project
async function createProjectFromTemplate(templateName, projectName, clientName) {
  const template = PROJECT_TEMPLATES[templateName];
  if (!template) {
    throw new Error(`Template ${templateName} not found`);
  }

  // Create project
  const projectResult = await mcp.call('create_project', {
    data: {
      name: `${clientName} - ${projectName}`,
      identifier: `${clientName.toLowerCase().replace(/\s+/g, '-')}-${projectName.toLowerCase().replace(/\s+/g, '-')}`,
      description: `Project based on ${templateName} template for ${clientName}`
    }
  });

  // Apply template
  for (const moduleName of template.modules) {
    await mcp.call('create_module', {
      project_slug: projectResult.project.identifier,
      data: {
        name: moduleName,
        description: `${moduleName} module`
      }
    });
  }

  for (const docName of template.docs) {
    await mcp.call('create_page', {
      project_slug: projectResult.project.identifier,
      data: {
        name: docName,
        content: `# ${docName}\n\nContent for ${docName}`,
        description: `${docName} document`
      }
    });
  }

  // Create development cycles
  let startDate = new Date();
  for (const cycle of template.cycles) {
    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + (cycle.duration * 7));

    await mcp.call('create_cycle', {
      project_slug: projectResult.project.identifier,
      data: {
        name: cycle.name,
        description: `${cycle.name} development cycle`,
        start_date: startDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0]
      }
    });

    startDate = endDate;
  }

  return projectResult.project;
}
```

## Benefits

With these project management capabilities, Claude Code can:

1. **Automatically create new projects** when opportunities arise
2. **Bootstrap projects with standard structures** for consistency
3. **Maintain project templates** for common project types
4. **Reduce manual setup time** by automating repetitive tasks
5. **Ensure consistent project organization** across all initiatives
6. **Create comprehensive project documentation** from day one
7. **Set up development workflows** with modules and cycles

This enables a truly integrated development workflow where Claude Code can not only track progress but actively create and structure entire projects in Plane.