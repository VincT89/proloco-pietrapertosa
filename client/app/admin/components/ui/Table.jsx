import React from 'react';

export default function Table({ columns, data, emptyMessage = "Nessun dato presente", renderRow }) {
  return (
    <div className="admin-table-wrap">
      <table className="admin-table">
        <thead>
          <tr>
            {columns.map((col, idx) => {
              const alignClass = `text-${col.align || 'left'}`;
              const widthClass = col.width ? `w-${col.width.replace('px', 'px')}` : 'w-auto';
              return (
                <th key={idx} className={`${alignClass} ${widthClass}`}>
                  {col.label}
                </th>
              );
            })}
          </tr>
        </thead>
        <tbody>
          {data.length === 0 ? (
            <tr>
              <td colSpan={columns.length} className="admin-table-empty">
                {emptyMessage}
              </td>
            </tr>
          ) : (
            data.map((item, index) => renderRow(item, index))
          )}
        </tbody>
      </table>
    </div>
  );
}
