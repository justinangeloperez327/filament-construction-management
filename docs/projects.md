# Projects

Group 4 introduces the project as the central business context.

## Project record

Projects include:

- project number and name
- optional short name and description
- project type
- location, city and country
- contract value and currency
- start, planned completion, actual completion and defects-liability dates
- planned and actual progress
- lifecycle status

Projects use soft deletes because they are long-lived business records.

## Project memberships

Project memberships link:

```text
Project + User + Project-scoped Role
```

A user can hold more than one project role when required. Memberships support effective start/end dates and an active flag.

Global application roles and project roles are deliberately separate.

- Global permissions can apply across every project.
- Project permissions apply only through an active membership on that specific project.

All project roles receive `projects.view`. The seeded Project Manager role also receives `projects.update`.

## Project visibility

Project list queries are scoped through `Project::visibleTo()`.

A user sees:

- all projects when a global `projects.view_any` permission applies, or
- only projects where that user has an active project membership.

Direct record authorization uses the same project-aware permission model.

## Stakeholders

Companies are assigned to each project with a project-specific relationship:

- Client
- Consultant
- Main Contractor
- Contractor
- Subcontractor
- Supplier
- Government Entity
- Other

A stakeholder can be marked primary for each relationship type. This avoids assuming that a company's global classification is identical on every project.

## Project contacts

Existing company contacts can be attached to a project. The Filament selector limits contacts to companies already assigned as project stakeholders.

## Filament

The Projects resource includes:

- list, create, view and edit pages
- project status and country filters
- project manager and client summaries
- Project Team relation manager
- Stakeholders relation manager
- Project Contacts relation manager

Project members with read-only project roles can view their project but cannot edit project data or relationship records.
