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

  if (fetching) return <div className="admin-loading-text">Caricamento...</div>;

  return (
    <div className="admin-page-container-small">
      <div className="admin-page-header">
        <div className="flex-row">
          <Link href="/admin/gallery" className="admin-loading-text">
            <ArrowLeft size={24} />
          </Link>
          <h1 className="admin-page-title">{isNew ? 'Crea Sezione' : 'Modifica Sezione'}</h1>
        </div>
      </div>

      {error && <div className="admin-media-error">{error}</div>}

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

          <div className="grid-2">
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
            <div className="flex-row items-center justify-between">
              <label className="admin-form-label mb-0">
                Immagini della Sezione ({formData.media_urls.length})
              </label>
              <Button type="button" icon={<Plus size={14} />} onClick={() => setIsMediaModalOpen(true)}>
                Aggiungi dalla Libreria
              </Button>
            </div>
            
            <div className="admin-gallery-grid p-5 rounded-lg border border-admin-border bg-admin-panel">
              {formData.media_urls.length === 0 ? (
                <div className="admin-modal-empty col-span-full">
                  Nessuna immagine aggiunta. Clicca su "Aggiungi dalla Libreria".
                </div>
              ) : (
                formData.media_urls.map((url, idx) => (
                  <div key={idx} className="admin-modal-item">
                    <img src={url} alt="Media" className="admin-modal-img" />
                    <button 
                      type="button" 
                      onClick={() => removeMedia(idx)}
                      className="admin-gallery-remove"
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

        <div className="admin-form-actions">
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
