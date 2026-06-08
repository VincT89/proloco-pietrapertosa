"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { useState, useEffect, use } from 'react';
import { useRouter } from 'next/navigation';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { ArrowLeft, Save } from 'lucide-react';
import 'react-quill-new/dist/quill.snow.css';

import Card from '../../../components/ui/Card';
import Input from '../../../components/ui/Input';
import Button from '../../../components/ui/Button';
import Tabs from '../../../components/ui/Tabs';
import Select from '../../../components/ui/Select';

const ReactQuill = dynamic(() => import('react-quill-new'), { ssr: false });

export default function EventEditor({ params }) {
  const router = useRouter();
  const unwrappedParams = use(params);
  const { id } = unwrappedParams;
  const isNew = id === 'new';

  const [loading, setLoading] = useState(!isNew);
  const [saving, setSaving] = useState(false);
  const [mediaList, setMediaList] = useState([]);
  
  const [formData, setFormData] = useState({
    title: '', title_en: '',
    description: '', description_en: '',
    start_date: '',
    end_date: '',
    location: '', location_en: '',
    category: '', category_en: '',
    status: 'draft',
    cover_media_id: '',
    gallery: []
  });

  useEffect(() => {
    const fetchMedia = async () => {
      try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/media`, {
          headers: { 'Authorization': `Bearer ${localStorage.getItem('admin_token')}` }
        });
        if (res.ok) {
          const data = await res.json();
          setMediaList(data.media);
        }
      } catch (err) { console.error(err); }
    };
    fetchMedia();

    if (!isNew) {
      const fetchEventItem = async () => {
        try {
          const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/events/${id}`);
          if (res.ok) {
            const data = await res.json();
            setFormData({
              title: data.event.title || '',
              title_en: data.event.title_en || '',
              description: data.event.description || '',
              description_en: data.event.description_en || '',
              start_date: data.event.start_date ? new Date(data.event.start_date).toISOString().split('T')[0] : '',
              end_date: data.event.end_date ? new Date(data.event.end_date).toISOString().split('T')[0] : '',
              location: data.event.location || '',
              location_en: data.event.location_en || '',
              category: data.event.category || '',
              category_en: data.event.category_en || '',
              status: data.event.status || 'draft',
              cover_media_id: data.event.cover_media_id || '',
              gallery: typeof data.event.gallery === 'string' ? safeJsonParse(data.event.gallery) : (data.event.gallery || [])
            });
          }
        } catch (err) {
          console.error(err);
        } finally {
          setLoading(false);
        }
      };
      fetchEventItem();
    }
  }, [id, isNew]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const toggleGallery = (url) => {
    setFormData(prev => {
      const g = prev.gallery || [];
      if (g.includes(url)) {
        return { ...prev, gallery: g.filter(u => u !== url) };
      } else {
        return { ...prev, gallery: [...g, url] };
      }
    });
  };

  const handleEditorChange = (description) => setFormData(prev => ({ ...prev, description }));
  const handleEditorChangeEn = (description_en) => setFormData(prev => ({ ...prev, description_en }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);

    try {
      const token = localStorage.getItem('admin_token');
      const url = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/events${isNew ? '' : `/${id}`}`;
      const method = isNew ? 'POST' : 'PUT';

      const payload = { ...formData };
      if (!payload.cover_media_id) payload.cover_media_id = null;
      if (!payload.start_date) payload.start_date = null;
      if (!payload.end_date) payload.end_date = null;

      const res = await fetch(url, {
        method,
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}` 
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) throw new Error('Errore durante il salvataggio');
      
      router.push('/admin/events');
    } catch (err) {
      alert(err.message);
      setSaving(false);
    }
  };

  const modules = {
    toolbar: [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike', 'blockquote'],
      [{'list': 'ordered'}, {'list': 'bullet'}],
      ['link', 'image', 'video'],
      ['clean']
    ],
  };

  if (loading) return <div style={{ color: 'var(--admin-muted)' }}>Caricamento in corso...</div>;

  const tabIT = (
    <div className="flex-col">
      <Input label="Titolo (IT)" name="title" value={formData.title} onChange={handleChange} required />
      <div className="grid-2">
        <Input label="Luogo (IT)" name="location" value={formData.location} onChange={handleChange} placeholder="Es. Piazza Vittorio Emanuele" />
        <Input label="Categoria (IT)" name="category" value={formData.category} onChange={handleChange} placeholder="Es. Sagra, Cultura, Sport..." />
      </div>
      <div>
        <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Descrizione Dettagliata (IT)</label>
        <div style={{ background: 'white', borderRadius: '4px', border: '1px solid var(--admin-border)', overflow: 'hidden' }}>
          <ReactQuill theme="snow" value={formData.description} onChange={handleEditorChange} modules={modules} style={{ height: '400px' }} />
        </div>
      </div>
    </div>
  );

  const tabEN = (
    <div className="flex-col">
      <Input label="Titolo (EN)" name="title_en" value={formData.title_en} onChange={handleChange} />
      <div className="grid-2">
        <Input label="Luogo (EN)" name="location_en" value={formData.location_en} onChange={handleChange} placeholder="e.g. Vittorio Emanuele Square" />
        <Input label="Categoria (EN)" name="category_en" value={formData.category_en} onChange={handleChange} placeholder="e.g. Festival, Culture, Sport..." />
      </div>
      <div>
        <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Descrizione Dettagliata (EN)</label>
        <div style={{ background: 'white', borderRadius: '4px', border: '1px solid var(--admin-border)', overflow: 'hidden' }}>
          <ReactQuill theme="snow" value={formData.description_en} onChange={handleEditorChangeEn} modules={modules} style={{ height: '400px' }} />
        </div>
      </div>
    </div>
  );

  return (
    <div style={{ maxWidth: '1000px' }}>
      <div className="admin-page-header">
        <div className="flex-row" style={{ gap: '16px' }}>
          <Link href="/admin/events" style={{ color: 'var(--admin-muted)' }}>
            <ArrowLeft size={24} />
          </Link>
          <h1 className="admin-page-title">{isNew ? 'Nuovo Evento' : 'Modifica Evento'}</h1>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="flex-col">
        
        <Card>
          <div className="grid-3">
            <Select 
              label="Stato" 
              name="status" 
              value={formData.status} 
              onChange={handleChange}
              options={[
                { value: "draft", label: "Bozza (Nascosta)" },
                { value: "published", label: "Pubblicato (Visibile)" }
              ]}
            />
            <Input label="Data Inizio" type="date" name="start_date" value={formData.start_date} onChange={handleChange} required />
            <Input label="Data Fine (opzionale)" type="date" name="end_date" value={formData.end_date} onChange={handleChange} />
          </div>
        </Card>

        <Card>
          <Tabs tabs={[
            { label: 'Italiano', content: tabIT },
            { label: 'Inglese (Opzionale)', content: tabEN }
          ]} />
        </Card>

        <Card>
          <div className="flex-col">
            <Select 
              label="Immagine di Copertina" 
              name="cover_media_id" 
              value={formData.cover_media_id} 
              onChange={handleChange}
              options={[
                { value: "", label: "-- Nessuna Copertina --" },
                ...mediaList.map(m => ({ value: m.id, label: `${m.folder}/${m.public_id.split('/').pop()}` }))
              ]}
            />
          </div>

          <div className="flex-col" style={{ marginTop: '24px' }}>
            <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Galleria di Immagini e Video</label>
            <div className="admin-gallery-grid">
              {mediaList.length === 0 ? <span style={{color: 'var(--admin-muted)'}}>Nessun media caricato.</span> : mediaList.map(media => {
                const isSelected = (formData.gallery || []).includes(media.url);
                return (
                  <div 
                    key={media.id} 
                    onClick={() => toggleGallery(media.url)}
                    style={{ 
                      cursor: 'pointer', position: 'relative', aspectRatio: '1', borderRadius: '4px', overflow: 'hidden',
                      border: isSelected ? '3px solid var(--admin-accent)' : '1px solid var(--admin-border)'
                    }}
                  >
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    {media.resource_type === 'video' ? (
                      <video src={media.url} style={{ width: '100%', height: '100%', objectFit: 'cover', opacity: isSelected ? 1 : 0.6 }} />
                    ) : (
                      <img src={media.url} alt="Media" style={{ width: '100%', height: '100%', objectFit: 'cover', opacity: isSelected ? 1 : 0.6 }} />
                    )}
                    {isSelected && <div style={{ position: 'absolute', top: 5, right: 5, background: 'var(--admin-accent)', color: 'white', borderRadius: '50%', width: 20, height: 20, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.8rem', fontWeight: 'bold' }}>✓</div>}
                  </div>
                );
              })}
            </div>
          </div>
        </Card>

        <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: '16px' }}>
          <Button type="submit" disabled={saving} icon={<Save size={16} />}>
            {saving ? 'Salvataggio...' : 'Salva Evento'}
          </Button>
        </div>

      </form>
    </div>
  );
}
