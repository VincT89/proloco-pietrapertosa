export default function Button({ children, type = 'button', variant = 'primary', onClick, disabled, className = '', icon }) {
  const baseStyle = {
    display: 'inline-flex',
    alignItems: 'center',
    gap: '8px',
    padding: '12px 24px',
    borderRadius: '4px',
    border: 'none',
    cursor: disabled ? 'not-allowed' : 'pointer',
    fontSize: '0.85rem',
    fontWeight: '600',
    letterSpacing: '0.05em',
    textTransform: 'uppercase',
    transition: 'all 0.3s ease',
    opacity: disabled ? 0.6 : 1
  };

  const variants = {
    primary: {
      background: 'var(--admin-accent)',
      color: 'white'
    },
    danger: {
      background: '#ef4444',
      color: 'white'
    },
    ghost: {
      background: 'transparent',
      color: 'var(--admin-muted)',
      padding: '8px 12px'
    }
  };

  const hoverVariants = {
    primary: { background: 'var(--admin-accent-hover)' },
    danger: { background: '#dc2626' },
    ghost: { color: 'var(--admin-ink)', background: 'rgba(0,0,0,0.03)' }
  };

  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled}
      className={className}
      style={{ ...baseStyle, ...variants[variant] }}
      onMouseEnter={(e) => {
        if (!disabled && variant !== 'ghost') e.target.style.background = hoverVariants[variant].background;
        if (!disabled && variant === 'ghost') {
          e.target.style.background = hoverVariants.ghost.background;
          e.target.style.color = hoverVariants.ghost.color;
        }
      }}
      onMouseLeave={(e) => {
        if (!disabled && variant !== 'ghost') e.target.style.background = variants[variant].background;
        if (!disabled && variant === 'ghost') {
          e.target.style.background = variants.ghost.background;
          e.target.style.color = variants.ghost.color;
        }
      }}
    >
      {icon && <span style={{ display: 'flex', width: '16px', height: '16px' }}>{icon}</span>}
      {children}
    </button>
  );
}
