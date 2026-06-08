import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  async rewrites() {
    return [
      { source: '/en', destination: '/?lang=en' },
      { source: '/en/news', destination: '/notizie?lang=en' },
      { source: '/en/news/:id', destination: '/notizie/:id?lang=en' },
      { source: '/en/events', destination: '/eventi?lang=en' },
      { source: '/en/events/:id', destination: '/eventi/:id?lang=en' },
      { source: '/en/pro-loco', destination: '/pro-loco?lang=en' },
      { source: '/en/discover', destination: '/scopri?lang=en' },
      { source: '/en/stories', destination: '/storie?lang=en' },
      { source: '/en/stories/:id', destination: '/storie/:id?lang=en' },
      { source: '/en/territory', destination: '/territorio?lang=en' },
      { source: '/en/tastes', destination: '/sapori?lang=en' },
      { source: '/en/community', destination: '/comunita?lang=en' },
      { source: '/en/gallery', destination: '/galleria?lang=en' },
    ];
  },
  async redirects() {
    return [
      {
        source: '/il-borgo',
        destination: '/scopri',
        permanent: true,
      },
      {
        source: '/esperienze',
        destination: '/scopri',
        permanent: true,
      },
      {
        source: '/contatti',
        destination: '/pro-loco',
        permanent: true,
      },
    ];
  },
};

export default nextConfig;
