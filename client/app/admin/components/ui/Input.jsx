export default function Input({ label, type = 'text', value, onChange, placeholder, required, name, readOnly }) {
  const containerStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '6px',
    width: '100%'
  };

  const labelStyle = {
    fontSize: '0.75rem',
    fontWeight: '600',
    color: 'var(--admin-muted)',
    textTransform: 'uppercase',
    letterSpacing: '0.05em'
  };

  const inputStyle = {
    width: '100%',
    padding: '12px 14px',
    border: '1px solid var(--admin-border)',
    borderRadius: '4px',
    background: '#fff',
    color: 'var(--admin-ink)',
    fontSize: '0.95rem',
    fontFamily: 'inherit',
    transition: 'border-color 0.2s',
    outline: 'none'
  };

  return (
    <div style={containerStyle}>
      {label && <label style={labelStyle}>{label}</label>}
      <input
        type={type}
        name={name}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required={required}
        readOnly={readOnly}
        style={inputStyle}
        onFocus={(e) => e.target.style.borderColor = 'var(--admin-accent)'}
        onBlur={(e) => e.target.style.borderColor = 'var(--admin-border)'}
      />
    </div>
  );
}
