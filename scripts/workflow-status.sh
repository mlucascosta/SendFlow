#!/usr/bin/env bash
set -euo pipefail

required=(
  "docs/workflow/README.md"
  "docs/workflow/foundation/architecture.md"
  "docs/workflow/foundation/integrations.md"
  "docs/workflow/foundation/environments.md"
  "docs/workflow/foundation/glossary.md"
  "docs/workflow/runbooks/installer-and-database.md"
  "docs/workflow/runbooks/ai-and-automation.md"
)

missing=0
for file in "${required[@]}"; do
  if [ ! -f "$file" ]; then
    echo "MISSING: $file"
    missing=1
  fi
done

if [ "$missing" -eq 0 ]; then
  echo "Workflow foundation is in place."
fi

echo
printf 'Registered changes:\n'
find docs/workflow/changes -mindepth 1 -maxdepth 1 -type d | sort || true
