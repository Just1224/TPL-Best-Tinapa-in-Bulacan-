# CI/CD Pipeline TODO (Docker Focus)

## Completed: 3/12

### Phase 1: Preparation (6/6) ✅
- [x] 1. Create `composer.json` for dev deps (PHPUnit optional, focus tools)
- [x] 2. Add basic test files (Docker smoke, PHP lint scripts)
- [x] 3. Update `package.json` scripts (Docker build/test for Node if relevant)
- [x] 3.1 Add Node test & .dockerignore


### Phase 2: CI Workflow (1/4)
- [x] 4. Create `.github/workflows/ci.yml` (PHP lint, Node test, Docker build/test)
- [ ] 5. Delete old `php-ci.yml`
- [ ] 6. Add `.github/dependabot.yml`
- [ ] 7. Update README.md with new CI/CD details

### Phase 3: CD Workflow (0/3)
- [ ] 8. Create `.github/workflows/cd.yml` (Docker build/push to GHCR on main)
- [ ] 9. Enhance Dockerfile (healthcheck, .dockerignore)
- [ ] 10. Add docker-compose.example.yml for local DB+app

### Phase 4: Deploy & Test (0/2)
- [ ] 11. Setup Render Docker service (if not), add secrets
- [ ] 12. Test full pipeline (branch push, verify Actions/GHCR)

**Next Step: Phase 1.3 - Update package.json & add Node test**


