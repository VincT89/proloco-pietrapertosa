export default function Select({ label, value, onChange, options, name, required, style }) {
  const containerStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '6px',
    width: '100%',
    ...style
  };

  const labelStyle = {
    fontSize: '0.75rem',
    fontWeight: '600',
    color: 'var(--admin-muted)',
    textTransform: 'uppercase',
    letterSpacing: '0.05em'
  };

  const selectStyle = {
    width: '100%',
    padding: '12px 14px',
    border: '1px solid var(--admin-border)',
    borderRadius: '4px',
    background: '#fff',
    color: 'var(--admin-ink)',
    fontSize: '0.95rem',
    fontFamily: 'inherit',
    transition: 'border-color 0.2s',
    outline: 'none',
    cursor: 'pointer'
  };

  return (
    <div style={containerStyle}>
      {label && <label style={labelStyle}>{label}</label>}
      <select
        name={name}
        value={value}
        onChange={onChange}
        required={required}
        style={selectStyle}
        onFocus={(e) => e.target.style.borderColor = 'var(--admin-accent)'}
        onBlur={(e) => e.target.style.borderColor = 'var(--admin-border)'}
      >
        {options.map((opt, idx) => (
          <option key={idx} value={opt.value}>{opt.label}</option>
        ))}
      </select>
    </div>
  );
}
