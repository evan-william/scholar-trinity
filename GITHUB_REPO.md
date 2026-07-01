# GitHub Repository Setup

Use this metadata when creating the GitHub repository.

## Repository Title

`trinity-scholar`

## Repository Description

`Trinity Scholar: AP exam registration, payment, and admin management platform`

## Repository Topics

```text
laravel php ap-exam registration-platform student-management payment-flow admin-dashboard bilingual receipt-management
```

## Visibility

Recommended: `Private` while client requirements, payment settings, and branding are still being finalized.

## First Push

```bash
git remote add origin https://github.com/evan-william/trinity-scholar.git
git branch -M main
git push -u origin main
```

## Notes

- Keep `.env`, local databases, uploads, exports, payment proofs, and passport files out of GitHub.
- Review payment gateway and e-invoice credentials before any production deployment.
- Keep the repository private unless the client explicitly approves making it public.
