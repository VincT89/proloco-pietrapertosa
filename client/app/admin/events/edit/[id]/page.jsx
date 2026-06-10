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

  if (loading) return <div className="admin-loading-text">Caricamento in corso...</div>;

  const tabIT = (
    <div className="flex-col">
      <Input label="Titolo (IT)" name="title" value={formData.title} onChange={handleChange} required />
      <div className="grid-2">
        <Input label="Luogo (IT)" name="location" value={formData.location} onChange={handleChange} placeholder="Es. Piazza Vittorio Emanuele" />
        <Input label="Categoria (IT)" name="category" value={formData.category} onChange={handleChange} placeholder="Es. Sagra, Cultura, Sport..." />
      </div>
      <div>
        <label className="admin-form-label">Descrizione Dettagliata (IT)</label>
        <div className="admin-quill-wrap">
          <ReactQuill theme="snow" value={formData.description} onChange={handleEditorChange} modules={modules} className="admin-quill" />
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
        <label className="admin-form-label">Descrizione Dettagliata (EN)</label>
        <div className="admin-quill-wrap">
          <ReactQuill theme="snow" value={formData.description_en} onChange={handleEditorChangeEn} modules={modules} className="admin-quill" />
        </div>
      </div>
    </div>
  );

  return (
    <div className="admin-page-container-small">
      <div className="admin-page-header">
        <div className="flex-row">
          <Link href="/admin/events" className="admin-loading-text">
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

          <div className="flex-col mt-24px">
            <label className="admin-form-label">Galleria di Immagini e Video</label>
            <div className="admin-gallery-grid">
              {mediaList.length === 0 ? <span className="admin-loading-text">Nessun media caricato.</span> : mediaList.map(media => {
                const isSelected = (formData.gallery || []).includes(media.url);
                return (
                  <div 
                    key={media.id} 
                    onClick={() => toggleGallery(media.url)}
                    className={`admin-gallery-select-item ${isSelected ? 'selected' : 'unselected'}`}
                  >
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    {media.resource_type === 'video' ? (
                      <video src={media.url} className={`admin-gallery-select-img ${isSelected ? 'selected' : 'unselected'}`} />
                    ) : (
                      <img src={media.url} alt="Media" className={`admin-gallery-select-img ${isSelected ? 'selected' : 'unselected'}`} />
                    )}
                    {isSelected && <div className="admin-gallery-check">✓</div>}
                  </div>
                );
              })}
            </div>
          </div>
        </Card>

        <div className="admin-form-actions">
          <Button type="submit" disabled={saving} icon={<Save size={16} />}>
            {saving ? 'Salvataggio...' : 'Salva Evento'}
          </Button>
        </div>

      </form>
    </div>
  );
}
