"use client";

import React, { useState, useEffect } from 'react';
import { Upload, Trash2, Image as ImageIcon, Loader2 } from 'lucide-react';
import Button from '../components/ui/Button';
import Card from '../components/ui/Card';

export default function MediaLibrary() {
  const [media, setMedia] = useState([]);
  const [loading, setLoading] = useState(true);
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState('');
  const fileInputRef = React.useRef(null);

  const fetchMedia = async () => {
    try {
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/media`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (!res.ok) throw new Error('Errore nel caricamento media');
      const data = await res.json();
      setMedia(data.media);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchMedia();
  }, []);

  const handleFileUpload = async (e) => {
    const files = Array.from(e.target.files || []);
    if (files.length === 0) return;

    setUploading(true);
    setError('');

    try {
      const token = localStorage.getItem('admin_token');
      
      const uploadPromises = files.map(async (file) => {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('folder', 'gallery');

        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/media/upload`, {
          method: 'POST',
          headers: { 'Authorization': `Bearer ${token}` },
          body: formData
        });

        if (!res.ok) {
          const errorData = await res.json();
          throw new Error(errorData.error || `Errore upload per ${file.name}`);
        }
      });

      await Promise.all(uploadPromises);
      await fetchMedia(); 
    } catch (err) {
      setError(err.message);
    } finally {
      setUploading(false);
      // Reset input value to allow selecting the same file(s) again
      if (e.target) e.target.value = '';
    }
  };

  const handleDelete = async (id) => {
    if (!confirm("Sei sicuro di voler eliminare questa immagine? L'operazione è irreversibile.")) return;

    try {
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/media/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
      });

      if (!res.ok) throw new Error("Errore durante l'eliminazione");
      await fetchMedia(); 
    } catch (err) {
      alert(err.message);
    }
  };

  return (
    <div>
      <div className="admin-page-header">
        <div>
          <h1 className="admin-page-title">Media Library <span>gestione immagini</span></h1>
        </div>

        <div>
          <Button 
            onClick={() => fileInputRef.current?.click()} 
            disabled={uploading} 
            icon={uploading ? <Loader2 className="animate-spin" size={16} /> : <Upload size={16} />}
          >
            {uploading ? 'Caricamento...' : 'Carica Immagine'}
          </Button>
          <input ref={fileInputRef} type="file" multiple className="hidden-input" accept="image/*" onChange={handleFileUpload} disabled={uploading} />
        </div>
      </div>

      {error && (
        <div className="admin-media-error">
          {error}
        </div>
      )}

      {loading ? (
        <div className="admin-media-loading">Caricamento libreria...</div>
      ) : media.length === 0 ? (
        <Card className="admin-media-empty">
          <ImageIcon size={48} className="admin-media-empty-icon" />
          <h3 className="admin-media-empty-title">Nessuna immagine presente</h3>
          <p className="admin-media-empty-text">Usa il pulsante in alto a destra per caricare la tua prima immagine.</p>
        </Card>
      ) : (
        <div className="admin-gallery-grid-large">
          {media.map((item) => (
            <div key={item.id} className="admin-media-card">
              <div className="admin-media-thumb-wrap">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                {item.resource_type === 'video' ? (
                  <video src={item.url} className="admin-media-thumb" />
                ) : (
                  <img src={item.url} alt={item.alt || 'Media'} className="admin-media-thumb" />
                )}
                
                {/* Overlay actions */}
                <div className="admin-media-overlay hover-overlay">
                  <button onClick={() => handleDelete(item.id)} className="admin-media-delete-btn">
                    <Trash2 size={18} />
                  </button>
                </div>
              </div>
              <div className="admin-media-info">
                <div className="admin-media-filename">
                  {item.folder} / {item.public_id.split('/').pop()}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
