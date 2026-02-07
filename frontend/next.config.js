/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3001/api'
  },
  // Configure output directory for static export if needed
  distDir: '.next',
  // Public directory is hardcoded to 'public' in Next.js
  // For deployment to hosting requiring 'public_html', use a post-build script
};

module.exports = nextConfig;
