"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { useState, useEffect, Suspense, use } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { ArrowLeft, Save } from 'lucide-react';

import Card from '@/app/admin/components/ui/Card';
import Input from '@/app/admin/components/ui/Input';
import Textarea from '@/app/admin/components/ui/Textarea';
import Button from '@/app/admin/components/ui/Button';
import Tabs from '@/app/admin/components/ui/Tabs';

function DirectoryEditContent({ id }) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const isNew = id === 'new';

  const defaultCategory = searchParams.get('category') || 'territorio_aziende';

  const [formData, setFormData] = useState({
    category: defaultCategory,
    title: '', title_en: '',
    subtitle: '', subtitle_en: '',
    description: '', description_en: '',
    contact_info: '', contact_info_en: '',
    gallery: []
  });
  
  const [mediaList, setMediaList] = useState([]);
  const [loading, setLoading] = useState(!isNew);
  const [saving, setSaving] = useState(false);

  const categories = [
    { id: 'comunita', name: 'Comunità - Associazioni' },
    { id: 'eventi_annuali', name: 'Eventi Annuali (Card)' },
    { id: 'territorio_aziende', name: 'Territorio - Aziende Agricole' },
    { id: 'territorio_foodtruck', name: 'Territorio - Food Truck' },
    { id: 'territorio_artigiani', name: 'Territorio - Artigiani' },
    { id: 'sapori_piatti', name: 'Sapori - Piatti Tipici' }
  ];

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
      const fetchItem = async () => {
        try {
          const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory/${id}`);
          if (res.ok) {
            const data = await res.json();
            setFormData({
              category: data.item.category || 'territorio_aziende',
              title: data.item.title || '',
              title_en: data.item.title_en || '',
              subtitle: data.item.subtitle || '',
              subtitle_en: data.item.subtitle_en || '',
              description: data.item.description || '',
              description_en: data.item.description_en || '',
              contact_info: data.item.contact_info || '',
              contact_info_en: data.item.contact_info_en || '',
              gallery: typeof data.item.gallery === 'string' ? safeJsonParse(data.item.gallery) : (data.item.gallery || [])
            });
          }
        } catch (err) {
          console.error(err);
        } finally {
          setLoading(false);
        }
      };
      fetchItem();
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

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);

    try {
      const token = localStorage.getItem('admin_token');
      const url = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory${isNew ? '' : `/${id}`}`;
      const method = isNew ? 'POST' : 'PUT';

      const res = await fetch(url, {
        method,
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}` 
        },
        body: JSON.stringify(formData)
      });

      if (!res.ok) throw new Error('Errore durante il salvataggio');
      
      router.push('/admin/directory');
    } catch (err) {
      alert(err.message);
      setSaving(false);
    }
  };

  if (loading) return <div className="admin-loading-text">Caricamento in corso...</div>;

  const tabIT = (
    <div className="flex-col">
      <div className="grid-2">
        <Input label="Titolo (Nome - IT)" name="title" value={formData.title} onChange={handleChange} required />
        <Input label="Sottotitolo (IT)" name="subtitle" value={formData.subtitle} onChange={handleChange} />
      </div>
      <Textarea label="Descrizione (IT)" name="description" value={formData.description} onChange={handleChange} rows={5} />
      <Input label="Info Contatti (Telefono / Email / Fonte - IT)" name="contact_info" value={formData.contact_info} onChange={handleChange} />
    </div>
  );

  const tabEN = (
    <div className="flex-col">
      <div className="grid-2">
        <Input label="Titolo (Nome - EN)" name="title_en" value={formData.title_en} onChange={handleChange} />
        <Input label="Sottotitolo (EN)" name="subtitle_en" value={formData.subtitle_en} onChange={handleChange} />
      </div>
      <Textarea label="Descrizione (EN)" name="description_en" value={formData.description_en} onChange={handleChange} rows={5} />
      <Input label="Info Contatti (EN)" name="contact_info_en" value={formData.contact_info_en} onChange={handleChange} />
    </div>
  );

  return (
    <div className="admin-page-container-small">
      <div className="admin-page-header">
        <div className="flex-row">
          <Link href="/admin/directory" className="admin-loading-text">
            <ArrowLeft size={24} />
          </Link>
          <h1 className="admin-page-title">{isNew ? 'Nuovo Elemento' : 'Modifica Elemento'}</h1>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="flex-col">
        <Card>
          <div className="flex-col">
            <label className="admin-form-label">Categoria</label>
            <select 
              name="category" value={formData.category} onChange={handleChange}
              className="admin-form-select"
            >
              {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
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
            <label className="admin-form-label">Immagini Selezionate (Clicca sulla X per rimuovere)</label>
            <div className="admin-gallery-preview-container">
              {(formData.gallery || []).map((url, i) => (
                <div 
                  key={i} 
                  className="admin-gallery-preview-item"
                  onClick={() => toggleGallery(url)}
                >
                  <img src={url} alt="Selected" className="admin-gallery-preview-img" />
                  <div className="admin-gallery-remove">&times;</div>
                </div>
              ))}
              {(formData.gallery || []).length === 0 && <span className="admin-loading-text">Nessuna immagine selezionata</span>}
            </div>

            <label className="admin-form-label">Scegli dalla Libreria Media (Clicca per aggiungere/rimuovere)</label>
            <div className="admin-gallery-grid">
              {mediaList.length === 0 ? <span className="admin-loading-text">Nessun media caricato.</span> : mediaList.map(media => {
                const isSelected = (formData.gallery || []).includes(media.url);
                return (
                  <div 
                    key={media.id} 
                    onClick={() => toggleGallery(media.url)}
                    className={`admin-gallery-select-item ${isSelected ? 'selected' : 'unselected'}`}
                  >
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
            {saving ? 'Salvataggio...' : 'Salva Elemento'}
          </Button>
        </div>
      </form>
    </div>
  );
}

export default function DirectoryEdit({ params }) {
  const unwrappedParams = use(params);
  return (
    <Suspense fallback={<div className="admin-loading-text">Caricamento...</div>}>
      <DirectoryEditContent id={unwrappedParams.id} />
    </Suspense>
  );
}
