# Project Structure

Group 5 adds reusable construction breakdown data below each project.

## Hierarchy

The project hierarchy is:

```text
Project
└── Area / Zone
    └── Asset / Building
        └── Level / Floor
            └── Location
```

Every structure record carries its own `project_id` for efficient project scoping while also retaining the parent foreign key that enforces its place in the hierarchy.

Model validation rejects cross-project parent assignments.

## Disciplines and trades

Disciplines and trades are shared master data.

Seeded disciplines include:

- Civil
- Structural
- Architectural
- Mechanical
- Electrical
- Plumbing
- Fire Fighting
- ICT
- ELV
- Landscape
- Infrastructure
- Other

Trades may belong to a discipline. Work package forms use a dependent Trade selector so only trades belonging to the selected discipline are offered.

## Work packages

A work package belongs to a project and may reference:

- discipline
- trade
- responsible contractor
- planned start
- planned finish
- status

The contractor selector is limited to companies already assigned to the project as Main Contractor, Contractor or Subcontractor.

## Access control

All project-scoped roles receive read access to project structure and work packages.

Project Manager and Construction Manager receive create, update and delete rights for project structure and work packages on projects where they hold an active membership.

Discipline and Trade master-data resources use separate global permissions.

## Filament

Project pages now expose relation managers for:

- Areas
- Assets
- Levels
- Locations
- Work Packages

Disciplines and Trades are available as master-data resources under the Projects navigation group.
