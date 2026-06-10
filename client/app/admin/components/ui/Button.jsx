export default function Button({ children, type = 'button', variant = 'primary', onClick, disabled, className = '', icon }) {
  const variantClass = variant ? `admin-btn-${variant}` : 'admin-btn-primary';

  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled}
      className={`admin-btn ${variantClass} ${className}`}
    >
      {icon && <span className="admin-btn-icon">{icon}</span>}
      {children}
    </button>
  );
}

