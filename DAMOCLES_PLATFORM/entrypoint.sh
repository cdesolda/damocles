#!/bin/bash

set -o errexit
set -o nounset
set -o pipefail

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "MySQL is ready"

# Save the current Git commit hash to a file
echo "Saving Git commit hash..."
if [ -d .git ]; then
  git rev-parse --short HEAD > storage/git_version
  echo "Git commit hash saved to storage/git_version: $(cat storage/git_version)"
else
  echo "No .git directory found; skipping Git commit hash."
fi

echo "Checking if migrations and seeding are needed..."

set +e
php artisan migrate:status
if [ $? -eq 1 ]; then
  TO_MIGRATE=1
else
  php artisan migrate:status | grep -q "Pending"
  if [ $? -eq 0 ]; then
    TO_MIGRATE=1
  else
    TO_MIGRATE=0
  fi
fi

set -e
if [ $TO_MIGRATE -eq 1 ]; then
  echo "Starting Laravel migration and seeding process..."
  php artisan migrate --force
  php artisan db:seed --force
  echo "Migration and seeding completed successfully."
else
  echo "Migrations and seeding already done, skipping."
fi

# Generate the application key
php artisan key:generate

# Run the queue and schedule workers
php artisan queue:work &
php artisan schedule:work &

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000
