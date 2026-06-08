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
          <input ref={fileInputRef} type="file" multiple style={{ display: 'none' }} accept="image/*" onChange={handleFileUpload} disabled={uploading} />
        </div>
      </div>

      {error && (
        <div style={{ background: '#fef2f2', border: '1px solid #fca5a5', color: '#b91c1c', padding: '16px', borderRadius: 'var(--admin-radius)', marginBottom: '24px' }}>
          {error}
        </div>
      )}

      {loading ? (
        <div style={{ textAlign: 'center', padding: '60px', color: 'var(--admin-muted)' }}>Caricamento libreria...</div>
      ) : media.length === 0 ? (
        <Card style={{ textAlign: 'center', padding: '80px 40px' }}>
          <ImageIcon size={48} color="var(--admin-muted)" style={{ margin: '0 auto 16px', opacity: 0.5 }} />
          <h3 style={{ color: 'var(--admin-ink)', fontSize: '1.2rem', marginBottom: '8px' }}>Nessuna immagine presente</h3>
          <p style={{ color: 'var(--admin-muted)' }}>Usa il pulsante in alto a destra per caricare la tua prima immagine.</p>
        </Card>
      ) : (
        <div className="admin-gallery-grid" style={{ gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))' }}>
          {media.map((item) => (
            <div key={item.id} style={{
              background: 'var(--admin-panel)', borderRadius: 'var(--admin-radius)', overflow: 'hidden', border: '1px solid var(--admin-border)',
              position: 'relative', boxShadow: 'var(--admin-shadow)'
            }}>
              <div style={{ width: '100%', aspectRatio: '1', position: 'relative' }}>
                {/* eslint-disable-next-line @next/next/no-img-element */}
                {item.resource_type === 'video' ? (
                  <video src={item.url} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                ) : (
                  <img src={item.url} alt={item.alt || 'Media'} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                )}
                
                {/* Overlay actions */}
                <div style={{
                  position: 'absolute', inset: 0, background: 'rgba(0,0,0,0.5)', display: 'flex', alignItems: 'center', justifyContent: 'center',
                  opacity: 0, transition: 'opacity 0.2s'
                }} className="hover-overlay">
                  <button onClick={() => handleDelete(item.id)} style={{
                    background: '#ef4444', color: 'white', border: 'none', padding: '10px', borderRadius: '50%', cursor: 'pointer',
                    display: 'flex', alignItems: 'center', justifyContent: 'center'
                  }}>
                    <Trash2 size={18} />
                  </button>
                </div>
              </div>
              <div style={{ padding: '12px' }}>
                <div style={{ fontSize: '0.8rem', color: 'var(--admin-muted)', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                  {item.folder} / {item.public_id.split('/').pop()}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
      <style dangerouslySetInnerHTML={{__html: `
        .hover-overlay:hover { opacity: 1 !important; }
        .admin-gallery-grid > div:hover .hover-overlay { opacity: 1 !important; }
      `}} />
    </div>
  );
}
