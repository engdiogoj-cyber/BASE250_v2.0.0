#!/bin/bash
# Deployment script for hosting providers that require public_html

# Build the Next.js application
echo "Building Next.js application..."
npm run build

# Create public_html directory if it doesn't exist
echo "Setting up public_html directory..."
mkdir -p ../public_html

# Copy the built application to public_html
echo "Copying build output to public_html..."
cp -r .next/static ../public_html/
cp -r public/* ../public_html/

# Copy standalone output if using standalone mode
if [ -d ".next/standalone" ]; then
  cp -r .next/standalone/* ../public_html/
fi

echo "Deployment to public_html completed!"
echo "Note: Configure your web server to serve from public_html directory"
