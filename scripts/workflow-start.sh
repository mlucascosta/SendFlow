#!/usr/bin/env bash
set -euo pipefail

if [ $# -lt 1 ]; then
  echo "Usage: $0 <change-slug>"
  exit 1
fi

slug="$1"
base_dir="docs/workflow/changes/$slug"
template_dir="docs/workflow/templates/change"

mkdir -p "$base_dir"
cp "$template_dir/01-brainstorm.md" "$base_dir/01-brainstorm.md"
cp "$template_dir/02-plan.md" "$base_dir/02-plan.md"
cp "$template_dir/03-review.md" "$base_dir/03-review.md"
cp "$template_dir/04-doc-capture.md" "$base_dir/04-doc-capture.md"

echo "Workflow change scaffold created at: $base_dir"
find "$base_dir" -maxdepth 1 -type f | sort
