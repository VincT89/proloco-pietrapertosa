"use client";

import React, { useState, useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import Link from 'next/link';
import { ArrowLeft, Save, Plus, X } from 'lucide-react';
import MediaLibraryModal from '../../../components/MediaLibraryModal';
import Button from '../../../components/ui/Button';
import Card from '../../../components/ui/Card';
import Input from '../../../components/ui/Input';

export default function GalleryEdit() {
  const router = useRouter();
  const { id } = useParams();
  const isNew = id === 'new';

  const [formData, setFormData] = useState({
    title: '',
    title_en: '',
    media_urls: [],
    section_date: '',
    sort_order: 0
  });

  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(!isNew);
  const [error, setError] = useState('');
  const [isMediaModalOpen, setIsMediaModalOpen] = useState(false);

  useEffect(() => {
    if (!isNew) {
      fetchItem();
    }
  }, [id]);

  const fetchItem = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
      const res = await fetch(`${apiUrl}/gallery`);
      if (!res.ok) throw new Error('Errore nel caricamento dei dati');
      const data = await res.json();
      const item = data.items.find(i => i.id.toString() === id);
      if (item) {
        setFormData({
          title: item.title || '',
          title_en: item.title_en || '',
          media_urls: item.media_urls || [],
          section_date: item.section_date ? item.section_date.split('T')[0] : '',
          sort_order: item.sort_order || 0
        });
      } else {
        setError('Elemento non trovato');
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setFetching(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleMediaSelect = (selectedMediaArray) => {
    setFormData(prev => {
      // Filter out URLs that are already in the gallery to avoid duplicates
      const newUrls = selectedMediaArray
        .map(media => media.url)
        .filter(url => !prev.media_urls.includes(url));
        
      return { 
        ...prev, 
        media_urls: [...prev.media_urls, ...newUrls]
      };
    });
    setIsMediaModalOpen(false);
  };

  const removeMedia = (index) => {
    setFormData(prev => {
      const newUrls = [...prev.media_urls];
      newUrls.splice(index, 1);
      return { ...prev, media_urls: newUrls };
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (formData.media_urls.length === 0) {
      setError('Devi aggiungere almeno una foto alla sezione.');
      return;
    }

    setLoading(true);
    setError('');

    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api';
      const token = localStorage.getItem('admin_token');
      
      const payload = {
        title: formData.title,
        title_en: formData.title_en,
        media_urls: formData.media_urls,
        section_date: formData.section_date || null,
        sort_order: formData.sort_order
      };

      const res = await fetch(`${apiUrl}/gallery${isNew ? '' : `/${id}`}`, {
        method: isNew ? 'POST' : 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        const errData = await res.json();
        throw new Error(errData.error || 'Errore nel salvataggio');
      }

      router.push('/admin/gallery');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (fetching) return <div style={{ padding: '40px', color: 'var(--admin-muted)' }}>Caricamento...</div>;

  return (
    <div style={{ width: '100%' }}>
      <div className="admin-page-header">
        <div className="flex-row" style={{ gap: '16px' }}>
          <Link href="/admin/gallery" style={{ color: 'var(--admin-muted)' }}>
            <ArrowLeft size={24} />
          </Link>
          <h1 className="admin-page-title">{isNew ? 'Crea Sezione' : 'Modifica Sezione'}</h1>
        </div>
      </div>

      {error && <div style={{ background: '#fef2f2', color: '#991b1b', padding: '16px', borderRadius: '8px', border: '1px solid #f87171', marginBottom: '24px' }}>{error}</div>}

      <form onSubmit={handleSubmit} className="flex-col">
        <Card>
          <div className="grid-2">
            <Input 
              label="Titolo Sezione (Italiano) *"
              type="text" 
              name="title" 
              value={formData.title} 
              onChange={handleChange} 
              placeholder="Es. Festa Patronale 2026"
              required
            />
            <Input 
              label="Titolo Sezione (Inglese)"
              type="text" 
              name="title_en" 
              value={formData.title_en} 
              onChange={handleChange} 
              placeholder="Es. Patronal Festival 2026"
            />
          </div>

          <div className="grid-2" style={{ marginTop: '20px' }}>
            <Input 
              label="Data di Riferimento (Per ordinamento)"
              type="date" 
              name="section_date" 
              value={formData.section_date} 
              onChange={handleChange} 
            />
            <Input 
              label="Ordine (Opzionale)"
              type="number" 
              name="sort_order" 
              value={formData.sort_order} 
              onChange={handleChange} 
            />
          </div>
        </Card>

        <Card>
          <div className="flex-col">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <label style={{ display: 'block', color: 'var(--admin-muted)', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                Immagini della Sezione ({formData.media_urls.length})
              </label>
              <Button type="button" icon={<Plus size={14} />} onClick={() => setIsMediaModalOpen(true)} style={{ padding: '8px 16px', fontSize: '0.85rem' }}>
                Aggiungi dalla Libreria
              </Button>
            </div>
            
            <div className="admin-gallery-grid" style={{ background: 'var(--admin-bg)', padding: '20px', borderRadius: '8px', border: '1px solid var(--admin-border)' }}>
              {formData.media_urls.length === 0 ? (
                <div style={{ gridColumn: '1 / -1', textAlign: 'center', color: 'var(--admin-muted)', padding: '20px' }}>
                  Nessuna immagine aggiunta. Clicca su "Aggiungi dalla Libreria".
                </div>
              ) : (
                formData.media_urls.map((url, idx) => (
                  <div key={idx} style={{ position: 'relative', aspectRatio: '1', borderRadius: '6px', overflow: 'hidden', border: '1px solid var(--admin-border)' }}>
                    <img src={url} alt="Media" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    <button 
                      type="button" 
                      onClick={() => removeMedia(idx)}
                      style={{ position: 'absolute', top: '5px', right: '5px', background: '#ef4444', color: 'white', border: 'none', borderRadius: '50%', width: '24px', height: '24px', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer' }}
                      title="Rimuovi"
                    >
                      <X size={14} />
                    </button>
                  </div>
                ))
              )}
            </div>
          </div>
        </Card>

        <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: '16px' }}>
          <Button type="submit" disabled={loading} icon={<Save size={18} />}>
            {loading ? 'Salvataggio...' : 'Salva Sezione'}
          </Button>
        </div>
      </form>

      {isMediaModalOpen && (
        <MediaLibraryModal 
          onClose={() => setIsMediaModalOpen(false)} 
          onSelect={handleMediaSelect} 
        />
      )}
    </div>
  );
}
