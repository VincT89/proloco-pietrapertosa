"use client";

import React, { useState, useEffect } from 'react';
import { X, Loader2, Check } from 'lucide-react';
import Button from './ui/Button';

export default function MediaLibraryModal({ onClose, onSelect }) {
  const [media, setMedia] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [selectedUrls, setSelectedUrls] = useState([]);

  useEffect(() => {
    const fetchMedia = async () => {
      try {
        const token = localStorage.getItem('admin_token');
        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/media`, {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!res.ok) throw new Error('Errore nel caricamento media');
        const data = await res.json();
        setMedia(data.media || []);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchMedia();
  }, []);

  const toggleSelection = (item) => {
    if (selectedUrls.includes(item.url)) {
      setSelectedUrls(selectedUrls.filter(u => u !== item.url));
    } else {
      setSelectedUrls([...selectedUrls, item.url]);
    }
  };

  const handleConfirm = () => {
    const selectedMedia = media.filter(m => selectedUrls.includes(m.url));
    onSelect(selectedMedia);
  };

  return (
    <div style={{
      position: 'fixed',
      top: 0, left: 0, right: 0, bottom: 0,
      background: 'rgba(0, 0, 0, 0.7)',
      zIndex: 9999,
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      padding: '20px'
    }}>
      <div style={{
        background: 'var(--admin-panel)',
        borderRadius: '8px',
        width: '100%',
        maxWidth: '900px',
        maxHeight: '90vh',
        display: 'flex',
        flexDirection: 'column',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.2)'
      }}>
        {/* Header */}
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          padding: '20px',
          borderBottom: '1px solid var(--admin-border)'
        }}>
          <div>
            <h2 style={{ margin: 0, fontSize: '1.25rem', color: 'var(--admin-ink)' }}>Seleziona Immagini</h2>
            <p style={{ margin: 0, marginTop: '4px', fontSize: '0.85rem', color: 'var(--admin-muted)' }}>
              Seleziona una o più immagini dalla tua libreria
            </p>
          </div>
          <button 
            onClick={onClose}
            style={{ background: 'none', border: 'none', cursor: 'pointer', color: 'var(--admin-muted)' }}
          >
            <X size={24} />
          </button>
        </div>

        {/* Content */}
        <div style={{ padding: '20px', overflowY: 'auto', flex: 1 }}>
          {loading ? (
            <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '200px', color: 'var(--admin-muted)' }}>
              <Loader2 className="animate-spin" size={32} />
              <span style={{ marginLeft: '10px' }}>Caricamento...</span>
            </div>
          ) : error ? (
            <div style={{ color: '#ef4444', textAlign: 'center', padding: '20px' }}>
              {error}
            </div>
          ) : media.length === 0 ? (
            <div style={{ textAlign: 'center', color: 'var(--admin-muted)', padding: '40px' }}>
              Nessun media presente nella libreria.
            </div>
          ) : (
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fill, minmax(150px, 1fr))',
              gap: '16px'
            }}>
              {media.map((item) => {
                const isSelected = selectedUrls.includes(item.url);
                return (
                  <div 
                    key={item.id}
                    onClick={() => toggleSelection(item)}
                    style={{
                      position: 'relative',
                      aspectRatio: '1',
                      borderRadius: '6px',
                      overflow: 'hidden',
                      border: isSelected ? '3px solid var(--admin-accent)' : '1px solid var(--admin-border)',
                      cursor: 'pointer',
                      transition: 'all 0.2s ease',
                      opacity: isSelected ? 1 : 0.8
                    }}
                    onMouseEnter={(e) => { if (!isSelected) e.currentTarget.style.borderColor = 'var(--admin-accent)' }}
                    onMouseLeave={(e) => { if (!isSelected) e.currentTarget.style.borderColor = 'var(--admin-border)' }}
                  >
                    <img 
                      src={item.url} 
                      alt={item.public_id} 
                      style={{ width: '100%', height: '100%', objectFit: 'cover' }} 
                    />
                    
                    {isSelected && (
                      <div style={{
                        position: 'absolute',
                        top: 5, right: 5,
                        background: 'var(--admin-accent)',
                        color: 'white',
                        borderRadius: '50%',
                        width: '24px', height: '24px',
                        display: 'flex', alignItems: 'center', justifyContent: 'center'
                      }}>
                        <Check size={16} />
                      </div>
                    )}

                    <div style={{
                      position: 'absolute',
                      bottom: 0, left: 0, right: 0,
                      background: 'rgba(0,0,0,0.6)',
                      color: 'white',
                      padding: '4px 8px',
                      fontSize: '0.7rem',
                      whiteSpace: 'nowrap',
                      overflow: 'hidden',
                      textOverflow: 'ellipsis'
                    }}>
                      {item.public_id.split('/').pop()}
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>

        {/* Footer */}
        <div style={{
          padding: '20px',
          borderTop: '1px solid var(--admin-border)',
          display: 'flex',
          justifyContent: 'flex-end',
          gap: '12px'
        }}>
          <Button variant="ghost" onClick={onClose}>Annulla</Button>
          <Button onClick={handleConfirm} disabled={selectedUrls.length === 0}>
            Aggiungi {selectedUrls.length > 0 && `(${selectedUrls.length})`}
          </Button>
        </div>
      </div>
    </div>
  );
}
