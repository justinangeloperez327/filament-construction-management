# Company Directory

Group 3 introduces a single company directory shared by projects, procurement, contracts and external users.

## Company classifications

A company can have more than one classification. Supported seeded classifications are:

- Client
- Consultant
- Main Contractor
- Contractor
- Subcontractor
- Supplier
- Government Entity
- Other

This is a many-to-many relationship rather than separate client, consultant and supplier tables.

## Contacts and addresses

Companies can maintain multiple contacts and addresses. One contact and one address may be marked primary. Marking a new record primary automatically clears the previous primary record for the same company.

## Compliance documents

Company compliance documents support:

- Trade License
- VAT Certificate
- Bank Letter
- Registration Document
- Insurance
- Prequalification
- Other

Files use the configured private construction document disk. Expiry dates are indexed and the model exposes helpers for expired and soon-to-expire documents so later dashboard and notification groups can reuse the same logic.

## Users

Users may optionally belong to a company. This supports future client, consultant, subcontractor and supplier accounts without changing the authentication model.

## Authorization

Company, contact, address and company-document actions use the existing `companies.*` permission family.
