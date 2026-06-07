import type { NextConfig } from "next";

const nextConfig: NextConfig = {
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
