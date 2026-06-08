"use client";
import { useState } from 'react';

export default function Tabs({ tabs }) {
  const [activeTab, setActiveTab] = useState(0);

  const containerStyle = {
    display: 'flex',
    flexDirection: 'column',
    width: '100%'
  };

  const headerStyle = {
    display: 'flex',
    borderBottom: '1px solid var(--admin-border)',
    marginBottom: '24px',
    gap: '32px'
  };

  const getTabStyle = (isActive) => ({
    padding: '12px 0',
    background: 'none',
    border: 'none',
    borderBottom: isActive ? '2px solid var(--admin-accent)' : '2px solid transparent',
    color: isActive ? 'var(--admin-ink)' : 'var(--admin-muted)',
    fontSize: '0.95rem',
    fontWeight: isActive ? '600' : '400',
    cursor: 'pointer',
    transition: 'all 0.2s ease',
    marginBottom: '-1px'
  });

  return (
    <div style={containerStyle}>
      <div style={headerStyle}>
        {tabs.map((tab, index) => (
          <button
            key={index}
            type="button"
            style={getTabStyle(activeTab === index)}
            onClick={() => setActiveTab(index)}
          >
            {tab.label}
          </button>
        ))}
      </div>
      <div>
        {tabs[activeTab].content}
      </div>
    </div>
  );
}
