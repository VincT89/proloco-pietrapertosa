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
    <div className="admin-modal-overlay">
      <div className="admin-modal">
        {/* Header */}
        <div className="admin-modal-header">
          <div>
            <h2 className="admin-modal-title">Seleziona Immagini</h2>
            <p className="admin-modal-subtitle">
              Seleziona una o più immagini dalla tua libreria
            </p>
          </div>
          <button onClick={onClose} className="admin-modal-close">
            <X size={24} />
          </button>
        </div>

        {/* Content */}
        <div className="admin-modal-content">
          {loading ? (
            <div className="admin-modal-loading">
              <Loader2 className="animate-spin" size={32} />
              <span>Caricamento...</span>
            </div>
          ) : error ? (
            <div className="admin-modal-error">
              {error}
            </div>
          ) : media.length === 0 ? (
            <div className="admin-modal-empty">
              Nessun media presente nella libreria.
            </div>
          ) : (
            <div className="admin-modal-grid">
              {media.map((item) => {
                const isSelected = selectedUrls.includes(item.url);
                return (
                  <div 
                    key={item.id}
                    onClick={() => toggleSelection(item)}
                    className={`admin-modal-item ${isSelected ? 'selected' : ''}`}
                  >
                    <img 
                      src={item.url} 
                      alt={item.public_id} 
                      className="admin-modal-img" 
                    />
                    
                    {isSelected && (
                      <div className="admin-modal-check">
                        <Check size={16} />
                      </div>
                    )}

                    <div className="admin-modal-filename">
                      {item.public_id.split('/').pop()}
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="admin-modal-footer">
          <Button variant="ghost" onClick={onClose}>Annulla</Button>
          <Button onClick={handleConfirm} disabled={selectedUrls.length === 0}>
            Aggiungi {selectedUrls.length > 0 && `(${selectedUrls.length})`}
          </Button>
        </div>
      </div>
    </div>
  );
}
