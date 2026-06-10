"use client";

import React, { useState, useEffect, Suspense } from 'react';
import Link from 'next/link';
import { useSearchParams } from 'next/navigation';
import { Plus, Edit, Trash2 } from 'lucide-react';
import Button from '../components/ui/Button';
import Table from '../components/ui/Table';

function DirectoryAdminContent() {
  const searchParams = useSearchParams();
  const initialCategory = searchParams.get('category') || '';
  
  const [items, setItems] = useState([]);
  const [filterCategory, setFilterCategory] = useState(initialCategory);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setFilterCategory(searchParams.get('category') || '');
  }, [searchParams]);

  const fetchItems = async () => {
    try {
      const url = filterCategory 
        ? `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory?category=${filterCategory}`
        : `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory`;
        
      const res = await fetch(url);
      if (res.ok) {
        const data = await res.json();
        setItems(data.items);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchItems();
  }, [filterCategory]);

  const handleDelete = async (id) => {
    if (!confirm('Sei sicuro di voler eliminare questo elemento?')) return;
    
    try {
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      
      if (res.ok) {
        fetchItems();
      } else {
        alert("Errore durante l'eliminazione");
      }
    } catch (err) {
      alert(err.message);
    }
  };

  const categories = [
    { id: 'comunita', name: 'Comunità - Associazioni' },
    { id: 'eventi_annuali', name: 'Eventi Annuali (Card)' },
    { id: 'territorio_aziende', name: 'Territorio - Aziende Agricole' },
    { id: 'territorio_foodtruck', name: 'Territorio - Food Truck' },
    { id: 'territorio_artigiani', name: 'Territorio - Artigiani' },
    { id: 'sapori_piatti', name: 'Piatti Tipici' }
  ];

  const tableColumns = [
    { label: 'Titolo' },
    { label: 'Categoria' },
    { label: 'Azioni', align: 'right' }
  ];

  return (
    <div>
      <div className="admin-page-header">
        <h1 className="admin-page-title">Attività <span>e Territorio</span></h1>
        <Link href={`/admin/directory/edit/new?category=${filterCategory}`} style={{ textDecoration: 'none' }}>
          <Button icon={<Plus size={16} />}>Nuovo Elemento</Button>
        </Link>
      </div>

      <div style={{ marginBottom: '20px' }}>
        <select 
          value={filterCategory} 
          onChange={(e) => setFilterCategory(e.target.value)}
          style={{ padding: '10px 20px', background: 'var(--admin-bg)', color: 'var(--admin-ink)', border: '1px solid var(--admin-border)', borderRadius: '4px', fontSize: '1rem', outline: 'none' }}
        >
          <option value="">Tutte le categorie</option>
          {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
        </select>
      </div>

      {loading ? (
        <div style={{ color: 'var(--admin-muted)' }}>Caricamento in corso...</div>
      ) : (
        <Table
          columns={tableColumns}
          data={items}
          renderRow={(item) => (
            <tr key={item.id}>
              <td>
                <strong>{item.title}</strong>
                <div style={{ fontSize: '0.85rem', color: 'var(--admin-muted)' }}>{item.subtitle}</div>
              </td>
              <td>
                <span style={{ background: 'rgba(212, 175, 55, 0.1)', color: 'var(--admin-accent)', padding: '4px 8px', borderRadius: '4px', fontSize: '0.8rem', fontWeight: 'bold' }}>
                  {item.category}
                </span>
              </td>
              <td style={{ textAlign: 'right' }}>
                <div className="flex-row" style={{ justifyContent: 'flex-end', gap: '8px' }}>
                  <Link href={`/admin/directory/edit/${item.id}`} style={{ textDecoration: 'none' }}>
                    <Button variant="ghost" icon={<Edit size={16} />} />
                  </Link>
                  <Button variant="ghost" icon={<Trash2 size={16} color="#ef4444" />} onClick={() => handleDelete(item.id)} />
                </div>
              </td>
            </tr>
          )}
        />
      )}
    </div>
  );
}

export default function DirectoryAdmin() {
  return (
    <Suspense fallback={<div style={{ color: 'var(--admin-muted)' }}>Caricamento...</div>}>
      <DirectoryAdminContent />
    </Suspense>
  );
}
